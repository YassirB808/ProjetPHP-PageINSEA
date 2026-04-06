<?php 
include 'header.php'; 

$category = $_GET['category'] ?? '';
$lang_id = get_language_id($pdo, get_lang_code());

$stmt = $pdo->prepare("
    SELECT sl.image_url, slt.title, slt.content
    FROM student_life sl
    JOIN student_life_translations slt ON sl.id = slt.student_life_id
    WHERE sl.category_slug = ? AND slt.language_id = ?
");
$stmt->execute([$category, $lang_id]);
$data = $stmt->fetch();

if (!$data) {
    echo "<main class='main-content'><section class='content-container'><p class='text-center'>Page non trouvée.</p></section></main>";
    include 'footer.php';
    exit;
}
?>

<main class="main-content">
    <section class="page-banner">
        <h1><?php echo htmlspecialchars($data['title']); ?></h1>
    </section>

    <section class="content-container">
        <div style="display: flex; gap: 50px; align-items: flex-start; flex-wrap: wrap;">
            <?php if ($data['image_url']): ?>
                <div style="flex: 1; min-width: 300px;">
                    <img src="<?php echo $assets_path . $data['image_url']; ?>" alt="<?php echo htmlspecialchars($data['title']); ?>" class="cover-img" style="border-radius: 15px; box-shadow: var(--shadow-lg);">
                </div>
            <?php endif; ?>
            
            <div style="flex: 1.5; min-width: 300px;">
                <div class="form-card">
                    <h2 class="text-green" style="margin-bottom: 25px;"><?php echo htmlspecialchars($data['title']); ?></h2>
                    <div style="line-height: 1.8; color: var(--gray-600); font-size: 1.1rem;">
                        <?php echo nl2br(htmlspecialchars($data['content'])); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include 'footer.php'; ?>
