<?php
// Template for individual filière pages
if (!isset($filiere_key)) {
    header('Location: index.php');
    exit();
}

include 'components/PHP/header.php';
?>

<main class="main-content">
    <section class="page-banner" style="background: var(--insea-green); color: var(--white); padding: 60px 5%; text-align: center;">
        <h1 style="font-size: 2.5rem; font-weight: 800;"><?php echo __('filiere_' . $filiere_key . '_title'); ?></h1>
    </section>

    <div style="max-width: 1200px; margin: 0 auto; padding: 60px 20px;">
        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 50px;">
            <div class="filiere-main">
                <section style="margin-bottom: 50px;">
                    <h2 style="color: var(--insea-green); border-bottom: 2px solid var(--insea-gold); display: inline-block; padding-bottom: 5px; margin-bottom: 20px;">
                        <?php echo __('filiere_desc_title'); ?>
                    </h2>
                    <p style="font-size: 1.1rem; line-height: 1.8; color: var(--gray-600);">
                        <?php echo __($filiere_key . '_desc'); ?>
                    </p>
                </section>

                <section>
                    <h2 style="color: var(--insea-green); border-bottom: 2px solid var(--insea-gold); display: inline-block; padding-bottom: 5px; margin-bottom: 20px;">
                        <?php echo __('filiere_programme_title'); ?>
                    </h2>
                    <ul style="list-style: none; padding: 0;">
                        <li style="margin-bottom: 15px; padding-left: 25px; position: relative;">
                            <span style="position: absolute; left: 0; color: var(--insea-gold); font-weight: bold;">&check;</span>
                            Tronc commun scientifique et managérial.
                        </li>
                        <li style="margin-bottom: 15px; padding-left: 25px; position: relative;">
                            <span style="position: absolute; left: 0; color: var(--insea-gold); font-weight: bold;">&check;</span>
                            Modules de spécialité approfondis.
                        </li>
                        <li style="margin-bottom: 15px; padding-left: 25px; position: relative;">
                            <span style="position: absolute; left: 0; color: var(--insea-gold); font-weight: bold;">&check;</span>
                            Projets de fin d'études et stages en entreprise.
                        </li>
                    </ul>
                </section>
            </div>

            <aside class="filiere-sidebar">
                <div style="background: var(--gray-50); padding: 30px; border-radius: 12px; border: 1px solid var(--gray-200);">
                    <h3 style="color: var(--insea-green); margin-bottom: 20px; font-weight: 800;"><?php echo __('filiere_debouches_title'); ?></h3>
                    <ul style="list-style: none; padding: 0; font-size: 0.95rem;">
                        <li style="margin-bottom: 10px; border-bottom: 1px solid var(--gray-200); padding-bottom: 10px;">Ingénieur en entreprise</li>
                        <li style="margin-bottom: 10px; border-bottom: 1px solid var(--gray-200); padding-bottom: 10px;">Consultant expert</li>
                        <li style="margin-bottom: 10px; border-bottom: 1px solid var(--gray-200); padding-bottom: 10px;">Chercheur / Académique</li>
                        <li>Entrepreneur</li>
                    </ul>
                </div>
                
                <a href="cycle_ingenieur.php" class="btn-outline" style="margin-top: 30px; width: 100%; text-align: center;">
                    Retour au cycle
                </a>
            </aside>
        </div>
    </div>
</main>

<?php include 'components/PHP/footer.php'; ?>
