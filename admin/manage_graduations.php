<?php
require_once 'auth_check.php';
require_once '../components/PHP/db_connect.php';
require_once '../components/PHP/lang_handler.php';
require_once 'image_processor.php';
require_once 'translator.php';

$message = '';
$edit_mode = false;
$edit_id = 0;
$edit_data = ['year' => '', 'content_fr' => '', 'image_url_a' => '', 'image_url_b' => ''];

if (isset($_GET['edit'])) {
    $edit_mode = true;
    $edit_id = (int)$_GET['edit'];
    $stmt = $pdo->prepare("SELECT g.*, gt.content FROM graduations g JOIN graduations_translations gt ON g.id = gt.graduation_id WHERE g.id = ? AND gt.language_id = 1");
    $stmt->execute([$edit_id]);
    $res = $stmt->fetch();
    if ($res) $edit_data = ['year' => $res['year'], 'content_fr' => $res['content'], 'image_url_a' => $res['image_url_a'], 'image_url_b' => $res['image_url_b']];
}

if (isset($_GET['delete'])) {
    $pdo->prepare("DELETE FROM graduations WHERE id = ?")->execute([(int)$_GET['delete']]);
    $message = "<div class='alert alert-success'>Promotion supprimée.</div>";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $year = (int)$_POST['year'];
    $content_fr = $_POST['content_fr'] ?? '';
    $post_id = $_POST['edit_id'] ?? 0;
    $img_a = $_POST['existing_image_a'] ?? NULL;
    $img_b = $_POST['existing_image_b'] ?? NULL;

    if (!empty($_FILES['image_a']['name'])) {
        $uploaded_a = processAndSaveImage($_FILES['image_a']['tmp_name'], "../components/images/others/", 'grad_a');
        if ($uploaded_a) $img_a = "others/" . $uploaded_a;
    }
    if (!empty($_FILES['image_b']['name'])) {
        $uploaded_b = processAndSaveImage($_FILES['image_b']['tmp_name'], "../components/images/others/", 'grad_b');
        if ($uploaded_b) $img_b = "others/" . $uploaded_b;
    }

    try {
        $pdo->beginTransaction();
        if ($post_id > 0) {
            $pdo->prepare("UPDATE graduations SET year = ?, image_url_a = ?, image_url_b = ? WHERE id = ?")->execute([$year, $img_a, $img_b, $post_id]);
            $pdo->prepare("UPDATE graduations_translations SET content = ? WHERE graduation_id = ? AND language_id = 1")->execute([$content_fr, $post_id]);
            $pdo->prepare("UPDATE graduations_translations SET content = ? WHERE graduation_id = ? AND language_id = 2")->execute([autoTranslate($content_fr, 'fr', 'en'), $post_id]);
            $pdo->prepare("UPDATE graduations_translations SET content = ? WHERE graduation_id = ? AND language_id = 3")->execute([autoTranslate($content_fr, 'fr', 'ar'), $post_id]);
        } else {
            $pdo->prepare("INSERT INTO graduations (year, image_url_a, image_url_b) VALUES (?, ?, ?)")->execute([$year, $img_a, $img_b]);
            $grad_id = $pdo->lastInsertId();
            $pdo->prepare("INSERT INTO graduations_translations (graduation_id, language_id, content) VALUES (?, 1, ?), (?, 2, ?), (?, 3, ?)")->execute([$grad_id, $content_fr, $grad_id, autoTranslate($content_fr, 'fr', 'en'), $grad_id, autoTranslate($content_fr, 'fr', 'ar')]);
        }
        $pdo->commit();
        if ($post_id > 0) { header('Location: manage_graduations.php?msg=updated'); exit; }
        $message = "<div class='alert alert-success'>Promotion ajoutée.</div>";
    } catch (Exception $e) { $pdo->rollBack(); $message = "<div class='alert alert-error'>Erreur: ".$e->getMessage()."</div>"; }
}

if (isset($_GET['msg']) && $_GET['msg'] == 'updated') $message = "<div class='alert alert-success'>Modifications enregistrées.</div>";
$grads = $pdo->query("SELECT * FROM graduations ORDER BY year DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"><title>Diplômes - INSEA Admin</title><link rel="stylesheet" href="admin_style.css"></head>
<body>
    <div class="admin-layout">
        <aside class="sidebar">
            <div class="sidebar-brand"><img src="../components/images/logos/insea_logo.png" alt=""><span>INSEA ADMIN</span></div>
            <nav class="sidebar-nav">
                <a href="index.php" class="nav-link">Dashboard</a>
                <a href="manage_news.php" class="nav-link">Actualités</a>
                <a href="manage_gallery.php" class="nav-link">Galerie Photos</a>
                <a href="manage_partners.php" class="nav-link">Partenariats</a>
                <a href="manage_jobs.php" class="nav-link">Offres d'Emploi</a>
                <a href="manage_graduations.php" class="nav-link active">Diplômes</a>
                <a href="manage_labs.php" class="nav-link">Laboratoires</a>
                <a href="manage_calendar.php" class="nav-link">Calendrier</a>
                <a href="manage_student_life.php" class="nav-link">Vie Étudiante</a>
            </nav>
        </aside>
        <main class="main-content">
            <div class="content-wrapper">
                <header class="page-header"><div class="page-title"><h1>Remise des Diplômes</h1><p>Gérer les archives des promotions.</p></div><div class="user-profile"><a href="logout.php" onclick="confirmLogout('logout.php'); return false;" class="logout-link">Déconnexion</a></div></header>
                <?php include 'modals.php'; ?>
                <?php echo $message; ?>
                <div class="card">
                    <h2 class="card-title"><?php echo $edit_mode ? 'Modifier' : 'Ajouter'; ?> une promotion</h2>
                    <form action="" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="edit_id" value="<?php echo $edit_id; ?>">
                        <input type="hidden" name="existing_image_a" value="<?php echo $edit_data['image_url_a']; ?>">
                        <input type="hidden" name="existing_image_b" value="<?php echo $edit_data['image_url_b']; ?>">
                        <div class="form-row">
                            <div class="form-group"><label>Année</label><input type="number" name="year" class="form-control" required value="<?php echo htmlspecialchars($edit_data['year']); ?>"></div>
                            <div class="form-group"><label>Description</label><textarea name="content_fr" class="form-control" rows="3" required><?php echo htmlspecialchars($edit_data['content_fr']); ?></textarea></div>
                        </div>
                        <div class="form-row">
                            <div class="form-group"><label>Photo A</label><input type="file" name="image_a" class="form-control" accept="image/*"></div>
                            <div class="form-group"><label>Photo B</label><input type="file" name="image_b" class="form-control" accept="image/*"></div>
                        </div>
                        <button type="submit" class="btn-primary"><?php echo $edit_mode?'Enregistrer':'Publier'; ?></button>
                        <?php if($edit_mode): ?><a href="manage_graduations.php" class="btn-cancel">Annuler</a><?php endif; ?>
                    </form>
                </div>
                <div class="card">
                    <h2 class="card-title">Promotions enregistrées</h2>
                    <table class="data-table">
                        <thead><tr><th>Année</th><th style="text-align:right">Actions</th></tr></thead>
                        <tbody><?php foreach ($grads as $g): ?><tr><td style="font-weight:800; color:var(--insea-green)">Promotion <?php echo $g['year']; ?></td><td style="text-align:right"><a href="?edit=<?php echo $g['id']; ?>" class="link-edit">Modifier</a><a href="?delete=<?php echo $g['id']; ?>" class="link-delete" onclick="return confirm('Supprimer ?')">Supprimer</a></td></tr><?php endforeach; ?></tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
