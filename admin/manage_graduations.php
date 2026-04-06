<?php
require_once 'auth_check.php';
require_once '../components/PHP/db_connect.php';
require_once '../components/PHP/lang_handler.php';
require_once 'image_processor.php';

$message = '';
$edit_mode = false;
$edit_id = 0;
$edit_data = [
    'year' => '',
    'content_fr' => '',
    'image_url_a' => '',
    'image_url_b' => ''
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
    $stmt = $pdo->prepare("SELECT g.*, gt.content FROM graduations g JOIN graduations_translations gt ON g.id = gt.graduation_id WHERE g.id = ? AND gt.language_id = 1");
    $stmt->execute([$edit_id]);
    $res = $stmt->fetch();
    if ($res) {
        $edit_data = [
            'year' => $res['year'],
            'content_fr' => $res['content'],
            'image_url_a' => $res['image_url_a'],
            'image_url_b' => $res['image_url_b']
        ];
    }
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM graduations WHERE id = ?");
    $stmt->execute([$id]);
    $message = "<div class='alert alert-success'>Promotion supprimée.</div>";
}

// Handle Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $year = (int)$_POST['year'];
    $content_fr = $_POST['content_fr'] ?? '';
    $post_id = $_POST['edit_id'] ?? 0;

    $img_a = $_POST['existing_image_a'] ?? NULL;
    $img_b = $_POST['existing_image_b'] ?? NULL;
    $target_dir = "../components/images/others/";

    if (!empty($_FILES['image_a']['name'])) {
        $uploaded_a = processAndSaveImage($_FILES['image_a']['tmp_name'], $target_dir, 'grad_a');
        if ($uploaded_a) $img_a = "others/" . $uploaded_a;
    }
    if (!empty($_FILES['image_b']['name'])) {
        $uploaded_b = processAndSaveImage($_FILES['image_b']['tmp_name'], $target_dir, 'grad_b');
        if ($uploaded_b) $img_b = "others/" . $uploaded_b;
    }

    try {
        $pdo->beginTransaction();

        if ($post_id > 0) {
            $stmt = $pdo->prepare("UPDATE graduations SET year = ?, image_url_a = ?, image_url_b = ? WHERE id = ?");
            $stmt->execute([$year, $img_a, $img_b, $post_id]);
            
            $content_en = autoTranslate($content_fr, 'fr', 'en');
            $content_ar = autoTranslate($content_fr, 'fr', 'ar');

            $stmt_up_t = $pdo->prepare("UPDATE graduations_translations SET content = ? WHERE graduation_id = ? AND language_id = 1");
            $stmt_up_t->execute([$content_fr, $post_id]);
            $stmt_up_t2 = $pdo->prepare("UPDATE graduations_translations SET content = ? WHERE graduation_id = ? AND language_id = 2");
            $stmt_up_t2->execute([$content_en, $post_id]);
            $stmt_up_t3 = $pdo->prepare("UPDATE graduations_translations SET content = ? WHERE graduation_id = ? AND language_id = 3");
            $stmt_up_t3->execute([$content_ar, $post_id]);

            $message = "<div class='alert alert-success'>Promotion mise à jour.</div>";
        } else {
            $stmt = $pdo->prepare("INSERT INTO graduations (year, image_url_a, image_url_b) VALUES (?, ?, ?)");
            $stmt->execute([$year, $img_a, $img_b]);
            $grad_id = $pdo->lastInsertId();

            $content_en = autoTranslate($content_fr, 'fr', 'en');
            $content_ar = autoTranslate($content_fr, 'fr', 'ar');

            $stmt_t = $pdo->prepare("INSERT INTO graduations_translations (graduation_id, language_id, content) VALUES (?, ?, ?)");
            $stmt_t->execute([$grad_id, 1, $content_fr]);
            $stmt_t->execute([$grad_id, 2, $content_en]);
            $stmt_t->execute([$grad_id, 3, $content_ar]);

            $message = "<div class='alert alert-success'>Promotion ajoutée.</div>";
        }

        $pdo->commit();
        if ($post_id > 0) { header('Location: manage_graduations.php?msg=updated'); exit; }
    } catch (Exception $e) {
        $pdo->rollBack();
        $message = "<div class='alert alert-error'>Erreur : " . $e->getMessage() . "</div>";
    }
}

if (isset($_GET['msg']) && $_GET['msg'] == 'updated') $message = "<div class='alert alert-success'>Modifications enregistrées.</div>";

$stmt = $pdo->query("SELECT * FROM graduations ORDER BY year DESC");
$grads = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gérer les Diplômes - Admin INSEA</title>
    <link rel="stylesheet" href="../components/CSS/style.css">
    <style>
        body { background: #f0f2f5; padding: 40px; }
        .admin-box { max-width: 900px; margin: 0 auto; }
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
            <h1>Gestion des Remises de Diplômes</h1>
            <div class="line" style="margin: 12px 0;"></div>
        </header>

        <?php echo $message; ?>

        <div class="form-card" style="margin-bottom: 40px;">
            <h2 style="margin-bottom: 25px;"><?php echo $edit_mode ? 'Modifier la Promotion' : 'Ajouter une Promotion'; ?></h2>
            <form action="" method="POST" enctype="multipart/form-data" class="form-grid">
                <input type="hidden" name="edit_id" value="<?php echo $edit_id; ?>">
                <input type="hidden" name="existing_image_a" value="<?php echo $edit_data['image_url_a']; ?>">
                <input type="hidden" name="existing_image_b" value="<?php echo $edit_data['image_url_b']; ?>">
                
                <div class="form-row-2">
                    <div>
                        <label>Année</label>
                        <input type="number" name="year" required value="<?php echo htmlspecialchars($edit_data['year']); ?>">
                    </div>
                    <div>
                        <label>Description (Français)</label>
                        <textarea name="content_fr" rows="3" required><?php echo htmlspecialchars($edit_data['content_fr']); ?></textarea>
                    </div>
                </div>
                <div class="form-row-2">
                    <div>
                        <label>Photo A</label>
                        <input type="file" name="image_a" accept="image/*">
                    </div>
                    <div>
                        <label>Photo B</label>
                        <input type="file" name="image_b" accept="image/*">
                    </div>
                </div>
                <button type="submit" class="btn-form-submit"><?php echo $edit_mode ? 'Enregistrer' : 'Publier'; ?> (Traduction Auto)</button>
                <?php if ($edit_mode): ?>
                    <a href="manage_graduations.php" style="display: block; text-align: center; margin-top: 10px; color: var(--gray-500);">Annuler</a>
                <?php endif; ?>
            </form>
        </div>

        <h2>Historique des Promotions</h2>
        <?php foreach ($grads as $g): ?>
            <div class="list-item">
                <strong>Promotion <?php echo $g['year']; ?></strong>
                <div>
                    <a href="?edit=<?php echo $g['id']; ?>" class="btn-edit">Modifier</a>
                    <a href="?delete=<?php echo $g['id']; ?>" class="btn-delete" onclick="return confirm('Supprimer ?')">Supprimer</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

</body>
</html>
