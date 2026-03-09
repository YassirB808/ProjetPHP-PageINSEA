<?php include 'header.php'; ?>

<main class="main-content">
    <section class="page-banner" style="background: var(--insea-green); color: var(--white); padding: 60px 5%; text-align: center;">
        <h1 style="font-size: 2.5rem; font-weight: 800;"><?php echo __('cal_title'); ?></h1>
    </section>

    <section style="max-width: 1000px; margin: 60px auto; padding: 0 20px;">
        <p style="text-align: center; color: var(--gray-600); font-size: 1.1rem; margin-bottom: 50px; line-height: 1.6;">
            <?php echo __('cal_desc'); ?>
        </p>

        <div style="background: var(--white); border-radius: 15px; box-shadow: var(--shadow-lg); overflow: hidden;">
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="background: var(--insea-green); color: var(--white);">
                        <th style="padding: 20px; font-weight: 700;"><?php echo __('cal_event'); ?></th>
                        <th style="padding: 20px; font-weight: 700;"><?php echo __('cal_date'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="border-bottom: 1px solid var(--gray-200);">
                        <td style="padding: 20px; font-weight: 600;">Rentrée universitaire</td>
                        <td style="padding: 20px;">Septembre 2025</td>
                    </tr>
                    <tr style="border-bottom: 1px solid var(--gray-200); background: var(--gray-50);">
                        <td style="padding: 20px; font-weight: 600;">Examens de fin du 1er semestre</td>
                        <td style="padding: 20px;">Janvier 2026</td>
                    </tr>
                    <tr style="border-bottom: 1px solid var(--gray-200);">
                        <td style="padding: 20px; font-weight: 600;">Vacances de fin de semestre</td>
                        <td style="padding: 20px;">Fin Janvier 2026</td>
                    </tr>
                    <tr style="border-bottom: 1px solid var(--gray-200); background: var(--gray-50);">
                        <td style="padding: 20px; font-weight: 600;">Début du 2ème semestre</td>
                        <td style="padding: 20px;">Février 2026</td>
                    </tr>
                    <tr style="border-bottom: 1px solid var(--gray-200);">
                        <td style="padding: 20px; font-weight: 600;">Examens de fin d'année</td>
                        <td style="padding: 20px;">Juin 2026</td>
                    </tr>
                    <tr style="background: var(--gray-50);">
                        <td style="padding: 20px; font-weight: 600;">Rattrapages</td>
                        <td style="padding: 20px;">Juillet 2026</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</main>

<?php include 'footer.php'; ?>
