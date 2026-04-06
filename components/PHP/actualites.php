<?php 
include 'header.php'; 
require_once 'db_connect.php';

$lang_code = get_lang_code();
$lang_id = get_language_id($pdo, $lang_code);

// Fetch news from database
$stmt = $pdo->prepare("
    SELECT n.id, n.publish_date, nt.title, nt.content, n.image_url, n.link_url
    FROM news n
    JOIN news_translations nt ON n.id = nt.news_id
    WHERE nt.language_id = ?
    ORDER BY n.publish_date DESC
");
$stmt->execute([$lang_id]);
$news_list = $stmt->fetchAll();
?>

<main class="main-content">
    <section class="page-banner">
        <h1><?php echo __('nav_actualites'); ?></h1>
    </section>

    <section class="content-container">
        <div class="news-grid">
            <?php foreach ($news_list as $news): ?>
                <?php 
                    $news_img = 'NEWS';
                    if ($news['image_url']) {
                        $img_src = (strpos($news['image_url'], 'http') === 0) 
                            ? htmlspecialchars($news['image_url']) 
                            : $assets_path . 'images/' . htmlspecialchars($news['image_url']);
                        $news_img = '<img src="' . $img_src . '" alt="News Image" class="cover-img">';
                    }
                ?>
                <article class="news-card">
                    <div class="news-img">
                        <?php echo $news_img; ?>
                    </div>
                    <div class="news-content">
                        <div class="news-date"><?php echo date('d M Y', strtotime($news['publish_date'])); ?></div>
                        <h3><?php echo htmlspecialchars($news['title']); ?></h3>
                        <p><?php echo truncate_text($news['content'], 150); ?></p>
                        <a href="article.php?id=<?php echo (int)$news['id']; ?>" class="link-arrow"><?php echo __('read_more'); ?></a>
                    </div>
                </article>
            <?php endforeach; ?>

            <?php if (empty($news_list)): ?>
                <p class="text-center" style="grid-column: span 3; color: var(--gray-500);"><?php echo __('no_news_found'); ?></p>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php include 'footer.php'; ?>
