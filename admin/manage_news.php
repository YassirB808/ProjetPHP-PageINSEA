<?php
require_once 'auth_check.php';
require_once '../components/PHP/db_connect.php';
require_once '../components/PHP/lang_handler.php';
require_once 'image_processor.php';
require_once 'translator.php';

$message = '';
$edit_mode = false;
$edit_id = 0;
$edit_data = [
    'title_fr' => '',
    'content_fr' => '',
    'link_url' => '',
    'image_url' => '',
    'is_featured' => 0
];

// Handle Edit Mode Fetch
if (isset($_GET['edit'])) {
    $edit_mode = true;
    $edit_id = (int)$_GET['edit'];
    $stmt = $pdo->prepare("SELECT n.*, nt.title, nt.content FROM news n JOIN news_translations nt ON n.id = nt.news_id WHERE n.id = ? AND nt.language_id = 1");
    $stmt->execute([$edit_id]);
    $res = $stmt->fetch();
    if ($res) {
        $edit_data = [
            'title_fr' => $res['title'],
            'content_fr' => $res['content'],
            'link_url' => $res['link_url'],
            'image_url' => $res['image_url'],
            'is_featured' => $res['is_featured']
        ];
    }
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM news WHERE id = ?");
    if ($stmt->execute([$id])) {
        $message = "<div class='alert alert-success'>Article supprimé.</div>";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title_fr = $_POST['title_fr'] ?? '';
    $content_fr = $_POST['content_fr'] ?? '';
    $link_url = $_POST['link_url'] ?? NULL;
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $post_id = $_POST['edit_id'] ?? 0;
    
    // Handle Image Upload
    $db_image_path = $_POST['existing_image'] ?? NULL;
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "../components/images/others/";
        $uploaded_file = processAndSaveImage($_FILES['image']['tmp_name'], $target_dir, 'news');
        if ($uploaded_file) {
            $db_image_path = "others/" . $uploaded_file;
        }
    }

    if (!empty($title_fr) && !empty($content_fr)) {
        try {
            $pdo->beginTransaction();

            // Translations using central utility
            $title_en = autoTranslate($title_fr, 'fr', 'en');
            $content_en = autoTranslate($content_fr, 'fr', 'en');
            $title_ar = autoTranslate($title_fr, 'fr', 'ar');
            $content_ar = autoTranslate($content_fr, 'fr', 'ar');

            if ($post_id > 0) {
                $stmt = $pdo->prepare("UPDATE news SET image_url = ?, link_url = ?, is_featured = ? WHERE id = ?");
                $stmt->execute([$db_image_path, $link_url, $is_featured, $post_id]);
                
                $stmt_up_t = $pdo->prepare("UPDATE news_translations SET title = ?, content = ? WHERE news_id = ? AND language_id = ?");
                $stmt_up_t->execute([$title_fr, $content_fr, $post_id, 1]);
                $stmt_up_t->execute([$title_en, $content_en, $post_id, 2]);
                $stmt_up_t->execute([$title_ar, $content_ar, $post_id, 3]);

                $message = "<div class='alert alert-success'>Article mis à jour et traduit.</div>";
            } else {
                $stmt = $pdo->prepare("INSERT INTO news (publish_date, image_url, link_url, is_featured) VALUES (NOW(), ?, ?, ?)");
                $stmt->execute([$db_image_path, $link_url, $is_featured]);
                $news_id = $pdo->lastInsertId();

                $stmt_trans = $pdo->prepare("INSERT INTO news_translations (news_id, language_id, title, content) VALUES (?, ?, ?, ?)");
                $stmt_trans->execute([$news_id, 1, $title_fr, $content_fr]);
                $stmt_trans->execute([$news_id, 2, $title_en, $content_en]);
                $stmt_trans->execute([$news_id, 3, $title_ar, $content_ar]);

                $message = "<div class='alert alert-success'>Article publié et traduit.</div>";
            }

            $pdo->commit();
            if ($post_id > 0) { header('Location: manage_news.php?msg=updated'); exit; }
        } catch (Exception $e) {
            $pdo->rollBack();
            $message = "<div class='alert alert-error'>Erreur : " . $e->getMessage() . "</div>";
        }
    }
}

if (isset($_GET['msg']) && $_GET['msg'] == 'updated') $message = "<div class='alert alert-success'>Article mis à jour.</div>";

$stmt = $pdo->query("SELECT n.id, nt.title, n.publish_date FROM news n JOIN news_translations nt ON n.id = nt.news_id WHERE nt.language_id = 1 ORDER BY n.publish_date DESC");
$news_list = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gérer les Actualités - Admin INSEA</title>
    <link rel="stylesheet" href="../components/CSS/style.css">
    <style>
        body { background: #f0f2f5; padding: 40px; }
        .admin-box { max-width: 1000px; margin: 0 auto; }
        .back-link { display: inline-block; margin-bottom: 20px; color: var(--insea-green); font-weight: 700; text-decoration: none; }
        .news-item { background: white; padding: 20px; border-radius: 12px; margin-bottom: 15px; display: flex; justify-content: space-between; align-items: center; box-shadow: var(--shadow); border: 1px solid var(--gray-200); }
        .btn-delete { color: #dc3545; font-weight: bold; text-decoration: none; }
        .btn-edit { color: var(--insea-green); font-weight: bold; text-decoration: none; margin-right: 15px; }
    </style>
</head>
<body>

    <div class="admin-box">
        <a href="index.php" class="back-link">← Retour au Dashboard</a>
        
        <header class="section-header" style="margin-top: 0; text-align: left;">
            <h1>Gestion des Actualités</h1>
            <div class="line" style="margin: 12px 0;"></div>
        </header>

        <?php echo $message; ?>

        <div class="form-card" style="margin-bottom: 40px;">
            <h2 style="margin-bottom: 25px;"><?php echo $edit_mode ? 'Modifier l\'Article' : 'Ajouter une News'; ?></h2>
            <form action="" method="POST" enctype="multipart/form-data" class="form-grid">
                <input type="hidden" name="edit_id" value="<?php echo $edit_id; ?>">
                <input type="hidden" name="existing_image" value="<?php echo $edit_data['image_url']; ?>">
                
                <div>
                    <label>Titre (Français)</label>
                    <input type="text" name="title_fr" required value="<?php echo htmlspecialchars($edit_data['title_fr']); ?>">
                </div>
                <div>
                    <label>Lien Externe (Optionnel)</label>
                    <input type="text" name="link_url" placeholder="https://..." value="<?php echo htmlspecialchars($edit_data['link_url']); ?>">
                </div>
                <div>
                    <label>Contenu (Français)</label>
                    <textarea name="content_fr" rows="5" required><?php echo htmlspecialchars($edit_data['content_fr']); ?></textarea>
                </div>
                <div class="form-row-2">
                    <div>
                        <label>Image <?php echo $edit_data['image_url'] ? '(Laisser vide pour garder l\'actuelle)' : ''; ?></label>
                        <input type="file" name="image" accept="image/*">
                    </div>
                    <div style="display: flex; align-items: center; gap: 10px; padding-top: 25px;">
                        <input type="checkbox" name="is_featured" id="feat" style="width: auto;" <?php echo $edit_data['is_featured'] ? 'checked' : ''; ?>>
                        <label for="feat" style="margin: 0;">Mettre à la une</label>
                    </div>
                </div>
                <button type="submit" class="btn-form-submit"><?php echo $edit_mode ? 'Mettre à jour' : 'Publier'; ?> (Traduction Auto active)</button>
                <?php if ($edit_mode): ?>
                    <a href="manage_news.php" style="display: block; text-align: center; margin-top: 10px; color: var(--gray-500);">Annuler la modification</a>
                <?php endif; ?>
            </form>
        </div>

        <h2 style="margin-bottom: 20px;">Liste des Articles</h2>
        <?php foreach ($news_list as $n): ?>
            <div class="news-item">
                <div>
                    <span style="color: var(--gray-500); font-size: 0.8rem;"><?php echo $n['publish_date']; ?></span>
                    <h4 style="margin-top: 5px;"><?php echo htmlspecialchars($n['title']); ?></h4>
                </div>
                <div>
                    <a href="?edit=<?php echo $n['id']; ?>" class="btn-edit">Modifier</a>
                    <a href="?delete=<?php echo $n['id']; ?>" class="btn-delete" onclick="return confirm('Confirmer la suppression ?')">Supprimer</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

</body>
</html>
