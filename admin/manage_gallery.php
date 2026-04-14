<?php
require_once 'auth_check.php';
require_once '../components/PHP/db_connect.php';
require_once '../components/PHP/lang_handler.php';
require_once 'image_processor.php';
require_once 'translator.php';
require_once 'icons.php';

$message = '';
$edit_mode = false;
$edit_id = 0;
$edit_data = ['title_fr' => '', 'category' => 'evenements', 'link_url' => '', 'image_url' => ''];

if (isset($_GET['edit'])) {
    $edit_mode = true;
    $edit_id = (int)$_GET['edit'];
    $stmt = $pdo->prepare("SELECT g.*, gt.title FROM gallery g JOIN gallery_translations gt ON g.id = gt.gallery_id WHERE g.id = ? AND gt.language_id = 1");
    $stmt->execute([$edit_id]);
    $res = $stmt->fetch();
    if ($res) $edit_data = ['title_fr' => $res['title'], 'category' => $res['category'], 'link_url' => $res['link_url'], 'image_url' => $res['image_url']];
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $pdo->prepare("DELETE FROM gallery WHERE id = ?")->execute([$id]);
    $message = "<div class='alert alert-success'>Image supprimée.</div>";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title_fr = $_POST['title_fr'] ?? '';
    $category = $_POST['category'] ?? 'evenements';
    $link_url = $_POST['link_url'] ?? NULL;
    $post_id = $_POST['edit_id'] ?? 0;
    $db_image_path = $_POST['existing_image'] ?? NULL;

    if (!empty($_FILES['image']['name'])) {
        $uploaded_file = processAndSaveImage($_FILES['image']['tmp_name'], "../components/images/others/", 'gal');
        if ($uploaded_file) $db_image_path = "others/" . $uploaded_file;
    }

    if (!empty($db_image_path)) {
        try {
            $pdo->beginTransaction();
            if ($post_id > 0) {
                $pdo->prepare("UPDATE gallery SET image_url = ?, category = ?, link_url = ? WHERE id = ?")->execute([$db_image_path, $category, $link_url, $post_id]);
                $title_en = autoTranslate($title_fr, 'fr', 'en');
                $title_ar = autoTranslate($title_fr, 'fr', 'ar');
                $pdo->prepare("UPDATE gallery_translations SET title = ? WHERE gallery_id = ? AND language_id = 1")->execute([$title_fr, $post_id]);
                $pdo->prepare("UPDATE gallery_translations SET title = ? WHERE gallery_id = ? AND language_id = 2")->execute([$title_en, $post_id]);
                $pdo->prepare("UPDATE gallery_translations SET title = ? WHERE gallery_id = ? AND language_id = 3")->execute([$title_ar, $post_id]);
            } else {
                $pdo->prepare("INSERT INTO gallery (image_url, category, link_url) VALUES (?, ?, ?)")->execute([$db_image_path, $category, $link_url]);
                $gal_id = $pdo->lastInsertId();
                $pdo->prepare("INSERT INTO gallery_translations (gallery_id, language_id, title) VALUES (?, 1, ?), (?, 2, ?), (?, 3, ?)")->execute([$gal_id, $title_fr, $gal_id, autoTranslate($title_fr, 'fr', 'en'), $gal_id, autoTranslate($title_fr, 'fr', 'ar')]);
            }
            $pdo->commit();
            if ($post_id > 0) { header('Location: manage_gallery.php?msg=updated'); exit; }
            $message = "<div class='alert alert-success'>Image ajoutée.</div>";
        } catch (Exception $e) { $pdo->rollBack(); $message = "<div class='alert alert-error'>Erreur: ".$e->getMessage()."</div>"; }
    }
}

if (isset($_GET['msg']) && $_GET['msg'] == 'updated') $message = "<div class='alert alert-success'>Image mise à jour.</div>";
$gallery_items = $pdo->query("SELECT g.id, g.image_url, g.category, gt.title FROM gallery g JOIN gallery_translations gt ON g.id = gt.gallery_id WHERE gt.language_id = 1 ORDER BY g.created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Galerie - INSEA Admin</title>
    <link rel="stylesheet" href="admin_style.css">
</head>
<body>
    <div class="admin-layout">
        <aside class="sidebar">
            <div class="sidebar-brand"><img src="../components/images/logos/insea_logo.png" alt=""><span>INSEA ADMIN</span></div>
            <nav class="sidebar-nav">
                <a href="index.php" class="nav-link">Dashboard</a>
                <a href="manage_news.php" class="nav-link">Actualités</a>
                <a href="manage_gallery.php" class="nav-link active">Galerie Photos</a>
                <a href="manage_partners.php" class="nav-link">Partenariats</a>
                <a href="manage_jobs.php" class="nav-link">Offres d'Emploi</a>
                <a href="manage_graduations.php" class="nav-link">Diplômes</a>
                <a href="manage_labs.php" class="nav-link">Laboratoires</a>
                <a href="manage_calendar.php" class="nav-link">Calendrier</a>
                <a href="manage_student_life.php" class="nav-link">Vie Étudiante</a>
            </nav>
        </aside>
        <main class="main-content">
            <div class="content-wrapper">
                <header class="page-header">
                    <div class="page-title"><h1>Galerie Photos</h1><p>Gérer les visuels du campus.</p></div>
                    <div class="user-profile"><a href="logout.php" onclick="confirmLogout('logout.php'); return false;" class="logout-link">Déconnexion</a></div>
                    </header>
                    <?php include 'modals.php'; ?>

                <?php echo $message; ?>
                <div class="card">
                    <h2 class="card-title"><?php echo $edit_mode ? 'Modifier' : 'Ajouter'; ?> une photo</h2>
                    <form action="" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="edit_id" value="<?php echo $edit_id; ?>">
                        <input type="hidden" name="existing_image" value="<?php echo $edit_data['image_url']; ?>">
                        <div class="form-row">
                            <div class="form-group"><label>Titre / Légende</label><input type="text" name="title_fr" class="form-control" required value="<?php echo htmlspecialchars($edit_data['title_fr']); ?>"></div>
                            <div class="form-group"><label>Catégorie</label><select name="category" class="form-control"><option value="evenements" <?php echo $edit_data['category']=='evenements'?'selected':''; ?>>Événements</option><option value="etudiants" <?php echo $edit_data['category']=='etudiants'?'selected':''; ?>>Étudiants</option><option value="partenariats" <?php echo $edit_data['category']=='partenariats'?'selected':''; ?>>Partenariats</option><option value="recherche" <?php echo $edit_data['category']=='recherche'?'selected':''; ?>>Recherche</option></select></div>
                        </div>
                        <div class="form-row">
                            <div class="form-group"><label>Image</label><input type="file" name="image" class="form-control" accept="image/*" <?php echo $edit_mode?'':'required'; ?>></div>
                            <div class="form-group"><label>Lien (Optionnel)</label><input type="text" name="link_url" class="form-control" value="<?php echo htmlspecialchars($edit_data['link_url']); ?>"></div>
                        </div>
                        <button type="submit" class="btn-primary"><?php echo $edit_mode?'Enregistrer':'Uploader'; ?></button>
                        <?php if($edit_mode): ?><a href="manage_gallery.php" class="btn-cancel">Annuler</a><?php endif; ?>
                    </form>
                </div>
                <h2 class="section-label">Photos actuelles</h2>
                <div class="admin-gallery-grid">
                    <?php foreach ($gallery_items as $item): ?>
                    <div class="gallery-item-card">
                        <img src="../components/images/<?php echo $item['image_url']; ?>" alt="">
                        <div class="gallery-item-info">
                            <span><?php echo $item['category']; ?></span>
                            <strong><?php echo htmlspecialchars($item['title']); ?></strong>
                            <div class="action-btns" style="margin-top: 12px;">
                                <a href="?edit=<?php echo $item['id']; ?>" class="link-edit" title="Modifier"><?php echo icon_pen(); ?></a>
                                <a href="?delete=<?php echo $item['id']; ?>" class="link-delete" title="Supprimer"><?php echo icon_trash(); ?></a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
