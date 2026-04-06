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

// Handle Update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sl_id = (int)$_POST['student_life_id'];
    $content_fr = $_POST['content_fr'] ?? '';

    try {
        $pdo->beginTransaction();

        $content_en = autoTranslate($content_fr, 'fr', 'en');
        $content_ar = autoTranslate($content_fr, 'fr', 'ar');

        // Update translations
        $stmt = $pdo->prepare("UPDATE student_life_translations SET content = ? WHERE student_life_id = ? AND language_id = 1");
        $stmt->execute([$content_fr, $sl_id]);

        $stmt = $pdo->prepare("UPDATE student_life_translations SET content = ? WHERE student_life_id = ? AND language_id = 2");
        $stmt->execute([$content_en, $sl_id]);

        $stmt = $pdo->prepare("UPDATE student_life_translations SET content = ? WHERE student_life_id = ? AND language_id = 3");
        $stmt->execute([$content_ar, $sl_id]);

        $pdo->commit();
        $message = "<div class='alert alert-success'>Mise à jour réussie (EN/AR traduits automatiquement).</div>";
    } catch (Exception $e) {
        $pdo->rollBack();
        $message = "<div class='alert alert-error'>Erreur : " . $e->getMessage() . "</div>";
    }
}

// Fetch categories
$stmt = $pdo->query("SELECT sl.id, slt.title, slt.content FROM student_life sl JOIN student_life_translations slt ON sl.id = slt.student_life_id WHERE slt.language_id = 1");
$categories = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gérer la Vie Estudiantine - INSEA</title>
    <link rel="stylesheet" href="../components/CSS/style.css">
    <style>
        .admin-container { max-width: 900px; margin: 40px auto; padding: 20px; }
        .editor-section { background: white; padding: 30px; border-radius: 12px; box-shadow: var(--shadow); margin-bottom: 30px; border-left: 5px solid var(--insea-green); }
    </style>
</head>
<body style="background: var(--gray-50);">

    <div class="admin-container">
        <a href="index.php" style="color: var(--insea-green); font-weight: bold;">← Dashboard</a>
        
        <header class="section-header">
            <h1>Vie Estudiantine</h1>
            <div class="line"></div>
        </header>

        <?php echo $message; ?>

        <?php foreach ($categories as $cat): ?>
        <div class="editor-section">
            <h3 style="margin-bottom: 15px; color: var(--insea-green);"><?php echo htmlspecialchars($cat['title']); ?></h3>
            <form action="" method="POST" class="form-grid">
                <input type="hidden" name="student_life_id" value="<?php echo $cat['id']; ?>">
                <div>
                    <label>Description (Français)</label>
                    <textarea name="content_fr" rows="4" required><?php echo htmlspecialchars($cat['content']); ?></textarea>
                </div>
                <button type="submit" class="btn-form-submit" style="padding: 10px 20px; font-size: 0.9rem;">Mettre à jour cette section</button>
            </form>
        </div>
        <?php endforeach; ?>
    </div>

</body>
</html>
