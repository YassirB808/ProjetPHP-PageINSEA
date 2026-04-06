<?php 
include 'header.php'; 

$lang_id = get_language_id($pdo, get_lang_code());

$stmt = $pdo->prepare("
    SELECT lt.name
    FROM laboratories l
    JOIN laboratories_translations lt ON l.id = lt.lab_id
    WHERE lt.language_id = ?
    ORDER BY lt.name ASC
");
$stmt->execute([$lang_id]);
$labs = $stmt->fetchAll();
?>

<main class="main-content">
    <section class="page-banner">
        <h1><?php echo __('nav_labs'); ?></h1>
    </section>

    <section class="content-container">
        <p class="page-intro">
            <?php echo __('labs_desc'); ?>
        </p>

        <div class="labs-grid">
            <?php if (empty($labs)): ?>
                <p class="text-center" style="color: var(--gray-500);"><?php echo __('no_labs_found'); ?></p>
            <?php else: ?>
                <?php foreach ($labs as $lab): ?>
                    <div class="lab-card">
                        <h3>
                            <?php echo htmlspecialchars($lab['name']); ?>
                        </h3>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php include 'footer.php'; ?>
