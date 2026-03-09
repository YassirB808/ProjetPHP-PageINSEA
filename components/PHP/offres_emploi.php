<?php include 'header.php'; ?>

<main class="main-content">
    <section class="page-banner" style="background: var(--insea-green); color: var(--white); padding: 60px 5%; text-align: center;">
        <h1 style="font-size: 2.5rem; font-weight: 800;"><?php echo __('nav_job_offers'); ?></h1>
    </section>

    <section style="max-width: 1200px; margin: 60px auto; padding: 0 20px;">
        <div class="news-grid" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 30px;">
            <!-- Dummy Job 1 -->
            <article class="news-card">
                <div class="news-img">JOB</div>
                <div class="news-content">
                    <div class="news-date">Posté le 01 Mars 2026</div>
                    <h3>Data Scientist Senior - Secteur Bancaire</h3>
                    <p>Une grande institution financière recherche un ingénieur INSEA spécialisé en Data Science pour piloter des projets de machine learning.</p>
                    <a href="#" class="link-arrow"><?php echo __('read_more'); ?></a>
                </div>
            </article>

            <!-- Dummy Job 2 -->
            <article class="news-card">
                <div class="news-img">JOB</div>
                <div class="news-content">
                    <div class="news-date">Posté le 20 Février 2026</div>
                    <h3>Actuaire Consultant - Cabinet International</h3>
                    <p>Recrutement de profils Actuariat-Finance pour des missions d'audit et de conseil en gestion des risques.</p>
                    <a href="#" class="link-arrow"><?php echo __('read_more'); ?></a>
                </div>
            </article>
        </div>
    </section>
</main>

<?php include 'footer.php'; ?>
