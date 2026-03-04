<?php include 'components/PHP/header.php'; ?>

<main class="main-content">
    <section class="page-banner" style="background: var(--insea-green); color: var(--white); padding: 60px 5%; text-align: center;">
        <h1 style="font-size: 2.5rem; font-weight: 800;"><?php echo __('cycle_ingenieur'); ?></h1>
    </section>

    <section class="ci-intro" style="max-width: 1000px; margin: 60px auto; padding: 0 20px; line-height: 1.8; text-align: center;">
        <p style="font-size: 1.2rem; color: var(--gray-600);">
            <?php echo __('ci_description'); ?>
        </p>
    </section>

    <section class="ci-specializations" style="background: var(--gray-50); padding: 80px 0;">
        <div class="section-header" style="margin-top: 0;">
            <h2><?php echo __('ci_specializations'); ?></h2>
            <div class="line"></div>
        </div>

        <div class="news-grid" style="max-width: 1200px; margin: 0 auto; display: grid; grid-template-columns: repeat(2, 1fr); gap: 30px; padding: 0 20px;">
            <a href="filiere_af.php" class="news-card" style="text-decoration: none; color: inherit;">
                <article>
                    <div class="news-img">AF</div>
                    <div class="news-content">
                        <h3><?php echo __('ci_af'); ?></h3>
                        <p><?php echo __('af_short'); ?></p>
                    </div>
                </article>
            </a>

            <a href="filiere_ds.php" class="news-card" style="text-decoration: none; color: inherit;">
                <article>
                    <div class="news-img">DS</div>
                    <div class="news-content">
                        <h3><?php echo __('ci_ds'); ?></h3>
                        <p><?php echo __('ds_short'); ?></p>
                    </div>
                </article>
            </a>

            <a href="filiere_se.php" class="news-card" style="text-decoration: none; color: inherit;">
                <article>
                    <div class="news-img">SE</div>
                    <div class="news-content">
                        <h3><?php echo __('ci_se'); ?></h3>
                        <p><?php echo __('se_short'); ?></p>
                    </div>
                </article>
            </a>

            <a href="filiere_ro.php" class="news-card" style="text-decoration: none; color: inherit;">
                <article>
                    <div class="news-img">RO</div>
                    <div class="news-content">
                        <h3><?php echo __('ci_ro'); ?></h3>
                        <p><?php echo __('ro_short'); ?></p>
                    </div>
                </article>
            </a>
        </div>
    </section>

    <section class="ci-info-grid" style="max-width: 1200px; margin: 80px auto; display: grid; grid-template-columns: repeat(2, 1fr); gap: 40px; padding: 0 20px;">
        <div class="info-box" style="background: var(--white); padding: 40px; border-radius: 12px; box-shadow: var(--shadow); border-top: 5px solid var(--insea-gold);">
            <h2 style="color: var(--insea-green); margin-bottom: 20px;"><?php echo __('ci_admission'); ?></h2>
            <p><?php echo __('ci_admission_text'); ?></p>
        </div>
        <div class="info-box" style="background: var(--white); padding: 40px; border-radius: 12px; box-shadow: var(--shadow); border-top: 5px solid var(--insea-gold);">
            <h2 style="color: var(--insea-green); margin-bottom: 20px;"><?php echo __('ci_program'); ?></h2>
            <p><?php echo __('ci_program_text'); ?></p>
        </div>
    </section>
</main>

<?php include 'components/PHP/footer.php'; ?>
