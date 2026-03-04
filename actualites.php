<?php include 'components/PHP/header.php'; ?>

<main class="main-content">
    <section class="page-banner" style="background: var(--insea-green); color: var(--white); padding: 60px 5%; text-align: center;">
        <h1 style="font-size: 2.5rem; font-weight: 800;"><?php echo __('nav_actualites'); ?></h1>
    </section>

    <section style="max-width: 1200px; margin: 60px auto; padding: 0 20px;">
        <div class="news-grid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px;">
            <!-- News 1 -->
            <article class="news-card">
                <div class="news-img">NEWS</div>
                <div class="news-content">
                    <div class="news-date">02 Mars 2026</div>
                    <h3><?php echo __('news_5_title'); ?></h3>
                    <p><?php echo __('news_5_desc'); ?></p>
                    <a href="#" class="link-arrow"><?php echo __('read_more'); ?></a>
                </div>
            </article>

            <!-- News 2 -->
            <article class="news-card">
                <div class="news-img">NEWS</div>
                <div class="news-content">
                    <div class="news-date">25 Février 2026</div>
                    <h3><?php echo __('news_2_title'); ?></h3>
                    <p><?php echo __('news_2_desc'); ?></p>
                    <a href="#" class="link-arrow"><?php echo __('read_more'); ?></a>
                </div>
            </article>

            <!-- News 3 -->
            <article class="news-card">
                <div class="news-img">NEWS</div>
                <div class="news-content">
                    <div class="news-date">15 Février 2026</div>
                    <h3><?php echo __('news_1_title'); ?></h3>
                    <p><?php echo __('news_1_desc'); ?></p>
                    <a href="#" class="link-arrow"><?php echo __('read_more'); ?></a>
                </div>
            </article>

            <!-- News 4 -->
            <article class="news-card">
                <div class="news-img">NEWS</div>
                <div class="news-content">
                    <div class="news-date">10 Février 2026</div>
                    <h3><?php echo __('news_3_title'); ?></h3>
                    <p><?php echo __('news_3_desc'); ?></p>
                    <a href="#" class="link-arrow"><?php echo __('read_more'); ?></a>
                </div>
            </article>
        </div>
    </section>
</main>

<?php include 'components/PHP/footer.php'; ?>
