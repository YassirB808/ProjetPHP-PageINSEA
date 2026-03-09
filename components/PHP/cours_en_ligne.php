<?php include 'header.php'; ?>

<main class="main-content">
    <section class="page-banner" style="background: var(--insea-green); color: var(--white); padding: 60px 5%; text-align: center;">
        <h1 style="font-size: 2.5rem; font-weight: 800;"><?php echo __('cours_title'); ?></h1>
    </section>

    <section style="max-width: 1000px; margin: 60px auto; padding: 0 20px;">
        <p style="text-align: center; color: var(--gray-600); font-size: 1.1rem; margin-bottom: 50px; line-height: 1.6;">
            <?php echo __('cours_desc'); ?>
        </p>

        <div style="display: flex; justify-content: center;">
            <div style="background: var(--white); padding: 40px; border-radius: 15px; box-shadow: var(--shadow-lg); text-align: center; border-top: 5px solid #00a1f1; max-width: 500px; width: 100%;">
                <h3 style="color: var(--gray-800); font-weight: 800; margin-bottom: 15px;">Microsoft Teams</h3>
                <p style="color: var(--gray-600); margin-bottom: 25px;">Plateforme officielle pour les cours en visioconférence, le partage de documents et le travail collaboratif en temps réel.</p>
                <a href="https://teams.microsoft.com" target="_blank" class="btn-outline">Accéder à Teams</a>
            </div>
        </div>
    </section>
</main>

<?php include 'footer.php'; ?>
