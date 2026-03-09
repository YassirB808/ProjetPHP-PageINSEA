<?php include 'header.php'; ?>

<main class="main-content">
    <section class="page-banner" style="background: var(--insea-green); color: var(--white); padding: 60px 5%; text-align: center;">
        <h1 style="font-size: 2.5rem; font-weight: 800;"><?php echo __('adm1_title'); ?></h1>
    </section>

    <section style="max-width: 1100px; margin: 60px auto; padding: 0 20px;">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px;">
            <div style="background: var(--white); padding: 40px; border-radius: 15px; box-shadow: var(--shadow-lg); border-top: 5px solid var(--insea-green);">
                <h2 style="color: var(--insea-green); margin-bottom: 20px; font-weight: 800;"><?php echo __('adm_cnc_title'); ?></h2>
                <p style="color: var(--gray-600); line-height: 1.8; margin-bottom: 20px;">
                    <?php echo __('adm_cnc_desc'); ?>
                </p>
                <ul style="list-style: none; padding: 0; color: var(--gray-600);">
                    <li style="margin-bottom: 10px;">&check; <?php echo __('adm_cnc_step_1'); ?></li>
                    <li style="margin-bottom: 10px;">&check; <?php echo __('adm_cnc_step_2'); ?></li>
                </ul>
            </div>

            <div style="background: var(--white); padding: 40px; border-radius: 15px; box-shadow: var(--shadow-lg); border-top: 5px solid var(--insea-gold);">
                <h2 style="color: var(--insea-green); margin-bottom: 20px; font-weight: 800;"><?php echo __('adm_concours_titre_title'); ?></h2>
                <p style="color: var(--gray-600); line-height: 1.8; margin-bottom: 20px;">
                    <?php echo __('adm_concours_titre_desc'); ?>
                </p>
                <ul style="list-style: none; padding: 0; color: var(--gray-600);">
                    <li style="margin-bottom: 10px;">&check; <?php echo __('adm_titre_step_1'); ?></li>
                    <li style="margin-bottom: 10px;">&check; <?php echo __('adm_titre_step_2'); ?></li>
                    <li style="margin-bottom: 10px;">&check; <?php echo __('adm_titre_step_3'); ?></li>
                </ul>
            </div>
        </div>
    </section>
</main>

<?php include 'footer.php'; ?>
