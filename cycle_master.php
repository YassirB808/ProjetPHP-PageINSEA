<?php include 'components/PHP/header.php'; ?>

<main class="main-content">
    <section class="page-banner" style="background: var(--insea-green); color: var(--white); padding: 60px 5%; text-align: center;">
        <h1 style="font-size: 2.5rem; font-weight: 800;"><?php echo __('nav_master'); ?></h1>
    </section>

    <section class="master-intro" style="max-width: 1200px; margin: 60px auto; padding: 0 20px;">
        <div style="background: var(--white); padding: 50px; border-radius: 15px; box-shadow: var(--shadow-lg); border-left: 5px solid var(--insea-gold);">
            <h2 style="color: var(--insea-green); margin-bottom: 25px; font-weight: 800;"><?php echo __('master_m2si_title'); ?></h2>
            <p style="font-size: 1.15rem; line-height: 1.8; color: var(--gray-600); margin-bottom: 30px;">
                <?php echo __('master_m2si_desc'); ?>
            </p>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; margin-top: 40px;">
                <div>
                    <h3 style="color: var(--insea-green); margin-bottom: 15px; font-weight: 700;"><?php echo __('ci_admission'); ?></h3>
                    <p style="color: var(--gray-600); line-height: 1.6;">
                        <?php echo __('master_admission_text'); ?>
                    </p>
                </div>
                <div>
                    <h3 style="color: var(--insea-green); margin-bottom: 15px; font-weight: 700;"><?php echo __('filiere_programme_title'); ?></h3>
                    <ul style="color: var(--gray-600); line-height: 1.6; padding-left: 20px;">
                        <li><?php echo __('master_prog_1'); ?></li>
                        <li><?php echo __('master_prog_2'); ?></li>
                        <li><?php echo __('master_prog_3'); ?></li>
                        <li><?php echo __('master_prog_4'); ?></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section class="ci-info-grid" style="max-width: 1200px; margin: 0 auto 80px; padding: 0 20px;">
         <div style="background: var(--gray-50); padding: 40px; border-radius: 12px; border: 1px solid var(--gray-200);">
            <h3 style="color: var(--insea-green); margin-bottom: 20px; font-weight: 800;"><?php echo __('filiere_debouches_title'); ?></h3>
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">
                <div style="background: var(--white); padding: 20px; border-radius: 8px; text-align: center; box-shadow: var(--shadow);">
                    <strong><?php echo __('master_job_1'); ?></strong>
                </div>
                <div style="background: var(--white); padding: 20px; border-radius: 8px; text-align: center; box-shadow: var(--shadow);">
                    <strong><?php echo __('master_job_2'); ?></strong>
                </div>
                <div style="background: var(--white); padding: 20px; border-radius: 8px; text-align: center; box-shadow: var(--shadow);">
                    <strong><?php echo __('master_job_3'); ?></strong>
                </div>
                <div style="background: var(--white); padding: 20px; border-radius: 8px; text-align: center; box-shadow: var(--shadow);">
                    <strong><?php echo __('master_job_4'); ?></strong>
                </div>
                <div style="background: var(--white); padding: 20px; border-radius: 8px; text-align: center; box-shadow: var(--shadow);">
                    <strong><?php echo __('master_job_5'); ?></strong>
                </div>
                <div style="background: var(--white); padding: 20px; border-radius: 8px; text-align: center; box-shadow: var(--shadow);">
                    <strong><?php echo __('master_job_6'); ?></strong>
                </div>
            </div>
         </div>
    </section>
</main>

<?php include 'components/PHP/footer.php'; ?>
