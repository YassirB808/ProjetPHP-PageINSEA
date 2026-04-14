<?php
require_once 'auth_check.php';
require_once '../components/PHP/db_connect.php';
require_once '../components/PHP/lang_handler.php';
require_once 'translator.php';

$message = '';

if (isset($_GET['delete'])) {
    $pdo->prepare("DELETE FROM events WHERE id = ?")->execute([(int)$_GET['delete']]);
    $message = "<div class='alert alert-success'>Date supprimée.</div>";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date_str = $_POST['event_date'] ?? '';
    $title_fr = $_POST['title_fr'] ?? '';
    try {
        $pdo->beginTransaction();
        $pdo->prepare("INSERT INTO events (event_date) VALUES (?)")->execute([$date_str]);
        $event_id = $pdo->lastInsertId();
        $pdo->prepare("INSERT INTO events_translations (event_id, language_id, title) VALUES (?, 1, ?), (?, 2, ?), (?, 3, ?)")->execute([$event_id, $title_fr, $event_id, autoTranslate($title_fr, 'fr', 'en'), $event_id, autoTranslate($title_fr, 'fr', 'ar')]);
        $pdo->commit();
        $message = "<div class='alert alert-success'>Événement ajouté.</div>";
    } catch (Exception $e) { $pdo->rollBack(); $message = "<div class='alert alert-error'>Erreur: ".$e->getMessage()."</div>"; }
}

$events = $pdo->query("SELECT e.id, e.event_date, et.title FROM events e JOIN events_translations et ON e.id = et.event_id WHERE et.language_id = 1 ORDER BY e.id ASC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"><title>Calendrier - INSEA Admin</title><link rel="stylesheet" href="admin_style.css">
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
                <a href="manage_calendar.php" class="nav-link active">Calendrier</a>
                <a href="manage_student_life.php" class="nav-link">Vie Étudiante</a>
            </nav>
        </aside>
        <main class="main-content">
            <div class="content-wrapper">
                <header class="page-header"><div class="page-title"><h1>Calendrier</h1><p>Gérer les dates clés.</p></div><div class="user-profile"><a href="logout.php" onclick="confirmLogout('logout.php'); return false;" class="logout-link">Déconnexion</a></div></header>
                <?php include 'modals.php'; ?>
                <?php echo $message; ?>
                <div class="card">
                    <h2 class="card-title">Ajouter une échéance</h2>
                    <form action="" method="POST">
                        <div class="form-row"><div class="form-group"><label>Période</label><input type="text" name="event_date" class="form-control" required placeholder="Juillet 2026"></div><div class="form-group"><label>Événement</label><input type="text" name="title_fr" class="form-control" required placeholder="Examens"></div></div>
                        <button type="submit" class="btn-primary">Ajouter</button>
                    </form>
                </div>
                <div class="card">
                    <h2 class="card-title">Événements enregistrés</h2>
                    <table class="data-table">
                        <thead><tr><th>Période</th><th>Événement</th><th style="text-align:right">Actions</th></tr></thead>
                        <tbody><?php foreach ($events as $e): ?><tr><td><strong><?php echo htmlspecialchars($e['event_date']); ?></strong></td><td><?php echo htmlspecialchars($e['title']); ?></td><td style="text-align:right"><a href="?delete=<?php echo $e['id']; ?>" class="link-delete" onclick="return confirm('Supprimer ?')">Supprimer</a></td></tr><?php endforeach; ?></tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
