<?php include 'components/PHP/header.php'; ?>

<main class="main-content">
    <section class="page-banner" style="background: var(--insea-green); color: var(--white); padding: 60px 5%; text-align: center;">
        <h1 style="font-size: 2.5rem; font-weight: 800;"><?php echo __('labs_title'); ?></h1>
    </section>

    <section style="max-width: 1200px; margin: 60px auto; padding: 0 20px;">
        <p style="text-align: center; color: var(--gray-600); font-size: 1.15rem; max-width: 800px; margin: 0 auto 60px; line-height: 1.8;">
            <?php echo __('labs_desc'); ?>
        </p>

        <div class="news-grid" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 30px;">
            <?php for($i=1; $i<=4; $i++): ?>
            <article class="news-card" style="box-shadow: 0 10px 30px rgba(0,0,0,0.08); border: 1px solid var(--gray-200); background: #fff;">
                <div class="news-img" style="background: var(--gray-50); height: 180px; display: flex; align-items: center; justify-content: center; border-bottom: 1px solid var(--gray-200); color: var(--insea-green); font-weight: 800; font-size: 1.2rem;">LAB 0<?php echo $i; ?></div>
                <div class="news-content" style="padding: 30px;">
                    <h3 style="margin-bottom: 15px; color: var(--gray-900); font-weight: 800;"><?php echo __('lab_' . $i . '_name'); ?></h3>
                    <p style="color: var(--gray-600); margin-bottom: 20px; font-size: 0.95rem; line-height: 1.6;">Développement de projets de recherche innovants, encadrement de thèses et organisation de manifestations scientifiques.</p>
                    <a href="#" class="link-arrow"><?php echo __('read_more'); ?></a>
                </div>
            </article>
            <?php endfor; ?>
        </div>
    </section>
</main>

<?php include 'components/PHP/footer.php'; ?>
