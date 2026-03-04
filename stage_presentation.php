<?php include 'components/PHP/header.php'; ?>

<main class="main-content">
    <section class="page-banner" style="background: var(--insea-green); color: var(--white); padding: 60px 5%; text-align: center;">
        <h1 style="font-size: 2.5rem; font-weight: 800;"><?php echo __('stage_pres_title'); ?></h1>
    </section>

    <section style="max-width: 1000px; margin: 60px auto; padding: 0 20px;">
        <div style="background: var(--white); padding: 40px; border-radius: 15px; box-shadow: var(--shadow-lg); line-height: 1.8; color: var(--gray-600);">
            <p style="font-size: 1.2rem; margin-bottom: 30px; font-weight: 600; color: var(--gray-800);">
                <?php echo __('stage_pres_desc'); ?>
            </p>

            <h2 style="color: var(--insea-green); margin-top: 40px; margin-bottom: 20px; font-weight: 800;"><?php echo __('stage_types_title'); ?></h2>
            <ul style="list-style: none; padding: 0;">
                <li style="margin-bottom: 20px; padding: 20px; background: var(--gray-50); border-radius: 8px; border-left: 4px solid var(--insea-gold);">
                    <strong><?php echo __('stage_type_1_title'); ?> :</strong> <?php echo __('stage_type_1_text'); ?>
                </li>
                <li style="margin-bottom: 20px; padding: 20px; background: var(--gray-50); border-radius: 8px; border-left: 4px solid var(--insea-gold);">
                    <strong><?php echo __('stage_type_2_title'); ?> :</strong> <?php echo __('stage_type_2_text'); ?>
                </li>
                <li style="padding: 20px; background: var(--gray-50); border-radius: 8px; border-left: 4px solid var(--insea-gold);">
                    <strong><?php echo __('stage_type_3_title'); ?> :</strong> <?php echo __('stage_type_3_text'); ?>
                </li>
            </ul>
        </div>
    </section>
</main>

<?php include 'components/PHP/footer.php'; ?>
