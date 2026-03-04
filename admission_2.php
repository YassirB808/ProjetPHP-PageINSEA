<?php include 'components/PHP/header.php'; ?>

<main class="main-content">
    <section class="page-banner" style="background: var(--insea-green); color: var(--white); padding: 60px 5%; text-align: center;">
        <h1 style="font-size: 2.5rem; font-weight: 800;"><?php echo __('adm2_title'); ?></h1>
    </section>

    <section style="max-width: 1100px; margin: 60px auto; padding: 0 20px;">
        <div style="background: var(--white); padding: 50px; border-radius: 15px; box-shadow: var(--shadow-lg); border-top: 5px solid var(--insea-gold);">
            <h2 style="color: var(--insea-green); margin-bottom: 25px; font-weight: 800; text-align: center;"><?php echo __('adm_concours_titre_title'); ?></h2>
            <p style="color: var(--gray-600); line-height: 1.8; font-size: 1.1rem; text-align: center; margin-bottom: 40px;">
                <?php echo __('adm2_desc'); ?>
            </p>

            
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px;">
                <div style="background: var(--gray-50); padding: 25px; border-radius: 12px; text-align: center;">
                    <div style="font-size: 2rem; color: var(--insea-green); margin-bottom: 10px;">1</div>
                    <h4 style="font-weight: 800; margin-bottom: 10px;"><?php echo __('adm2_step_1_title'); ?></h4>
                    <p style="font-size: 0.9rem; color: var(--gray-600);"><?php echo __('adm2_step_1_text'); ?></p>
                </div>
                <div style="background: var(--gray-50); padding: 25px; border-radius: 12px; text-align: center;">
                    <div style="font-size: 2rem; color: var(--insea-green); margin-bottom: 10px;">2</div>
                    <h4 style="font-weight: 800; margin-bottom: 10px;"><?php echo __('adm2_step_2_title'); ?></h4>
                    <p style="font-size: 0.9rem; color: var(--gray-600);"><?php echo __('adm2_step_2_text'); ?></p>
                </div>
                <div style="background: var(--gray-50); padding: 25px; border-radius: 12px; text-align: center;">
                    <div style="font-size: 2rem; color: var(--insea-green); margin-bottom: 10px;">3</div>
                    <h4 style="font-weight: 800; margin-bottom: 10px;"><?php echo __('adm2_step_3_title'); ?></h4>
                    <p style="font-size: 0.9rem; color: var(--gray-600);"><?php echo __('adm2_step_3_text'); ?></p>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include 'components/PHP/footer.php'; ?>
