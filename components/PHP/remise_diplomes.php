<?php 
include 'header.php'; 

$lang_id = get_language_id($pdo, get_lang_code());

$stmt = $pdo->prepare("
    SELECT g.*, gt.content 
    FROM graduations g 
    JOIN graduations_translations gt ON g.id = gt.graduation_id 
    WHERE gt.language_id = ?
    ORDER BY g.year DESC
");
$stmt->execute([$lang_id]);
$graduations = $stmt->fetchAll();
?>

<main class="main-content">
    <section class="page-banner">
        <h1><?php echo __('nav_graduation'); ?></h1>
    </section>

    <?php if (empty($graduations)): ?>
        <section class="content-container">
            <p class="text-center" style="color: var(--gray-500);">Aucune cérémonie répertoriée pour le moment.</p>
        </section>
    <?php else: ?>
        <?php foreach ($graduations as $grad): ?>
            <section style="max-width: 1000px; margin: 60px auto; padding: 0 20px;">
                <div class="form-card text-center">
                    <h2 class="text-green" style="margin-bottom: 25px;">Promotion <?php echo $grad['year']; ?></h2>
                    <p style="font-size: 1.15rem; line-height: 1.8; color: var(--gray-600); margin-bottom: 40px;">
                        <?php echo nl2br(htmlspecialchars($grad['content'])); ?>
                    </p>
                    
                    <div class="news-grid" style="grid-template-columns: repeat(2, 1fr); gap: 30px; padding: 0;">
                        <div class="gallery-item" style="height: 300px;">
                            <?php if ($grad['image_url_a']): ?>
                                <img src="<?php echo $assets_path . 'images/' . $grad['image_url_a']; ?>" alt="Graduation A" class="cover-img">
                            <?php else: ?>
                                <div style="height: 100%; display: flex; align-items: center; justify-content: center; background: var(--gray-50); color: var(--gray-400); font-weight: 800;">PHOTO A</div>
                            <?php endif; ?>
                        </div>
                        <div class="gallery-item" style="height: 300px;">
                            <?php if ($grad['image_url_b']): ?>
                                <img src="<?php echo $assets_path . 'images/' . $grad['image_url_b']; ?>" alt="Graduation B" class="cover-img">
                            <?php else: ?>
                                <div style="height: 100%; display: flex; align-items: center; justify-content: center; background: var(--gray-50); color: var(--gray-400); font-weight: 800;">PHOTO B</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </section>
        <?php endforeach; ?>
    <?php endif; ?>
</main>

<?php include 'footer.php'; ?>
