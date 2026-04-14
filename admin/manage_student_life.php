<?php
require_once 'auth_check.php';
require_once '../components/PHP/db_connect.php';
require_once '../components/PHP/lang_handler.php';
require_once 'translator.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sl_id = (int)$_POST['student_life_id'];
    $content_fr = $_POST['content_fr'] ?? '';
    try {
        $pdo->beginTransaction();
        $pdo->prepare("UPDATE student_life_translations SET content = ? WHERE student_life_id = ? AND language_id = 1")->execute([$content_fr, $sl_id]);
        $pdo->prepare("UPDATE student_life_translations SET content = ? WHERE student_life_id = ? AND language_id = 2")->execute([autoTranslate($content_fr, 'fr', 'en'), $sl_id]);
        $pdo->prepare("UPDATE student_life_translations SET content = ? WHERE student_life_id = ? AND language_id = 3")->execute([autoTranslate($content_fr, 'fr', 'ar'), $sl_id]);
        $pdo->commit();
        $message = "<div class='alert alert-success'>Contenu mis à jour.</div>";
    } catch (Exception $e) { $pdo->rollBack(); $message = "<div class='alert alert-error'>Erreur: ".$e->getMessage()."</div>"; }
}

$categories = $pdo->query("SELECT sl.id, slt.title, slt.content FROM student_life sl JOIN student_life_translations slt ON sl.id = slt.student_life_id WHERE slt.language_id = 1")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"><title>Vie Étudiante - INSEA Admin</title><link rel="stylesheet" href="admin_style.css">
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
                <a href="manage_jobs.php" class="nav-link">Offres d'Emploi</a>
                <a href="manage_graduations.php" class="nav-link">Diplômes</a>
                <a href="manage_labs.php" class="nav-link">Laboratoires</a>
                <a href="manage_calendar.php" class="nav-link">Calendrier</a>
                <a href="manage_student_life.php" class="nav-link active">Vie Étudiante</a>
            </nav>
        </aside>
        <main class="main-content">
            <div class="content-wrapper">
                <header class="page-header"><div class="page-title"><h1>Vie Étudiante</h1><p>Gérer les services aux étudiants.</p></div><div class="user-profile"><a href="logout.php" onclick="confirmLogout('logout.php'); return false;" class="logout-link">Déconnexion</a></div></header>
                <?php include 'modals.php'; ?>
                <?php echo $message; ?>
                <?php foreach ($categories as $cat): ?>
                <div class="card">
                    <h3 class="card-title" style="color:var(--insea-green)"><?php echo htmlspecialchars($cat['title']); ?></h3>
                    <form action="" method="POST">
                        <input type="hidden" name="student_life_id" value="<?php echo $cat['id']; ?>">
                        <div class="form-group"><label>Description (Français)</label><textarea name="content_fr" class="form-control" rows="4" required><?php echo htmlspecialchars($cat['content']); ?></textarea></div>
                        <button type="submit" class="btn-primary" style="width:auto; padding:10px 30px">Mettre à jour</button>
                    </form>
                </div>
                <?php endforeach; ?>
            </div>
        </main>
    </div>
</body>
</html>
