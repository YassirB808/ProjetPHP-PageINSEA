<?php
require_once 'auth_check.php';
require_once '../components/PHP/db_connect.php';
require_once '../components/PHP/lang_handler.php';
require_once 'translator.php';

$message = '';

if (isset($_GET['delete'])) {
    $pdo->prepare("DELETE FROM laboratories WHERE id = ?")->execute([(int)$_GET['delete']]);
    $message = "<div class='alert alert-success'>Laboratoire supprimé.</div>";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name_fr = $_POST['name_fr'] ?? '';
    try {
        $pdo->beginTransaction();
        $pdo->prepare("INSERT INTO laboratories () VALUES ()")->execute();
        $lab_id = $pdo->lastInsertId();
        $pdo->prepare("INSERT INTO laboratories_translations (lab_id, language_id, name) VALUES (?, 1, ?), (?, 2, ?), (?, 3, ?)")->execute([$lab_id, $name_fr, $lab_id, autoTranslate($name_fr, 'fr', 'en'), $lab_id, autoTranslate($name_fr, 'fr', 'ar')]);
        $pdo->commit();
        $message = "<div class='alert alert-success'>Laboratoire ajouté.</div>";
    } catch (Exception $e) { $pdo->rollBack(); $message = "<div class='alert alert-error'>Erreur: ".$e->getMessage()."</div>"; }
}

$labs = $pdo->query("SELECT l.id, lt.name FROM laboratories l JOIN laboratories_translations lt ON l.id = lt.lab_id WHERE lt.language_id = 1 ORDER BY lt.name ASC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"><title>Laboratoires - INSEA Admin</title><link rel="stylesheet" href="admin_style.css">
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
                <a href="manage_labs.php" class="nav-link active">Laboratoires</a>
                <a href="manage_calendar.php" class="nav-link">Calendrier</a>
                <a href="manage_student_life.php" class="nav-link">Vie Étudiante</a>
            </nav>
        </aside>
        <main class="main-content">
            <div class="content-wrapper">
                <header class="page-header"><div class="page-title"><h1>Laboratoires</h1><p>Gérer les structures de recherche.</p></div><div class="user-profile"><a href="logout.php" onclick="confirmLogout('logout.php'); return false;" class="logout-link">Déconnexion</a></div></header>
                <?php include 'modals.php'; ?>
                <?php echo $message; ?>
                <div class="card">
                    <h2 class="card-title">Ajouter un Laboratoire</h2>
                    <form action="" method="POST">
                        <div class="form-group"><label>Nom du Laboratoire (Français)</label><input type="text" name="name_fr" class="form-control" required></div>
                        <button type="submit" class="btn-primary">Ajouter</button>
                    </form>
                </div>
                <div class="card">
                    <h2 class="card-title">Liste des structures</h2>
                    <table class="data-table">
                        <thead><tr><th>Nom</th><th style="text-align:right">Actions</th></tr></thead>
                        <tbody><?php foreach ($labs as $l): ?><tr><td style="font-weight:600"><?php echo htmlspecialchars($l['name']); ?></td><td style="text-align:right"><a href="?delete=<?php echo $l['id']; ?>" class="link-delete" onclick="return confirm('Supprimer ?')">Supprimer</a></td></tr><?php endforeach; ?></tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
