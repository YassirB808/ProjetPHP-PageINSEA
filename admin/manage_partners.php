<?php
require_once 'auth_check.php';
require_once '../components/PHP/db_connect.php';
require_once '../components/PHP/lang_handler.php';
require_once 'image_processor.php';
require_once 'icons.php';

$message = '';
$edit_mode = false;
$edit_id = 0;
$edit_data = ['name' => '', 'type' => 'national', 'logo_url' => ''];

if (isset($_GET['edit'])) {
    $edit_mode = true;
    $edit_id = (int)$_GET['edit'];
    $stmt = $pdo->prepare("SELECT * FROM partners WHERE id = ?");
    $stmt->execute([$edit_id]);
    $res = $stmt->fetch();
    if ($res) $edit_data = ['name' => $res['name'], 'type' => $res['type'], 'logo_url' => $res['logo_url']];
}

if (isset($_GET['delete'])) {
    $pdo->prepare("DELETE FROM partners WHERE id = ?")->execute([(int)$_GET['delete']]);
    $message = "<div class='alert alert-success'>Partenaire supprimé.</div>";
}

if (isset($_POST['bulk_delete']) && !empty($_POST['selected_ids'])) {
    $ids = $_POST['selected_ids'];
    $placeholders = str_repeat('?,', count($ids) - 1) . '?';
    $stmt = $pdo->prepare("DELETE FROM partners WHERE id IN ($placeholders)");
    $stmt->execute($ids);
    $message = "<div class='alert alert-success'>Éléments supprimés.</div>";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['bulk_delete'])) {
    $name = $_POST['name'] ?? '';
    $type = $_POST['type'] ?? 'national';
    $post_id = $_POST['edit_id'] ?? 0;
    $db_logo_path = $_POST['existing_logo'] ?? '';

    if (!empty($_FILES['logo']['name'])) {
        $uploaded_file = processAndSaveImage($_FILES['logo']['tmp_name'], "../components/images/partenariats/", 'partner', 400);
        if ($uploaded_file) $db_logo_path = "partenariats/" . $uploaded_file;
    }

    if (!empty($db_logo_path)) {
        try {
            if ($post_id > 0) {
                $pdo->prepare("UPDATE partners SET name = ?, logo_url = ?, type = ? WHERE id = ?")->execute([$name, $db_logo_path, $type, $post_id]);
                header('Location: manage_partners.php?msg=updated'); exit;
            } else {
                $pdo->prepare("INSERT INTO partners (name, logo_url, type) VALUES (?, ?, ?)")->execute([$name, $db_logo_path, $type]);
                $message = "<div class='alert alert-success'>Partenaire ajouté.</div>";
            }
        } catch (Exception $e) { $message = "<div class='alert alert-error'>Erreur: ".$e->getMessage()."</div>"; }
    }
}

if (isset($_GET['msg']) && $_GET['msg'] == 'updated') $message = "<div class='alert alert-success'>Modifications enregistrées.</div>";
$partners = $pdo->query("SELECT * FROM partners ORDER BY name ASC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"><title>Partenariats - Admin</title><link rel="stylesheet" href="admin_style.css">
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
                <a href="manage_gallery.php" class="nav-link">Galerie Photos</a>
                <a href="manage_partners.php" class="nav-link active">Partenariats</a>
                <a href="manage_jobs.php" class="nav-link">Offres d'Emploi</a>
                <a href="manage_graduations.php" class="nav-link">Diplômes</a>
                <a href="manage_labs.php" class="nav-link">Laboratoires</a>
                <a href="manage_calendar.php" class="nav-link">Calendrier</a>
                <a href="manage_student_life.php" class="nav-link">Vie Étudiante</a>
            </nav>
        </aside>
        <main class="main-content">
            <div class="content-wrapper">
                <header class="page-header"><div class="page-title"><h1>Partenariats</h1><p>Gérer les partenaires.</p></div><div class="user-profile"><a href="logout.php" onclick="confirmLogout('logout.php'); return false;" class="logout-link">Déconnexion</a></div></header>
                <?php echo $message; ?>
                <div class="card">
                    <h2 class="card-title"><?php echo $edit_mode ? 'Modifier' : 'Ajouter'; ?> un partenaire</h2>
                    <form action="" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="edit_id" value="<?php echo $edit_id; ?>">
                        <input type="hidden" name="existing_logo" value="<?php echo $edit_data['logo_url']; ?>">
                        <div class="form-row">
                            <div class="form-group"><label>Nom</label><input type="text" name="name" class="form-control" required value="<?php echo htmlspecialchars($edit_data['name']); ?>"></div>
                            <div class="form-group"><label>Type</label><select name="type" class="form-control"><option value="national" <?php echo $edit_data['type']=='national'?'selected':''; ?>>National</option><option value="international" <?php echo $edit_data['type']=='international'?'selected':''; ?>>International</option></select></div>
                        </div>
                        <div class="form-group">
                            <label>Logo</label>
                            <input type="file" name="logo" class="form-control" accept="image/*" onchange="previewImage(this)" <?php echo $edit_mode?'':'required'; ?>>
                            <div id="preview-container" class="image-preview-container <?php echo $edit_data['logo_url'] ? 'has-image' : ''; ?>">
                                <img id="image-preview" src="<?php echo $edit_data['logo_url'] ? '../components/images/'.$edit_data['logo_url'] : ''; ?>" alt="Preview">
                                <div class="preview-placeholder">Aperçu</div>
                            </div>
                        </div>
                        <button type="submit" class="btn-primary"><?php echo $edit_mode?'Enregistrer':'Ajouter'; ?></button>
                        <?php if($edit_mode): ?><a href="manage_partners.php" class="btn-cancel">Annuler</a><?php endif; ?>
                    </form>
                </div>
                <h2 class="section-label">Partenaires actuels</h2>
                <form action="" method="POST" onsubmit="return confirm('Supprimer les éléments sélectionnés ?')">
                    <div class="bulk-actions" style="margin-bottom: 25px;">
                        <div style="display:flex; align-items:center; gap:12px;">
                            <input type="checkbox" class="checkbox-custom" onclick="toggleSelectAll(this)" id="sel-all">
                            <label for="sel-all" style="font-weight:700; font-size:0.85rem; cursor:pointer;">Tout sélectionner</label>
                        </div>
                        <button type="submit" name="bulk_delete" id="btn-bulk-delete" class="btn-delete-selected" disabled style="margin-left:auto;"><?php echo icon_trash(); ?> Supprimer la sélection</button>
                    </div>
                    <div class="admin-gallery-grid">
                        <?php foreach ($partners as $p): ?>
                        <div class="gallery-item-card" style="text-align:center; padding: 20px; position:relative;">
                            <input type="checkbox" name="selected_ids[]" value="<?php echo $p['id']; ?>" class="checkbox-custom" onclick="updateDeleteButton()" style="position:absolute; top:10px; left:10px; z-index:10;">
                            <img src="../components/images/<?php echo $p['logo_url']; ?>" alt="" style="height: 50px; object-fit: contain; width: 100%; border-bottom:none;">
                            <div class="gallery-item-info">
                                <strong><?php echo htmlspecialchars($p['name']); ?></strong>
                                <div class="action-btns" style="justify-content:center; margin-top:10px; display:flex;">
                                    <a href="?edit=<?php echo $p['id']; ?>" class="link-edit"><?php echo icon_pen(); ?></a>
                                    <a href="?delete=<?php echo $p['id']; ?>" class="link-delete" onclick="return confirm('Supprimer ?')"><?php echo icon_trash(); ?></a>
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
