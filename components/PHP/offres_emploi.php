<?php 
include 'header.php'; 

$lang_id = get_language_id($pdo, get_lang_code());

$stmt = $pdo->prepare("
    SELECT jo.post_date, jot.title, jot.content, jo.image_url, jo.link_url
    FROM job_offers jo
    JOIN job_offers_translations jot ON jo.id = jot.job_offer_id
    WHERE jot.language_id = ?
    ORDER BY jo.post_date DESC
");
$stmt->execute([$lang_id]);
$jobs = $stmt->fetchAll();
?>

<main class="main-content">
    <section class="page-banner">
        <h1><?php echo __('nav_job_offers'); ?></h1>
    </section>

    <section class="content-container">
        <div class="news-grid">
            <?php if (empty($jobs)): ?>
                <p class="text-center" style="grid-column: span 3; color: var(--gray-500);"><?php echo __('no_jobs_found'); ?></p>
            <?php else: ?>
                <?php foreach ($jobs as $job): ?>
                    <article class="news-card">
                        <div class="news-img">
                            <?php if ($job['image_url']): ?>
                                <img src="<?php echo $assets_path . 'images/' . $job['image_url']; ?>" alt="<?php echo htmlspecialchars($job['title']); ?>" class="cover-img">
                            <?php else: ?>
                                JOB
                            <?php endif; ?>
                        </div>
                        <div class="news-content">
                            <div class="news-date"><?php echo __('posted_on'); ?> <?php echo date('d M Y', strtotime($job['post_date'])); ?></div>
                            <h3><?php echo htmlspecialchars($job['title']); ?></h3>
                            <p><?php echo htmlspecialchars($job['content']); ?></p>
                            <a href="<?php echo $job['link_url'] ? htmlspecialchars($job['link_url']) : '#'; ?>" 
                               class="link-arrow"
                               <?php echo $job['link_url'] ? 'target="_blank"' : ''; ?>>
                               <?php echo __('read_more'); ?>
                            </a>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php include 'footer.php'; ?>
