<?php include 'header.php'; ?>

<main class="main-content">
    <section class="page-banner" style="background: var(--insea-green); color: var(--white); padding: 60px 5%; text-align: center;">
        <h1 style="font-size: 2.5rem; font-weight: 800;"><?php echo __('nav_doctoral'); ?></h1>
    </section>

    <section style="max-width: 1000px; margin: 60px auto; padding: 0 20px;">
        <div style="text-align: center; margin-bottom: 50px;">
            <h2 style="color: var(--insea-green); font-size: 2rem; font-weight: 800; margin-bottom: 20px;"><?php echo __('doc_title'); ?></h2>
            <div class="line" style="width: 80px; height: 4px; background: var(--insea-gold); margin: 0 auto;"></div>
        </div>

        <div style="display: grid; gap: 25px;">
            <div style="background: var(--white); padding: 30px; border-radius: 12px; box-shadow: var(--shadow); border-left: 5px solid var(--insea-green); transition: 0.3s;">
                <h3 style="color: var(--gray-800); font-weight: 700; font-size: 1.25rem;"><?php echo __('doc_si_si'); ?></h3>
            </div>
            
            <div style="background: var(--white); padding: 30px; border-radius: 12px; box-shadow: var(--shadow); border-left: 5px solid var(--insea-green); transition: 0.3s;">
                <h3 style="color: var(--gray-800); font-weight: 700; font-size: 1.25rem;"><?php echo __('doc_demo'); ?></h3>
            </div>

            <div style="background: var(--white); padding: 30px; border-radius: 12px; box-shadow: var(--shadow); border-left: 5px solid var(--insea-green); transition: 0.3s;">
                <h3 style="color: var(--gray-800); font-weight: 700; font-size: 1.25rem;"><?php echo __('doc_math'); ?></h3>
            </div>

            <div style="background: var(--white); padding: 30px; border-radius: 12px; box-shadow: var(--shadow); border-left: 5px solid var(--insea-green); transition: 0.3s;">
                <h3 style="color: var(--gray-800); font-weight: 700; font-size: 1.25rem; margin-bottom: 20px;"><?php echo __('doc_sti'); ?></h3>
                <ul style="list-style: none; padding-left: 20px; color: var(--gray-600); font-weight: 600;">
                    <li style="margin-bottom: 10px; position: relative; padding-left: 20px;">
                        <span style="position: absolute; left: 0; color: var(--insea-gold);">&raquo;</span>
                        <?php echo __('doc_opt_saa'); ?>
                    </li>
                    <li style="position: relative; padding-left: 20px;">
                        <span style="position: absolute; left: 0; color: var(--insea-gold);">&raquo;</span>
                        <?php echo __('doc_opt_eqf'); ?>
                    </li>
                </ul>
            </div>
        </div>
    </section>

    <section style="background: var(--gray-50); padding: 60px 0;">
        <div style="max-width: 1000px; margin: 0 auto; padding: 0 20px;">
            <div style="background: var(--white); padding: 40px; border-radius: 15px; box-shadow: var(--shadow-lg); border-top: 5px solid var(--insea-gold);">
                <h2 style="color: var(--insea-green); margin-bottom: 20px; font-weight: 800;"><?php echo __('ci_admission'); ?></h2>
                <p style="color: var(--gray-600); line-height: 1.8; font-size: 1.05rem;">
                    <?php echo __('doc_admission_text'); ?>
                </p>
            </div>
        </div>
    </section>
</main>

<?php include 'footer.php'; ?>
