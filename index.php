<?php include 'components/PHP/header.php'; ?>

<!-- HERO SECTION -->
<section class="hero-wide">
    <img src="components/images/others/insea_home.jpg" alt="INSEA Campus Wide">
</section>

<!-- WELCOME FLEX -->
<section class="welcome-flex">
    <div class="welcome-img">
        <img src="components/images/others/insea_home_image2.png" alt="INSEA Welcome">
    </div>
    <div class="welcome-text">
        <h2><?php echo __('welcome_title'); ?></h2>
        <p>
            <?php echo __('welcome_text'); ?>
        </p>
        <a href="#" class="link-arrow"><?php echo __('explorer_plus'); ?></a>
    </div>
</section>

<!-- ACTUALITES SECTION -->
<section>
    <div class="section-header">
        <h2><?php echo __('actualites'); ?></h2>
        <div class="line"></div>
    </div>

    <!-- LE PLUS RÉCENT (Featured First) -->
    <section class="featured-news">
        <div class="featured-img">
            <?php echo __('logo_title'); ?>
        </div>
        <div class="featured-content">
            <span class="tag-recent"><?php echo __('featured_recent'); ?></span>
            <h2><?php echo __('news_1_title'); ?></h2>
            <p><?php echo __('news_1_desc'); ?></p>
            <a href="#" class="link-arrow link-white"><?php echo __('read_more'); ?></a>
        </div>
    </section>

    <!-- NEWS GRID -->
    <div class="news-grid">
        <article class="news-card">
            <div class="news-img">NEWS</div>
            <div class="news-content">
                <span class="news-date">15 Mars 2026</span>
                <h3><?php echo __('news_2_title'); ?></h3>
                <p><?php echo __('news_2_desc'); ?></p>
                <a href="#" class="link-arrow"><?php echo __('voir_tous'); ?></a>
            </div>
        </article>

        <article class="news-card">
            <div class="news-img">NEWS</div>
            <div class="news-content">
                <span class="news-date">10 Mars 2026</span>
                <h3><?php echo __('news_3_title'); ?></h3>
                <p><?php echo __('news_3_desc'); ?></p>
                <a href="#" class="link-arrow"><?php echo __('voir_tous'); ?></a>
            </div>
        </article>

        <article class="news-card">
            <div class="news-img">NEWS</div>
            <div class="news-content">
                <span class="news-date">05 Mars 2026</span>
                <h3><?php echo __('news_4_title'); ?></h3>
                <p><?php echo __('news_4_desc'); ?></p>
                <a href="#" class="link-arrow"><?php echo __('voir_tous'); ?></a>
            </div>
        </article>
    </div>

    <!-- VIEW ALL BUTTON -->
    <div class="btn-container">
        <a href="components/PHP/actualites.php" class="btn-outline"><?php echo __('voir_tous'); ?></a>
    </div>
</section>

<!-- HISTOIRE MISSION ORGANISATION -->
<section class="hmo-section">
    <div class="hmo-container">
        <div class="hmo-item">
            <h3><?php echo __('history'); ?></h3>
            <p><?php echo __('hmo_history_text'); ?></p>
            <a href="#" class="link-arrow"><?php echo __('read_more'); ?></a>
        </div>
        
        <div class="hmo-item">
            <h3><?php echo __('mission'); ?></h3>
            <p><?php echo __('hmo_mission_text'); ?></p>
            <a href="#" class="link-arrow"><?php echo __('read_more'); ?></a>
        </div>

        <div class="hmo-item">
            <h3><?php echo __('organisation'); ?></h3>
            <p><?php echo __('hmo_org_text'); ?></p>
            <a href="#" class="link-arrow"><?php echo __('read_more'); ?></a>
        </div>
    </div>
</section>

<!-- MOT DU DIRECTEUR -->
<section class="mot-section">
    <div class="mot-container">
        <div class="mot-image-wrap">
            <div class="mot-image">PHOTO</div>
        </div>
        <div class="mot-text">
            <h2><?php echo __('director_word'); ?></h2>
            <blockquote><?php echo __('director_quote'); ?></blockquote>
            <div class="mot-author"><?php echo __('director_name'); ?><span><?php echo __('director_sub'); ?></span></div>
        </div>
    </div>
</section>

<!-- ESPACE ETUDIANTS & CHIFFRES -->
<section class="espace-etudiants-section">
    <div class="section-header" style="margin-top: 0;">
        <h2 style="color: var(--gray-900);"><?php 
            $student_space = __('student_space');
            $parts = explode(' ', $student_space, 2);
            echo $parts[0]; 
            if(isset($parts[1])) echo ' <span style="color: var(--insea-green);">' . $parts[1] . '</span>';
        ?></h2>
    </div>
    
    <div class="espace-cards-grid">
        <article class="espace-card">
            <div class="espace-icon-wrap">
                <div class="espace-icon-inner">
                    <img src="components/images/logos/admission.png" alt="Accès et admissions">
                </div>
            </div>
            <h3><?php echo __('student_link_admission'); ?></h3>
            <a href="components/PHP/admission.php" class="espace-link"><?php echo __('more_infos'); ?></a>
        </article>
        
        <article class="espace-card">
            <div class="espace-icon-wrap">
                <div class="espace-icon-inner">
                    <img src="components/images/logos/calendrier.png" alt="Emploi du temps">
                </div>
            </div>
            <h3><?php echo __('student_link_schedule'); ?></h3>
            <a href="components/PHP/calendrier_scolaire.php" class="espace-link"><?php echo __('more_infos'); ?></a>
        </article>

        <article class="espace-card">
            <div class="espace-icon-wrap">
                <div class="espace-icon-inner">
                    <img src="components/images/logos/preinscription.png" alt="Préinscription">
                </div>
            </div>
            <h3><?php echo __('student_link_preins'); ?></h3>
            <a href="components/PHP/admission_1.php" class="espace-link"><?php echo __('more_infos'); ?></a>
        </article>

        <article class="espace-card">
            <div class="espace-icon-wrap">
                <div class="espace-icon-inner">
                    <img src="components/images/logos/planning.png" alt="Planing des examens">
                </div>
            </div>
            <h3><?php echo __('student_link_exams'); ?></h3>
            <a href="components/PHP/calendrier_scolaire.php" class="espace-link"><?php echo __('more_infos'); ?></a>
        </article>
    </div>

    <div class="chiffres-icons-grid">
        <div class="chiffre-icon-item">
            <img src="components/images/logos/inscrits.png" alt="Etudiants inscrits">
            <div class="chiffre-number">1411</div>
            <div class="chiffre-label">Etudiants Inscrits</div>
        </div>
        <div class="chiffre-icon-item">
            <img src="components/images/logos/filiere.png" alt="Filières">
            <div class="chiffre-number">11</div>
            <div class="chiffre-label">Filières</div>
        </div>
        <div class="chiffre-icon-item">
            <img src="components/images/logos/recherche.png" alt="Centres de recherche">
            <div class="chiffre-number">1</div>
            <div class="chiffre-label">Centres de Recherche</div>
        </div>
        <div class="chiffre-icon-item">
            <img src="components/images/logos/equipe_recherche.png" alt="Equipes de recherche">
            <div class="chiffre-number">7</div>
            <div class="chiffre-label">Equipes de Recherche</div>
        </div>
    </div>
</section>

<!-- ADMINISTRATION SECTION -->
<section class="admin-section">
    <div class="section-header" style="margin-top: 0;">
        <h2><?php echo __('admin_title'); ?></h2>
        <div class="line"></div>
    </div>
    <div class="admin-grid">
        <article class="admin-card">
            <div class="admin-photo"></div>
            <h4>NOM ET PRÉNOM</h4>
            <p><?php echo __('admin_role_director'); ?></p>
        </article>
        <article class="admin-card">
            <div class="admin-photo"></div>
            <h4>NOM ET PRÉNOM</h4>
            <p><?php echo __('admin_role_deputy'); ?></p>
        </article>
        <article class="admin-card">
            <div class="admin-photo"></div>
            <h4>NOM ET PRÉNOM</h4>
            <p><?php echo __('admin_role_deputy'); ?></p>
        </article>
        <article class="admin-card">
            <div class="admin-photo"></div>
            <h4>NOM ET PRÉNOM</h4>
            <p><?php echo __('admin_role_secretary'); ?></p>
        </article>
    </div>
</section>

<!-- ORGANIGRAMME -->
<section class="orga-section">
    <div class="section-header" style="margin-top: 0;">
        <h2><?php echo __('organigramme'); ?></h2>
        <div class="line"></div>
    </div>
    <div class="orga-container">
        <div class="orga-box">
            [ IMAGE ORGANIGRAMME ]
        </div>
    </div>
</section>

<!-- VIDEO SECTION -->
<section class="video-section">
    <div class="video-container">
        <iframe src="https://www.youtube.com/embed/SHy1NKN56yg" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
    </div>
    <div class="video-description">
        <h3><?php echo __('discover_video'); ?></h3>
        <p><?php echo __('video_desc'); ?></p>
        <a href="#" class="link-arrow"><?php echo __('video_view_more'); ?></a>
    </div>
</section>

<!-- GALERIE SECTION -->
<section class="gallery-section">
    <div class="section-header" style="margin-top: 0;">
        <h2><?php echo __('gallery'); ?></h2>
        <div class="line"></div>
    </div>
    
    <div class="gallery-filters">
        <span class="filter-btn active"><?php echo __('gallery_filter_all'); ?></span>
        <span class="filter-btn"><?php echo __('gallery_filter_students'); ?></span>
        <span class="filter-btn"><?php echo __('gallery_filter_events'); ?></span>
        <span class="filter-btn"><?php echo __('gallery_filter_partners'); ?></span>
        <span class="filter-btn"><?php echo __('gallery_filter_research'); ?></span>
    </div>

    <div class="gallery-grid">
        <div class="gallery-item">IMG</div>
        <div class="gallery-item">IMG</div>
        <div class="gallery-item">IMG</div>
        <div class="gallery-item">IMG</div>
        <div class="gallery-item">IMG</div>
        <div class="gallery-item">IMG</div>
        <div class="gallery-item">IMG</div>
        <div class="gallery-item">IMG</div>
    </div>
</section>

<!-- PARTENARIATS SIDE SCROLLER -->
<section class="partners-section">
    <div class="section-header" style="margin-top: 0;">
        <h2><?php echo __('partners'); ?></h2>
        <div class="line"></div>
    </div>
    
    <div class="partners-viewport">
        <div class="partners-track">
            <div class="partner-logo" data-name="HCP Maroc"><img src="components/images/partenariats/hcp_logo.png" alt="HCP"></div>
            <div class="partner-logo" data-name="Banque Centrale Populaire"><img src="components/images/partenariats/Logo_BCP.png" alt="BCP"></div>
            <div class="partner-logo" data-name="DG Collectivités Locales"><img src="components/images/partenariats/Logo_DGCL.png" alt="DGCL"></div>
            <div class="partner-logo" data-name="ENSAI France"><img src="components/images/partenariats/Logo_ENSAIsvg.svg" alt="ENSAI"></div>
            <div class="partner-logo" data-name="UCLouvain Belgique"><img src="components/images/partenariats/UCLouvain_Logo.png" alt="UCLouvain"></div>
            <div class="partner-logo" data-name="UNFPA"><img src="components/images/partenariats/UNFPA_logo.svg" alt="UNFPA"></div>
            
            <!-- Duplicates for seamless loop -->
            <div class="partner-logo" data-name="HCP Maroc"><img src="components/images/partenariats/hcp_logo.png" alt="HCP"></div>
            <div class="partner-logo" data-name="Banque Centrale Populaire"><img src="components/images/partenariats/Logo_BCP.png" alt="BCP"></div>
            <div class="partner-logo" data-name="DG Collectivités Locales"><img src="components/images/partenariats/Logo_DGCL.png" alt="DGCL"></div>
            <div class="partner-logo" data-name="ENSAI France"><img src="components/images/partenariats/Logo_ENSAIsvg.svg" alt="ENSAI"></div>
            <div class="partner-logo" data-name="UCLouvain Belgique"><img src="components/images/partenariats/UCLouvain_Logo.png" alt="UCLouvain"></div>
            <div class="partner-logo" data-name="UNFPA"><img src="components/images/partenariats/UNFPA_logo.svg" alt="UNFPA"></div>
        </div>
    </div>
</section>

<?php include 'components/PHP/footer.php'; ?>
