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
    $pdo->prepare("DELETE FROM gallery WHERE id = ?")->execute([(int)$_GET['delete']]);
    $message = "<div class='alert alert-success'>Image supprimée.</div>";
}

if (isset($_POST['bulk_delete']) && !empty($_POST['selected_ids'])) {
    $ids = $_POST['selected_ids'];
    $placeholders = str_repeat('?,', count($ids) - 1) . '?';
    $stmt = $pdo->prepare("DELETE FROM gallery WHERE id IN ($placeholders)");
    $stmt->execute($ids);
    $message = "<div class='alert alert-success'>Éléments supprimés.</div>";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['bulk_delete'])) {
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
            header('Location: manage_gallery.php?msg=updated'); exit;
        } catch (Exception $e) { $pdo->rollBack(); $message = "<div class='alert alert-error'>Erreur: ".$e->getMessage()."</div>"; }
    }
}

if (isset($_GET['msg']) && $_GET['msg'] == 'updated') $message = "<div class='alert alert-success'>Modifications enregistrées.</div>";
$gallery_items = $pdo->query("SELECT g.id, g.image_url, g.category, gt.title FROM gallery g JOIN gallery_translations gt ON g.id = gt.gallery_id WHERE gt.language_id = 1 ORDER BY g.created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"><title>Galerie - INSEA Admin</title><link rel="stylesheet" href="admin_style.css">
    <script>
        function toggleSelectAll(source) {
            checkboxes = document.getElementsByName('selected_ids[]');
            for(var i=0, n=checkboxes.length;i<n;i++) checkboxes[i].checked = source.checked;
            updateDeleteButton();
        }
        function updateDeleteButton() {
            const anyChecked = document.querySelectorAll('input[name="selected_ids[]"]:checked').length > 0;
            document.getElementById('btn-bulk-delete').disabled = !anyChecked;
        }
        function previewImage(input) {
            const preview = document.getElementById('image-preview');
            const container = document.getElementById('preview-container');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    container.classList.add('has-image');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
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
                <header class="page-header"><div class="page-title"><h1>Galerie Photos</h1><p>Gérer les visuels du campus.</p></div><div class="user-profile"><a href="logout.php" onclick="confirmLogout('logout.php'); return false;" class="logout-link">Déconnexion</a></div></header>
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
                            <div class="form-group">
                                <label>Image</label>
                                <input type="file" name="image" class="form-control" accept="image/*" onchange="previewImage(this)" <?php echo $edit_mode?'':'required'; ?>>
                                <div id="preview-container" class="image-preview-container <?php echo $edit_data['image_url'] ? 'has-image' : ''; ?>">
                                    <img id="image-preview" src="<?php echo $edit_data['image_url'] ? '../components/images/'.$edit_data['image_url'] : ''; ?>" alt="Preview">
                                    <div class="preview-placeholder">Aperçu</div>
                                </div>
                            </div>
                            <div class="form-group"><label>Lien (Optionnel)</label><input type="text" name="link_url" class="form-control" value="<?php echo htmlspecialchars($edit_data['link_url']); ?>"></div>
                        </div>
                        <button type="submit" class="btn-primary"><?php echo $edit_mode?'Enregistrer':'Uploader'; ?></button>
                        <?php if($edit_mode): ?><a href="manage_gallery.php" class="btn-cancel">Annuler</a><?php endif; ?>
                    </form>
                </div>
                <h2 class="section-label">Photos actuelles</h2>
                <form action="" method="POST" onsubmit="return confirm('Supprimer les éléments sélectionnés ?')">
                    <div class="bulk-actions" style="margin-bottom: 25px;">
                        <div style="display:flex; align-items:center; gap:12px;">
                            <input type="checkbox" class="checkbox-custom" onclick="toggleSelectAll(this)" id="sel-all">
                            <label for="sel-all" style="font-weight:700; font-size:0.85rem; cursor:pointer;">Tout sélectionner</label>
                        </div>
                        <button type="submit" name="bulk_delete" id="btn-bulk-delete" class="btn-delete-selected" disabled style="margin-left:auto;"><?php echo icon_trash(); ?> Supprimer la sélection</button>
                    </div>
                    <div class="admin-gallery-grid">
                        <?php foreach ($gallery_items as $item): ?>
                        <div class="gallery-item-card" style="position:relative;">
                            <input type="checkbox" name="selected_ids[]" value="<?php echo $item['id']; ?>" class="checkbox-custom" onclick="updateDeleteButton()" style="position:absolute; top:10px; left:10px; z-index:10; border: 2px solid white; box-shadow: 0 0 5px rgba(0,0,0,0.5);">
                            <img src="../components/images/<?php echo $item['image_url']; ?>" alt="">
                            <div class="gallery-item-info">
                                <span><?php echo $item['category']; ?></span>
                                <strong><?php echo htmlspecialchars($item['title']); ?></strong>
                                <div class="action-btns" style="margin-top: 12px; display: flex;">
                                    <a href="?edit=<?php echo $item['id']; ?>" class="link-edit"><?php echo icon_pen(); ?></a>
                                    <a href="?delete=<?php echo $item['id']; ?>" class="link-delete" onclick="return confirm('Supprimer ?')"><?php echo icon_trash(); ?></a>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
