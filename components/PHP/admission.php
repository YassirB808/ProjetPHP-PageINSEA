<?php include 'header.php'; ?>

<main class="main-content">
    <section class="page-banner">
        <h1><?php echo __('nav_acces'); ?></h1>
    </section>

    <section class="content-container">
        <div class="news-grid">
            <a href="admission_1.php" class="news-card">
                <article>
                    <div class="news-img">1ère Année</div>
                    <div class="news-content">
                        <h3><?php echo __('nav_admission_1'); ?></h3>
                        <p>Concours National Commun (CNC) ou Concours sur titre pour les titulaires d'une Licence.</p>
                    </div>
                </article>
            </a>

            <a href="admission_2.php" class="news-card">
                <article>
                    <div class="news-img">2ème Année</div>
                    <div class="news-content">
                        <h3><?php echo __('nav_admission_2'); ?></h3>
                        <p><?php echo __('adm2_desc'); ?></p>
                    </div>
                </article>
            </a>

            <a href="cycle_master.php" class="news-card">
                <article>
                    <div class="news-img">Master</div>
                    <div class="news-content">
                        <h3><?php echo __('nav_master'); ?></h3>
                        <p>Admission au Master M2SI pour les titulaires d'une Licence en Informatique ou Mathématiques.</p>
                    </div>
                </article>
            </a>

            <a href="cycle_doctoral.php" class="news-card">
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
