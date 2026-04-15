<?php
require_once 'auth_check.php';
require_once '../components/PHP/db_connect.php';
require_once '../components/PHP/lang_handler.php';
require_once 'translator.php';
require_once 'icons.php';

$message = '';
$edit_mode = false;
$edit_id = 0;
$edit_data = ['event_date' => '', 'title_fr' => ''];

if (isset($_GET['edit'])) {
    $edit_mode = true;
    $edit_id = (int)$_GET['edit'];
    $stmt = $pdo->prepare("SELECT et.event_date, et.title FROM events_translations et WHERE et.event_id = ? AND et.language_id = 1");
    $stmt->execute([$edit_id]);
    $res = $stmt->fetch();
    if ($res) $edit_data = ['event_date' => $res['event_date'], 'title_fr' => $res['title']];
}

if (isset($_GET['delete'])) {
    $pdo->prepare("DELETE FROM events WHERE id = ?")->execute([(int)$_GET['delete']]);
    $message = "<div class='alert alert-success'>Date supprimée.</div>";
}

if (isset($_POST['bulk_delete']) && !empty($_POST['selected_ids'])) {
    $ids = $_POST['selected_ids'];
    $placeholders = str_repeat('?,', count($ids) - 1) . '?';
    $stmt = $pdo->prepare("DELETE FROM events WHERE id IN ($placeholders)");
    $stmt->execute($ids);
    $message = "<div class='alert alert-success'>Éléments supprimés.</div>";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['bulk_delete'])) {
    $date_fr = $_POST['event_date'] ?? '';
    $title_fr = $_POST['title_fr'] ?? '';
    $post_id = (int)($_POST['edit_id'] ?? 0);

    try {
        $pdo->beginTransaction();
        $date_en = autoTranslate($date_fr, 'fr', 'en');
        $date_ar = autoTranslate($date_fr, 'fr', 'ar');
        $title_en = autoTranslate($title_fr, 'fr', 'en');
        $title_ar = autoTranslate($title_fr, 'fr', 'ar');

        if ($post_id > 0) {
            $pdo->prepare("UPDATE events SET event_date = ? WHERE id = ?")->execute([$date_fr, $post_id]);
            $stmt_up = $pdo->prepare("UPDATE events_translations SET title = ?, event_date = ? WHERE event_id = ? AND language_id = ?");
            $stmt_up->execute([$title_fr, $date_fr, $post_id, 1]);
            $stmt_up->execute([$title_en, $date_en, $post_id, 2]);
            $stmt_up->execute([$title_ar, $date_ar, $post_id, 3]);
            $pdo->commit();
            header('Location: manage_calendar.php?msg=updated'); exit;
        } else {
            $pdo->prepare("INSERT INTO events (event_date) VALUES (?)")->execute([$date_fr]);
            $event_id = $pdo->lastInsertId();
            $stmt_ins = $pdo->prepare("INSERT INTO events_translations (event_id, language_id, title, event_date) VALUES (?, 1, ?, ?), (?, 2, ?, ?), (?, 3, ?, ?)");
            $stmt_ins->execute([$event_id, $title_fr, $date_fr, $event_id, $title_en, $date_en, $event_id, $title_ar, $date_ar]);
            $pdo->commit();
            $message = "<div class='alert alert-success'>Événement ajouté avec traductions.</div>";
        }
    } catch (Exception $e) { $pdo->rollBack(); $message = "<div class='alert alert-error'>Erreur: ".$e->getMessage()."</div>"; }
}

if (isset($_GET['msg']) && $_GET['msg'] == 'updated') $message = "<div class='alert alert-success'>Modifications enregistrées.</div>";
$events = $pdo->query("SELECT e.id, et.event_date, et.title FROM events e JOIN events_translations et ON e.id = et.event_id WHERE et.language_id = 1 ORDER BY e.id ASC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"><title>Calendrier - INSEA Admin</title><link rel="stylesheet" href="admin_style.css">
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
                    <h2 class="card-title"><?php echo $edit_mode ? 'Modifier' : 'Ajouter'; ?> une échéance</h2>
                    <form action="" method="POST">
                        <input type="hidden" name="edit_id" value="<?php echo $edit_id; ?>">
                        <div class="form-row">
                            <div class="form-group"><label>Période</label><input type="text" name="event_date" class="form-control" required placeholder="Juillet 2026" value="<?php echo htmlspecialchars($edit_data['event_date']); ?>"></div>
                            <div class="form-group"><label>Événement</label><input type="text" name="title_fr" class="form-control" required placeholder="Examens" value="<?php echo htmlspecialchars($edit_data['title_fr']); ?>"></div>
                        </div>
                        <button type="submit" class="btn-primary"><?php echo $edit_mode ? 'Enregistrer' : 'Ajouter'; ?></button>
                        <?php if($edit_mode): ?><a href="manage_calendar.php" class="btn-cancel">Annuler</a><?php endif; ?>
                    </form>
                </div>
                <div class="card">
                    <h2 class="card-title">Événements enregistrés</h2>
                    <form action="" method="POST" onsubmit="return confirm('Supprimer les éléments sélectionnés ?')">
                        <div class="bulk-actions"><button type="submit" name="bulk_delete" id="btn-bulk-delete" class="btn-delete-selected" disabled><?php echo icon_trash(); ?> Supprimer la sélection</button></div>
                        <table class="data-table">
                            <thead><tr><th class="checkbox-col"><input type="checkbox" class="checkbox-custom" onclick="toggleSelectAll(this)"></th><th>Période</th><th>Événement</th><th style="text-align:right">Actions</th></tr></thead>
                            <tbody>
                                <?php foreach ($events as $e): ?>
                                <tr>
                                    <td class="checkbox-col"><input type="checkbox" name="selected_ids[]" value="<?php echo $e['id']; ?>" class="checkbox-custom" onclick="updateDeleteButton()"></td>
                                    <td><strong><?php echo htmlspecialchars($e['event_date']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($e['title']); ?></td>
                                    <td style="text-align:right">
                                        <div class="action-btns" style="justify-content: flex-end; display: flex;">
                                            <a href="?edit=<?php echo $e['id']; ?>" class="link-edit"><?php echo icon_pen(); ?></a>
                                            <a href="?delete=<?php echo $e['id']; ?>" class="link-delete" onclick="return confirm('Supprimer ?')"><?php echo icon_trash(); ?></a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
