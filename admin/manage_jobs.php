<?php
require_once 'auth_check.php';
require_once '../components/PHP/db_connect.php';
require_once '../components/PHP/lang_handler.php';
require_once 'image_processor.php';
require_once 'translator.php';

$message = '';
$edit_mode = false;
$edit_id = 0;
$edit_data = ['title_fr' => '', 'content_fr' => '', 'link_url' => '', 'post_date' => date('Y-m-d'), 'image_url' => ''];

if (isset($_GET['edit'])) {
    $edit_mode = true;
    $edit_id = (int)$_GET['edit'];
    $stmt = $pdo->prepare("SELECT jo.*, jot.title, jot.content FROM job_offers jo JOIN job_offers_translations jot ON jo.id = jot.job_offer_id WHERE jo.id = ? AND jot.language_id = 1");
    $stmt->execute([$edit_id]);
    $res = $stmt->fetch();
    if ($res) $edit_data = ['title_fr' => $res['title'], 'content_fr' => $res['content'], 'link_url' => $res['link_url'], 'post_date' => $res['post_date'], 'image_url' => $res['image_url']];
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $pdo->prepare("DELETE FROM job_offers WHERE id = ?")->execute([$id]);
    $message = "<div class='alert alert-success'>Offre supprimée.</div>";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title_fr = $_POST['title_fr'] ?? '';
    $content_fr = $_POST['content_fr'] ?? '';
    $link_url = $_POST['link_url'] ?? NULL;
    $post_date = $_POST['post_date'] ?? date('Y-m-d');
    $post_id = $_POST['edit_id'] ?? 0;
    $db_image_path = $_POST['existing_image'] ?? NULL;

    if (!empty($_FILES['image']['name'])) {
        $uploaded_file = processAndSaveImage($_FILES['image']['tmp_name'], "../components/images/others/", 'job');
        if ($uploaded_file) $db_image_path = "others/" . $uploaded_file;
    }

    if (!empty($title_fr) && !empty($content_fr)) {
        try {
            $pdo->beginTransaction();
            if ($post_id > 0) {
                $pdo->prepare("UPDATE job_offers SET post_date = ?, image_url = ?, link_url = ? WHERE id = ?")->execute([$post_date, $db_image_path, $link_url, $post_id]);
                $pdo->prepare("UPDATE job_offers_translations SET title = ?, content = ? WHERE job_offer_id = ? AND language_id = 1")->execute([$title_fr, $content_fr, $post_id]);
                $pdo->prepare("UPDATE job_offers_translations SET title = ?, content = ? WHERE job_offer_id = ? AND language_id = 2")->execute([autoTranslate($title_fr, 'fr', 'en'), autoTranslate($content_fr, 'fr', 'en'), $post_id]);
                $pdo->prepare("UPDATE job_offers_translations SET title = ?, content = ? WHERE job_offer_id = ? AND language_id = 3")->execute([autoTranslate($title_fr, 'fr', 'ar'), autoTranslate($content_fr, 'fr', 'ar'), $post_id]);
            } else {
                $pdo->prepare("INSERT INTO job_offers (post_date, image_url, link_url) VALUES (?, ?, ?)")->execute([$post_date, $db_image_path, $link_url]);
                $job_id = $pdo->lastInsertId();
                $pdo->prepare("INSERT INTO job_offers_translations (job_offer_id, language_id, title, content) VALUES (?, 1, ?, ?), (?, 2, ?, ?), (?, 3, ?, ?)")->execute([$job_id, $title_fr, $content_fr, $job_id, autoTranslate($title_fr, 'fr', 'en'), autoTranslate($content_fr, 'fr', 'en'), $job_id, autoTranslate($title_fr, 'fr', 'ar'), autoTranslate($content_fr, 'fr', 'ar')]);
            }
            $pdo->commit();
            if ($post_id > 0) { header('Location: manage_jobs.php?msg=updated'); exit; }
            $message = "<div class='alert alert-success'>Offre publiée.</div>";
        } catch (Exception $e) { $pdo->rollBack(); $message = "<div class='alert alert-error'>Erreur: ".$e->getMessage()."</div>"; }
    }
}

if (isset($_GET['msg']) && $_GET['msg'] == 'updated') $message = "<div class='alert alert-success'>Offre mise à jour.</div>";
$jobs = $pdo->query("SELECT jo.id, jot.title, jo.post_date FROM job_offers jo JOIN job_offers_translations jot ON jo.id = jot.job_offer_id WHERE jot.language_id = 1 ORDER BY jo.post_date DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Emplois - INSEA Admin</title>
    <link rel="stylesheet" href="admin_style.css">
</head>
<body>
    <div class="admin-layout">
        <aside class="sidebar">
            <div class="sidebar-brand"><img src="../components/images/logos/insea_logo.png" alt=""><span>INSEA ADMIN</span></div>
            <nav class="sidebar-nav">
                <a href="index.php" class="nav-link">Dashboard</a>
                <a href="manage_news.php" class="nav-link">Actualités</a>
                <a href="manage_gallery.php" class="nav-link">Galerie Photos</a>
                <a href="manage_partners.php" class="nav-link">Partenariats</a>
                <a href="manage_jobs.php" class="nav-link active">Offres d'Emploi</a>
                <a href="manage_graduations.php" class="nav-link">Diplômes</a>
                <a href="manage_labs.php" class="nav-link">Laboratoires</a>
                <a href="manage_calendar.php" class="nav-link">Calendrier</a>
                <a href="manage_student_life.php" class="nav-link">Vie Étudiante</a>
            </nav>
        </aside>
        <main class="main-content">
            <div class="content-wrapper">
                <header class="page-header">
                    <div class="page-title"><h1>Offres d'Emploi</h1><p>Gérer les opportunités de carrière.</p></div>
                    <div class="user-profile"><a href="logout.php" onclick="confirmLogout('logout.php'); return false;" class="logout-link">Déconnexion</a></div>
                    </header>
                    <?php include 'modals.php'; ?>

                <?php echo $message; ?>
                <div class="card">
                    <h2 class="card-title"><?php echo $edit_mode ? 'Modifier' : 'Publier'; ?> un poste</h2>
                    <form action="" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="edit_id" value="<?php echo $edit_id; ?>">
                        <input type="hidden" name="existing_image" value="<?php echo $edit_data['image_url']; ?>">
                        <div class="form-row">
                            <div class="form-group"><label>Titre du Poste</label><input type="text" name="title_fr" class="form-control" required value="<?php echo htmlspecialchars($edit_data['title_fr']); ?>"></div>
                            <div class="form-group"><label>Date de Publication</label><input type="date" name="post_date" class="form-control" value="<?php echo $edit_data['post_date']; ?>"></div>
                        </div>
                        <div class="form-group"><label>Lien Postulation</label><input type="text" name="link_url" class="form-control" placeholder="https://..." value="<?php echo htmlspecialchars($edit_data['link_url']); ?>"></div>
                        <div class="form-group"><label>Description</label><textarea name="content_fr" class="form-control" rows="5" required><?php echo htmlspecialchars($edit_data['content_fr']); ?></textarea></div>
                        <div class="form-group"><label>Logo / Image</label><input type="file" name="image" class="form-control" accept="image/*"></div>
                        <button type="submit" class="btn-primary"><?php echo $edit_mode?'Enregistrer':'Publier'; ?></button>
                        <?php if($edit_mode): ?><a href="manage_jobs.php" class="btn-cancel">Annuler</a><?php endif; ?>
                    </form>
                </div>
                <div class="card">
                    <h2 class="card-title">Offres Actives</h2>
                    <table class="data-table">
                        <thead><tr><th>Date</th><th>Poste</th><th style="text-align:right">Actions</th></tr></thead>
                        <tbody>
                            <?php foreach ($jobs as $j): ?>
                            <tr>
                                <td style="color:var(--gray-600); font-size:0.85rem;"><?php echo $j['post_date']; ?></td>
                                <td style="font-weight:600;"><?php echo htmlspecialchars($j['title']); ?></td>
                                <td style="text-align:right">
                                    <div class="action-btns" style="justify-content: flex-end;">
                                        <a href="?edit=<?php echo $j['id']; ?>" class="link-edit">Modifier</a>
                                        <a href="?delete=<?php echo $j['id']; ?>" class="link-delete" onclick="return confirm('Supprimer ?')">Supprimer</a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
