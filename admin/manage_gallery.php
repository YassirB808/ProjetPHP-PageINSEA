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
    'category' => 'evenements',
    'link_url' => '',
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
    $stmt = $pdo->prepare("SELECT g.*, gt.title FROM gallery g JOIN gallery_translations gt ON g.id = gt.gallery_id WHERE g.id = ? AND gt.language_id = 1");
    $stmt->execute([$edit_id]);
    $res = $stmt->fetch();
    if ($res) {
        $edit_data = [
            'title_fr' => $res['title'],
            'category' => $res['category'],
            'link_url' => $res['link_url'],
            'image_url' => $res['image_url']
        ];
    }
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM gallery WHERE id = ?");
    $stmt->execute([$id]);
    $message = "<div class='alert alert-success'>Image supprimée.</div>";
}

// Handle Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title_fr = $_POST['title_fr'] ?? '';
    $category = $_POST['category'] ?? 'evenements';
    $link_url = $_POST['link_url'] ?? NULL;
    $post_id = $_POST['edit_id'] ?? 0;

    $db_image_path = $_POST['existing_image'] ?? NULL;
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "../components/images/others/";
        $uploaded_file = processAndSaveImage($_FILES['image']['tmp_name'], $target_dir, 'gal');
        if ($uploaded_file) {
            $db_image_path = "others/" . $uploaded_file;
        }
    }

    if (!empty($db_image_path)) {
        try {
            $pdo->beginTransaction();

            if ($post_id > 0) {
                $stmt = $pdo->prepare("UPDATE gallery SET image_url = ?, category = ?, link_url = ? WHERE id = ?");
                $stmt->execute([$db_image_path, $category, $link_url, $post_id]);
                
                $title_en = autoTranslate($title_fr, 'fr', 'en');
                $title_ar = autoTranslate($title_fr, 'fr', 'ar');

                $stmt_up_t = $pdo->prepare("UPDATE gallery_translations SET title = ? WHERE gallery_id = ? AND language_id = 1");
                $stmt_up_t->execute([$title_fr, $post_id]);
                $stmt_up_t2 = $pdo->prepare("UPDATE gallery_translations SET title = ? WHERE gallery_id = ? AND language_id = 2");
                $stmt_up_t2->execute([$title_en, $post_id]);
                $stmt_up_t3 = $pdo->prepare("UPDATE gallery_translations SET title = ? WHERE gallery_id = ? AND language_id = 3");
                $stmt_up_t3->execute([$title_ar, $post_id]);

                $message = "<div class='alert alert-success'>Image mise à jour.</div>";
            } else {
                $stmt = $pdo->prepare("INSERT INTO gallery (image_url, category, link_url) VALUES (?, ?, ?)");
                $stmt->execute([$db_image_path, $category, $link_url]);
                $gal_id = $pdo->lastInsertId();

                $title_en = autoTranslate($title_fr, 'fr', 'en');
                $title_ar = autoTranslate($title_fr, 'fr', 'ar');

                $stmt_t = $pdo->prepare("INSERT INTO gallery_translations (gallery_id, language_id, title) VALUES (?, ?, ?)");
                $stmt_t->execute([$gal_id, 1, $title_fr]);
                $stmt_t->execute([$gal_id, 2, $title_en]);
                $stmt_t->execute([$gal_id, 3, $title_ar]);

                $message = "<div class='alert alert-success'>Image ajoutée.</div>";
            }

            $pdo->commit();
            if ($post_id > 0) { header('Location: manage_gallery.php?msg=updated'); exit; }
        } catch (Exception $e) {
            $pdo->rollBack();
            $message = "<div class='alert alert-error'>Erreur : " . $e->getMessage() . "</div>";
        }
    }
}

if (isset($_GET['msg']) && $_GET['msg'] == 'updated') $message = "<div class='alert alert-success'>Modifications enregistrées.</div>";

$stmt = $pdo->query("SELECT g.id, g.image_url, g.category, gt.title FROM gallery g JOIN gallery_translations gt ON g.id = gt.gallery_id WHERE gt.language_id = 1 ORDER BY g.created_at DESC");
$gallery_items = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gérer la Galerie - Admin INSEA</title>
    <link rel="stylesheet" href="../components/CSS/style.css">
    <style>
        body { background: #f0f2f5; padding: 40px; }
        .admin-box { max-width: 1100px; margin: 0 auto; }
        .back-link { display: inline-block; margin-bottom: 20px; color: var(--insea-green); font-weight: 700; text-decoration: none; }
        .gallery-admin-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 25px; }
        .gallery-admin-item { background: white; border-radius: 12px; overflow: hidden; box-shadow: var(--shadow); border: 1px solid var(--gray-200); position: relative; }
        .gallery-admin-item img { width: 100%; height: 160px; object-fit: cover; }
        .gallery-admin-info { padding: 15px; font-size: 0.9rem; }
        .action-overlay { position: absolute; top: 8px; right: 8px; display: flex; gap: 5px; }
        .btn-act { padding: 4px 8px; border-radius: 4px; font-size: 0.7rem; font-weight: bold; text-decoration: none; color: white; }
        .btn-del { background: rgba(220, 53, 69, 0.9); }
        .btn-ed { background: rgba(42, 128, 73, 0.9); }
    </style>
</head>
<body>

    <div class="admin-box">
        <a href="index.php" class="back-link">← Retour au Dashboard</a>
        
        <header class="section-header" style="margin-top: 0; text-align: left;">
            <h1>Gestion de la Galerie</h1>
            <div class="line" style="margin: 12px 0;"></div>
        </header>

        <?php echo $message; ?>

        <div class="form-card" style="margin-bottom: 40px;">
            <h2 style="margin-bottom: 25px;"><?php echo $edit_mode ? 'Modifier l\'Image' : 'Ajouter une Photo'; ?></h2>
            <form action="" method="POST" enctype="multipart/form-data" class="form-grid">
                <input type="hidden" name="edit_id" value="<?php echo $edit_id; ?>">
                <input type="hidden" name="existing_image" value="<?php echo $edit_data['image_url']; ?>">
                
                <div class="form-row-2">
                    <div>
                        <label>Titre / Légende (Français)</label>
                        <input type="text" name="title_fr" required value="<?php echo htmlspecialchars($edit_data['title_fr']); ?>">
                    </div>
                    <div>
                        <label>Catégorie</label>
                        <select name="category" required>
                            <option value="evenements" <?php echo $edit_data['category'] == 'evenements' ? 'selected' : ''; ?>>Événements</option>
                            <option value="etudiants" <?php echo $edit_data['category'] == 'etudiants' ? 'selected' : ''; ?>>Étudiants</option>
                            <option value="partenariats" <?php echo $edit_data['category'] == 'partenariats' ? 'selected' : ''; ?>>Partenariats</option>
                            <option value="recherche" <?php echo $edit_data['category'] == 'recherche' ? 'selected' : ''; ?>>Recherche</option>
                        </select>
                    </div>
                </div>
                <div class="form-row-2">
                    <div>
                        <label>Image (Fichier)</label>
                        <input type="file" name="image" accept="image/*" <?php echo $edit_mode ? '' : 'required'; ?>>
                    </div>
                    <div>
                        <label>Lien URL (Optionnel)</label>
                        <input type="text" name="link_url" value="<?php echo htmlspecialchars($edit_data['link_url']); ?>">
                    </div>
                </div>
                <button type="submit" class="btn-form-submit"><?php echo $edit_mode ? 'Enregistrer' : 'Uploader'; ?> (Traduction Auto)</button>
                <?php if ($edit_mode): ?>
                    <a href="manage_gallery.php" style="display: block; text-align: center; margin-top: 10px; color: var(--gray-500);">Annuler la modification</a>
                <?php endif; ?>
            </form>
        </div>

        <h2 style="margin-bottom: 20px;">Photos actuelles</h2>
        <div class="gallery-admin-grid">
            <?php foreach ($gallery_items as $item): ?>
            <div class="gallery-admin-item">
                <div class="action-overlay">
                    <a href="?edit=<?php echo $item['id']; ?>" class="btn-act btn-ed">Edit</a>
                    <a href="?delete=<?php echo $item['id']; ?>" class="btn-act btn-del" onclick="return confirm('Supprimer ?')">Del</a>
                </div>
                <img src="../components/images/<?php echo $item['image_url']; ?>" alt="">
                <div class="gallery-admin-info">
                    <strong><?php echo htmlspecialchars($item['title']); ?></strong><br>
                    <span style="color: var(--insea-green); font-size: 0.7rem; text-transform: uppercase;"><?php echo $item['category']; ?></span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

</body>
</html>
