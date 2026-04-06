<?php
require_once 'auth_check.php';
require_once '../components/PHP/db_connect.php';
require_once '../components/PHP/lang_handler.php';

$message = '';

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

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM events WHERE id = ?");
    $stmt->execute([$id]);
    $message = "<div class='alert alert-success'>Événement supprimé du calendrier.</div>";
}

// Handle Add
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date_str = $_POST['event_date'] ?? '';
    $title_fr = $_POST['title_fr'] ?? '';

    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("INSERT INTO events (event_date) VALUES (?)");
        $stmt->execute([$date_str]);
        $event_id = $pdo->lastInsertId();

        $title_en = autoTranslate($title_fr, 'fr', 'en');
        $title_ar = autoTranslate($title_fr, 'fr', 'ar');

        $stmt_t = $pdo->prepare("INSERT INTO events_translations (event_id, language_id, title) VALUES (?, ?, ?)");
        $stmt_t->execute([$event_id, 1, $title_fr]);
        $stmt_t->execute([$event_id, 2, $title_en]);
        $stmt_t->execute([$event_id, 3, $title_ar]);

        $pdo->commit();
        $message = "<div class='alert alert-success'>Événement ajouté et traduit !</div>";
    } catch (Exception $e) {
        $pdo->rollBack();
        $message = "<div class='alert alert-error'>Erreur : " . $e->getMessage() . "</div>";
    }
}

$stmt = $pdo->query("SELECT e.id, e.event_date, et.title FROM events e JOIN events_translations et ON e.id = et.event_id WHERE et.language_id = 1 ORDER BY e.id ASC");
$events = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gérer le Calendrier - INSEA</title>
    <link rel="stylesheet" href="../components/CSS/style.css">
    <style>
        .admin-container { max-width: 800px; margin: 40px auto; padding: 20px; }
        .form-section { background: white; padding: 30px; border-radius: 12px; box-shadow: var(--shadow); margin-bottom: 40px; }
        .event-table { width: 100%; border-collapse: collapse; background: white; border-radius: 12px; overflow: hidden; box-shadow: var(--shadow); }
        .event-table th, .event-table td { padding: 15px; text-align: left; border-bottom: 1px solid var(--gray-200); }
        .event-table th { background: var(--insea-green); color: white; }
        .btn-delete { color: #dc3545; font-weight: bold; }
    </style>
</head>
<body style="background: var(--gray-50);">

    <div class="admin-container">
        <a href="index.php" style="color: var(--insea-green); font-weight: bold;">← Dashboard</a>
        
        <header class="section-header">
            <h1>Calendrier Universitaire</h1>
            <div class="line"></div>
        </header>

        <?php echo $message; ?>

        <div class="form-section">
            <h2>Ajouter une Date</h2>
            <form action="" method="POST" class="form-grid" style="margin-top: 20px;">
                <div class="form-row-2">
                    <div>
                        <label>Période / Date (Texte)</label>
                        <input type="text" name="event_date" required placeholder="Ex: Fin Janvier 2026">
                    </div>
                    <div>
                        <label>Événement (Français)</label>
                        <input type="text" name="title_fr" required placeholder="Ex: Examens de fin de semestre">
                    </div>
                </div>
                <button type="submit" class="btn-form-submit">Ajouter au Calendrier</button>
            </form>
        </div>

        <h2>Dates enregistrées</h2>
        <table class="event-table">
            <thead>
                <tr>
                    <th>Période</th>
                    <th>Événement</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($events as $e): ?>
                <tr>
                    <td><?php echo htmlspecialchars($e['event_date']); ?></td>
                    <td><?php echo htmlspecialchars($e['title']); ?></td>
                    <td>
                        <a href="?delete=<?php echo $e['id']; ?>" class="btn-delete" onclick="return confirm('Supprimer cette date ?')">Supprimer</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
