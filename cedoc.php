<?php include 'components/PHP/header.php'; ?>

<main class="main-content">
    <section class="page-banner" style="background: var(--insea-green); color: var(--white); padding: 60px 5%; text-align: center;">
        <h1 style="font-size: 2.5rem; font-weight: 800;"><?php echo __('cedoc_title'); ?></h1>
    </section>

    <section style="max-width: 1000px; margin: 60px auto; padding: 0 20px;">
        <div style="background: var(--white); padding: 40px; border-radius: 15px; box-shadow: var(--shadow-lg); border-left: 5px solid var(--insea-gold); margin-bottom: 40px;">
            <p style="font-size: 1.15rem; line-height: 1.8; color: var(--gray-800); margin-bottom: 30px; font-weight: 600;">
                <?php echo __('cedoc_desc'); ?>
            </p>

            <h2 style="color: var(--insea-green); font-size: 1.8rem; font-weight: 800; margin-bottom: 25px; border-bottom: 2px solid var(--insea-gold); display: inline-block; padding-bottom: 5px;">
                <?php echo __('cedoc_mission_title'); ?>
            </h2>

            <ul style="list-style: none; padding: 0;">
                <li style="margin-bottom: 20px; padding-left: 30px; position: relative; line-height: 1.7; color: var(--gray-600);">
                    <span style="position: absolute; left: 0; color: var(--insea-green); font-size: 1.2rem; font-weight: bold;">&bull;</span>
                    <?php echo __('cedoc_mission_1'); ?>
                </li>
                <li style="margin-bottom: 20px; padding-left: 30px; position: relative; line-height: 1.7; color: var(--gray-600);">
                    <span style="position: absolute; left: 0; color: var(--insea-green); font-size: 1.2rem; font-weight: bold;">&bull;</span>
                    <?php echo __('cedoc_mission_2'); ?>
                </li>
                <li style="padding-left: 30px; position: relative; line-height: 1.7; color: var(--gray-600);">
                    <span style="position: absolute; left: 0; color: var(--insea-green); font-size: 1.2rem; font-weight: bold;">&bull;</span>
                    <?php echo __('cedoc_mission_3'); ?>
                </li>
            </ul>
        </div>
    </section>
</main>

<?php include 'components/PHP/footer.php'; ?>
