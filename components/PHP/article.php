<?php 
include 'header.php'; 

$article_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$lang_id = get_language_id($pdo, get_lang_code());

$stmt = $pdo->prepare("
    SELECT n.publish_date, nt.title, nt.content, n.image_url, n.link_url
    FROM news n
    JOIN news_translations nt ON n.id = nt.news_id
    WHERE n.id = ? AND nt.language_id = ?
");
$stmt->execute([$article_id, $lang_id]);
$article = $stmt->fetch();

if (!$article) {
    echo "<main class='main-content'><section class='content-container'><p class='text-center'>Article non trouvé.</p></section></main>";
    include 'footer.php';
    exit;
}
?>

<style>
    .article-header {
        max-width: 900px;
        margin: 60px auto 40px;
        padding: 0 20px;
        text-align: center;
    }
    .article-meta {
        text-transform: uppercase;
        letter-spacing: 2px;
        font-weight: 700;
        color: var(--insea-green);
        font-size: 0.85rem;
        display: block;
        margin-bottom: 15px;
    }
    .article-title {
        font-size: 3rem;
        font-weight: 800;
        color: var(--gray-900);
        line-height: 1.1;
        margin-bottom: 30px;
        letter-spacing: -1px;
    }
    .article-hero {
        width: 100%;
        max-width: 800px;
        margin: 0 auto 60px;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 15px 35px -10px rgba(0, 0, 0, 0.1);
        height: 450px;
        background: #f0f2f5; /* Match the contain background */
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .article-hero img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain; /* SHOWS THE FULL IMAGE */
        display: block;
    }
    .article-body {
        max-width: 800px;
        margin: 0 auto 80px;
        padding: 0 20px;
    }
    .article-content {
        font-size: 1.25rem;
        line-height: 1.8;
        color: var(--gray-700);
        text-align: justify;
    }
    .article-footer {
        margin-top: 60px;
        padding-top: 40px;
        border-top: 1px solid var(--gray-200);
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 30px;
    }
    .external-link-box {
        background: var(--gray-50);
        padding: 30px;
        border-radius: 15px;
        width: 100%;
        text-align: center;
        border: 1px solid var(--gray-200);
    }
    .external-link-box p {
        margin-bottom: 15px;
        font-weight: 600;
        color: var(--gray-600);
    }
    .btn-action {
        background: var(--insea-green);
        color: white;
        padding: 15px 40px;
        border-radius: 50px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        transition: 0.3s;
        box-shadow: 0 10px 20px rgba(42, 128, 73, 0.2);
    }
    .btn-action:hover {
        background: var(--insea-green-dark);
        transform: translateY(-3px);
        box-shadow: 0 15px 30px rgba(42, 128, 73, 0.3);
        color: white;
    }
    .back-nav {
        color: var(--text-muted);
        font-weight: 600;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: 0.2s;
    }
    .back-nav:hover {
        color: var(--insea-green);
    }

    @media (max-width: 768px) {
        .article-title { font-size: 2.2rem; }
        .article-hero { border-radius: 0; }
    }
</style>

<main class="main-content">
    <!-- Header Section -->
    <header class="article-header">
        <span class="article-meta">Communication Officielle &bull; <?php echo date('d F Y', strtotime($article['publish_date'])); ?></span>
        <h1 class="article-title"><?php echo htmlspecialchars($article['title']); ?></h1>
    </header>

    <!-- Hero Image -->
    <?php if ($article['image_url']): ?>
    <div class="article-hero">
        <img src="<?php 
            echo (strpos($article['image_url'], 'http') === 0) 
                ? htmlspecialchars($article['image_url']) 
                : $assets_path . 'images/' . htmlspecialchars($article['image_url']); 
        ?>" alt="">
    </div>
    <?php endif; ?>

    <!-- Main Content Body -->
    <div class="article-body">
        <div class="article-content">
            <?php echo nl2br(htmlspecialchars($article['content'])); ?>
        </div>

        <footer class="article-footer">
            <?php if ($article['link_url']): ?>
                <div class="external-link-box">
                    <p>Pour plus d'informations, vous pouvez consulter la ressource externe liée à cet article :</p>
                    <a href="<?php echo htmlspecialchars($article['link_url']); ?>" target="_blank" class="btn-action">
                        Accéder au document complet
                    </a>
                </div>
            <?php endif; ?>

            <a href="actualites.php" class="back-nav">
                &larr; Retour à la liste des actualités
            </a>
        </footer>
    </div>
</main>

<?php include 'footer.php'; ?>
