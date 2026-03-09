<?php include 'header.php'; ?>

<main class="main-content">
    <section class="page-banner" style="background: var(--insea-green); color: var(--white); padding: 60px 5%; text-align: center;">
        <h1 style="font-size: 2.5rem; font-weight: 800;"><?php echo __('nav_acces'); ?></h1>
    </section>

    <section style="max-width: 1200px; margin: 60px auto; padding: 0 20px;">
        <div class="news-grid" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 30px;">
            <a href="admission_1.php" class="news-card" style="text-decoration: none; color: inherit;">
                <article>
                    <div class="news-img">1ère Année</div>
                    <div class="news-content">
                        <h3><?php echo __('nav_admission_1'); ?></h3>
                        <p>Concours National Commun (CNC) ou Concours sur titre pour les titulaires d'une Licence.</p>
                    </div>
                </article>
            </a>

            <a href="admission_2.php" class="news-card" style="text-decoration: none; color: inherit;">
                <article>
                    <div class="news-img">2ème Année</div>
                    <div class="news-content">
                        <h3><?php echo __('nav_admission_2'); ?></h3>
                        <p><?php echo __('adm2_desc'); ?></p>
                    </div>
                </article>
            </a>

            <a href="cycle_master.php" class="news-card" style="text-decoration: none; color: inherit;">
                <article>
                    <div class="news-img">Master</div>
                    <div class="news-content">
                        <h3><?php echo __('nav_master'); ?></h3>
                        <p>Admission au Master M2SI pour les titulaires d'une Licence en Informatique ou Mathématiques.</p>
                    </div>
                </article>
            </a>

            <a href="cycle_doctoral.php" class="news-card" style="text-decoration: none; color: inherit;">
                <article>
                    <div class="news-img">Doctorat</div>
                    <div class="news-content">
                        <h3><?php echo __('nav_doctoral'); ?></h3>
                        <p>Inscription ouverte aux titulaires d'un Master ou d'un diplôme d'Ingénieur d'État.</p>
                    </div>
                </article>
            </a>
        </div>
    </section>
</main>

<?php include 'footer.php'; ?>
