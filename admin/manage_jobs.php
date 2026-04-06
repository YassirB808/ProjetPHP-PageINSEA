<?php
require_once 'auth_check.php';
require_once '../components/PHP/db_connect.php';
require_once '../components/PHP/lang_handler.php';
require_once 'image_processor.php';

$message = '';
$edit_mode = false;
$edit_id = 0;
$edit_data = [
    'title_fr' => '',
    'content_fr' => '',
    'link_url' => '',
    'post_date' => date('Y-m-d'),
    'image_url' => ''
];

function autoTranslate($text, $source, $target) {
    if (empty($text)) return '';
    $url = "https://api.mymemory.translated.net/get?q=" . urlencode($text) . "&langpair=" . $source . "|" . $target;
    $response = @file_get_contents($url);
    if ($response) {
        $json = json_decode($response, true);
        return $json['responseData']['translatedText'] ?? $text;
    }
    return $text;
}

// Handle Edit Mode Fetch
if (isset($_GET['edit'])) {
    $edit_mode = true;
    $edit_id = (int)$_GET['edit'];
    $stmt = $pdo->prepare("SELECT jo.*, jot.title, jot.content FROM job_offers jo JOIN job_offers_translations jot ON jo.id = jot.job_offer_id WHERE jo.id = ? AND jot.language_id = 1");
    $stmt->execute([$edit_id]);
    $res = $stmt->fetch();
    if ($res) {
        $edit_data = [
            'title_fr' => $res['title'],
            'content_fr' => $res['content'],
            'link_url' => $res['link_url'],
            'post_date' => $res['post_date'],
            'image_url' => $res['image_url']
        ];
    }
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM job_offers WHERE id = ?");
    if ($stmt->execute([$id])) {
        $message = "<div class='alert alert-success'>Offre supprimée.</div>";
    }
}

// Handle Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title_fr = $_POST['title_fr'] ?? '';
    $content_fr = $_POST['content_fr'] ?? '';
    $link_url = $_POST['link_url'] ?? NULL;
    $post_date = $_POST['post_date'] ?? date('Y-m-d');
    $post_id = $_POST['edit_id'] ?? 0;

    $db_image_path = $_POST['existing_image'] ?? NULL;
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "../components/images/others/";
        $uploaded_file = processAndSaveImage($_FILES['image']['tmp_name'], $target_dir, 'job');
        if ($uploaded_file) {
            $db_image_path = "others/" . $uploaded_file;
        }
    }

    if (!empty($title_fr) && !empty($content_fr)) {
        try {
            $pdo->beginTransaction();

            if ($post_id > 0) {
                $stmt = $pdo->prepare("UPDATE job_offers SET post_date = ?, image_url = ?, link_url = ? WHERE id = ?");
                $stmt->execute([$post_date, $db_image_path, $link_url, $post_id]);
                
                $title_en = autoTranslate($title_fr, 'fr', 'en');
                $content_en = autoTranslate($content_fr, 'fr', 'en');
                $title_ar = autoTranslate($title_fr, 'fr', 'ar');
                $content_ar = autoTranslate($content_fr, 'fr', 'ar');

                $stmt_up_t = $pdo->prepare("UPDATE job_offers_translations SET title = ?, content = ? WHERE job_offer_id = ? AND language_id = 1");
                $stmt_up_t->execute([$title_fr, $content_fr, $post_id]);
                $stmt_up_t2 = $pdo->prepare("UPDATE job_offers_translations SET title = ?, content = ? WHERE job_offer_id = ? AND language_id = 2");
                $stmt_up_t2->execute([$title_en, $content_en, $post_id]);
                $stmt_up_t3 = $pdo->prepare("UPDATE job_offers_translations SET title = ?, content = ? WHERE job_offer_id = ? AND language_id = 3");
                $stmt_up_t3->execute([$title_ar, $content_ar, $post_id]);

                $message = "<div class='alert alert-success'>Offre mise à jour.</div>";
            } else {
                $stmt = $pdo->prepare("INSERT INTO job_offers (post_date, image_url, link_url) VALUES (?, ?, ?)");
                $stmt->execute([$post_date, $db_image_path, $link_url]);
                $job_id = $pdo->lastInsertId();

                $title_en = autoTranslate($title_fr, 'fr', 'en');
                $content_en = autoTranslate($content_fr, 'fr', 'en');
                $title_ar = autoTranslate($title_fr, 'fr', 'ar');
                $content_ar = autoTranslate($content_fr, 'fr', 'ar');

                $stmt_t = $pdo->prepare("INSERT INTO job_offers_translations (job_offer_id, language_id, title, content) VALUES (?, ?, ?, ?)");
                $stmt_t->execute([$job_id, 1, $title_fr, $content_fr]);
                $stmt_t->execute([$job_id, 2, $title_en, $content_en]);
                $stmt_t->execute([$job_id, 3, $title_ar, $content_ar]);

                $message = "<div class='alert alert-success'>Offre publiée.</div>";
            }

            $pdo->commit();
            if ($post_id > 0) { header('Location: manage_jobs.php?msg=updated'); exit; }
        } catch (Exception $e) {
            $pdo->rollBack();
            $message = "<div class='alert alert-error'>Erreur : " . $e->getMessage() . "</div>";
        }
    }
}

if (isset($_GET['msg']) && $_GET['msg'] == 'updated') $message = "<div class='alert alert-success'>Modifications enregistrées.</div>";

$stmt = $pdo->query("SELECT jo.id, jot.title, jo.post_date FROM job_offers jo JOIN job_offers_translations jot ON jo.id = jot.job_offer_id WHERE jot.language_id = 1 ORDER BY jo.post_date DESC");
$jobs = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gérer les Emplois - Admin INSEA</title>
    <link rel="stylesheet" href="../components/CSS/style.css">
    <style>
        body { background: #f0f2f5; padding: 40px; }
        .admin-box { max-width: 1000px; margin: 0 auto; }
        .back-link { display: inline-block; margin-bottom: 20px; color: var(--insea-green); font-weight: 700; text-decoration: none; }
        .list-item { background: white; padding: 20px; border-radius: 12px; margin-bottom: 15px; display: flex; justify-content: space-between; align-items: center; box-shadow: var(--shadow); border: 1px solid var(--gray-200); }
        .btn-delete { color: #dc3545; font-weight: bold; text-decoration: none; }
        .btn-edit { color: var(--insea-green); font-weight: bold; text-decoration: none; margin-right: 15px; }
    </style>
</head>
<body>

    <div class="admin-box">
        <a href="index.php" class="back-link">← Retour au Dashboard</a>
        
        <header class="section-header" style="margin-top: 0; text-align: left;">
            <h1>Gestion des Offres d'Emploi</h1>
            <div class="line" style="margin: 12px 0;"></div>
        </header>

        <?php echo $message; ?>

        <div class="form-card" style="margin-bottom: 40px;">
            <h2 style="margin-bottom: 25px;"><?php echo $edit_mode ? 'Modifier l\'Offre' : 'Publier un Poste'; ?></h2>
            <form action="" method="POST" enctype="multipart/form-data" class="form-grid">
                <input type="hidden" name="edit_id" value="<?php echo $edit_id; ?>">
                <input type="hidden" name="existing_image" value="<?php echo $edit_data['image_url']; ?>">
                
                <div class="form-row-2">
                    <div>
                        <label>Titre du Poste</label>
                        <input type="text" name="title_fr" required value="<?php echo htmlspecialchars($edit_data['title_fr']); ?>">
                    </div>
                    <div>
                        <label>Date de Publication</label>
                        <input type="date" name="post_date" value="<?php echo $edit_data['post_date']; ?>">
                    </div>
                </div>
                <div>
                    <label>Lien Postulation (LinkedIn / Email / etc)</label>
                    <input type="text" name="link_url" placeholder="https://..." value="<?php echo htmlspecialchars($edit_data['link_url']); ?>">
                </div>
                <div>
                    <label>Description (Français)</label>
                    <textarea name="content_fr" rows="5" required><?php echo htmlspecialchars($edit_data['content_fr']); ?></textarea>
                </div>
                <div>
                    <label>Logo / Image <?php echo $edit_data['image_url'] ? '(En laisser vide pour garder l\'actuelle)' : ''; ?></label>
                    <input type="file" name="image" accept="image/*">
                </div>
                <button type="submit" class="btn-form-submit"><?php echo $edit_mode ? 'Enregistrer' : 'Publier'; ?> (Traduction Auto)</button>
                <?php if ($edit_mode): ?>
                    <a href="manage_jobs.php" style="display: block; text-align: center; margin-top: 10px; color: var(--gray-500);">Annuler</a>
                <?php endif; ?>
            </form>
        </div>

        <h2 style="margin-bottom: 20px;">Offres Actives</h2>
        <?php foreach ($jobs as $j): ?>
            <div class="list-item">
                <div>
                    <span style="color: var(--gray-500); font-size: 0.8rem;"><?php echo $j['post_date']; ?></span>
                    <h4 style="margin-top: 5px;"><?php echo htmlspecialchars($j['title']); ?></h4>
                </div>
                <div>
                    <a href="?edit=<?php echo $j['id']; ?>" class="btn-edit">Modifier</a>
                    <a href="?delete=<?php echo $j['id']; ?>" class="btn-delete" onclick="return confirm('Supprimer ?')">Supprimer</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

</body>
</html>
