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
    $stmt = $pdo->prepare("DELETE FROM laboratories WHERE id = ?");
    $stmt->execute([$id]);
    $message = "<div class='alert alert-success'>Laboratoire supprimé.</div>";
}

// Handle Add
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name_fr = $_POST['name_fr'] ?? '';

    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("INSERT INTO laboratories () VALUES ()");
        $stmt->execute();
        $lab_id = $pdo->lastInsertId();

        $name_en = autoTranslate($name_fr, 'fr', 'en');
        $name_ar = autoTranslate($name_fr, 'fr', 'ar');

        $stmt_t = $pdo->prepare("INSERT INTO laboratories_translations (lab_id, language_id, name) VALUES (?, ?, ?)");
        $stmt_t->execute([$lab_id, 1, $name_fr]);
        $stmt_t->execute([$lab_id, 2, $name_en]);
        $stmt_t->execute([$lab_id, 3, $name_ar]);

        $pdo->commit();
        $message = "<div class='alert alert-success'>Laboratoire ajouté et traduit !</div>";
    } catch (Exception $e) {
        $pdo->rollBack();
        $message = "<div class='alert alert-error'>Erreur : " . $e->getMessage() . "</div>";
    }
}

$stmt = $pdo->query("SELECT l.id, lt.name FROM laboratories l JOIN laboratories_translations lt ON l.id = lt.lab_id WHERE lt.language_id = 1 ORDER BY lt.name ASC");
$labs = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gérer les Laboratoires - INSEA</title>
    <link rel="stylesheet" href="../components/CSS/style.css">
    <style>
        .admin-container { max-width: 800px; margin: 40px auto; padding: 20px; }
        .form-section { background: white; padding: 30px; border-radius: 12px; box-shadow: var(--shadow); margin-bottom: 40px; }
        .lab-list { background: white; border-radius: 12px; box-shadow: var(--shadow); overflow: hidden; }
        .lab-item { padding: 15px 20px; border-bottom: 1px solid var(--gray-200); display: flex; justify-content: space-between; align-items: center; }
        .btn-delete { color: #dc3545; font-weight: bold; }
    </style>
</head>
<body style="background: var(--gray-50);">

    <div class="admin-container">
        <a href="index.php" style="color: var(--insea-green); font-weight: bold;">← Dashboard</a>
        
        <header class="section-header">
            <h1>Gérer les Laboratoires</h1>
            <div class="line"></div>
        </header>

        <?php echo $message; ?>

        <div class="form-section">
            <h2>Ajouter un Laboratoire</h2>
            <form action="" method="POST" class="form-grid" style="margin-top: 20px;">
                <div>
                    <label>Nom du Laboratoire (Français)</label>
                    <input type="text" name="name_fr" required placeholder="Ex: Laboratoire de Data Science et d'IA">
                </div>
                <button type="submit" class="btn-form-submit">Ajouter et Traduire</button>
            </form>
        </div>

        <h2>Liste des Laboratoires</h2>
        <div class="lab-list">
            <?php foreach ($labs as $l): ?>
            <div class="lab-item">
                <span><?php echo htmlspecialchars($l['name']); ?></span>
                <a href="?delete=<?php echo $l['id']; ?>" class="btn-delete" onclick="return confirm('Supprimer ce laboratoire ?')">Supprimer</a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

</body>
</html>
