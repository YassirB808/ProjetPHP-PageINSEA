<?php include_once __DIR__ . '/lang_handler.php'; 
require_once __DIR__ . '/db_connect.php';
$php_path = (basename($_SERVER['PHP_SELF']) === 'index.php') ? 'components/PHP/' : '';
$assets_path = (basename($_SERVER['PHP_SELF']) === 'index.php') ? 'components/' : '../';
$index_path = (basename($_SERVER['PHP_SELF']) === 'index.php') ? 'index.php' : '../../index.php';
?>
<!DOCTYPE html>
<html lang="<?php echo get_lang_code(); ?>" dir="<?php echo get_dir(); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo __('title'); ?></title>
    <link rel="icon" type="image/png" href="<?php echo $assets_path; ?>images/logos/insea_logo.png">
    <link rel="stylesheet" href="<?php echo $assets_path; ?>CSS/style.css">
</head>
<body>

    <!-- MAIN BRANDING HEADER -->
    <header class="main-header">
        <div class="logo-block">
            <a href="<?php echo $index_path; ?>" class="header-logo-link">
                <img src="<?php echo $assets_path; ?>images/logos/INSEA_logo.png" alt="INSEA Logo">
                <div class="logo-text">
                    <h1><?php echo __('logo_title'); ?></h1>
                    <p><?php echo __('logo_subtitle'); ?></p>
                </div>
            </a>
        </div>

        <div class="header-right">
            <div class="lang-switcher">
                <a href="?lang=fr" class="<?php echo get_lang_code() == 'fr' ? 'active' : ''; ?>">FR</a>
                <a href="?lang=en" class="<?php echo get_lang_code() == 'en' ? 'active' : ''; ?>">EN</a>
                <a href="?lang=ar" class="<?php echo get_lang_code() == 'ar' ? 'active' : ''; ?>">AR</a>
            </div>
            <div class="search-header">
                <input type="text" placeholder="<?php echo __('search_placeholder'); ?>">
                <img src="<?php echo $assets_path; ?>images/logos/search_icon.png" alt="Rechercher" class="search-icon">
            </div>
        </div>
    </header>

    <!-- NAVIGATION WRAPPER -->
    <div class="nav-wrapper">
        <div class="nav-container">
            <ul class="main-nav">
                <li><a href="<?php echo $index_path; ?>"><?php echo __('nav_home'); ?></a></li>
                <li>
                    <a href="#"><?php echo __('nav_formations'); ?></a>
                    <ul class="dropdown">
                        <li>
                            <a href="<?php echo $php_path; ?>cycle_ingenieur.php"><?php echo __('cycle_ingenieur'); ?> &rsaquo;</a>
                            <ul class="dropdown">
                                <li><a href="<?php echo $php_path; ?>filiere_af.php"><?php echo __('ci_af'); ?></a></li>
                                <li><a href="<?php echo $php_path; ?>filiere_ds.php"><?php echo __('ci_ds'); ?></a></li>
                                <li><a href="<?php echo $php_path; ?>filiere_se.php"><?php echo __('ci_se'); ?></a></li>
                                <li><a href="<?php echo $php_path; ?>filiere_ro.php"><?php echo __('ci_ro'); ?></a></li>
                            </ul>
                        </li>
                        <li><a href="<?php echo $php_path; ?>cycle_master.php"><?php echo __('nav_master'); ?></a></li>
                        <li><a href="<?php echo $php_path; ?>cycle_doctoral.php"><?php echo __('nav_doctoral'); ?></a></li>
                        <li><a href="<?php echo $php_path; ?>formation_continue.php"><?php echo __('nav_formation_continue'); ?></a></li>
                        <li>
                            <a href="<?php echo $php_path; ?>admission.php"><?php echo __('nav_acces'); ?> &rsaquo;</a>
                            <ul class="dropdown">
                                <li><a href="<?php echo $php_path; ?>admission_1.php"><?php echo __('nav_admission_1'); ?></a></li>
                                <li><a href="<?php echo $php_path; ?>admission_2.php"><?php echo __('nav_admission_2'); ?></a></li>
                            </ul>
                        </li>
                        <li><a href="<?php echo $php_path; ?>calendrier_scolaire.php"><?php echo __('nav_calendrier'); ?></a></li>
                        <li><a href="<?php echo $php_path; ?>cours_en_ligne.php"><?php echo __('nav_cours_ligne'); ?></a></li>
                    </ul>
                </li>
                <li><a href="<?php echo $php_path; ?>actualites.php"><?php echo __('nav_actualites'); ?></a></li>
                <li>
                    <a href="#"><?php echo __('nav_stage'); ?> &rsaquo;</a>
                    <ul class="dropdown">
                        <li><a href="<?php echo $php_path; ?>stage_presentation.php"><?php echo __('nav_stage_pres'); ?></a></li>
                        <li><a href="<?php echo $php_path; ?>stage_proposer.php"><?php echo __('nav_stage_proposer'); ?></a></li>
                    </ul>
                </li>

                <li><a href="<?php echo $php_path; ?>partenariats.php"><?php echo __('nav_partenariats'); ?></a></li>
                <li>
                    <a href="#"><?php echo __('nav_laureats'); ?></a>
                    <ul class="dropdown">
                        <li><a href="<?php echo $php_path; ?>offres_emploi.php"><?php echo __('nav_job_offers'); ?></a></li>
                        <li><a href="<?php echo $php_path; ?>remise_diplomes.php"><?php echo __('nav_graduation'); ?></a></li>
                    </ul>
                </li>
                <li>
                    <a href="#"><?php echo __('nav_recherche'); ?></a>
                    <ul class="dropdown">
                        <li><a href="<?php echo $php_path; ?>cedoc.php"><?php echo __('nav_cedoc'); ?></a></li>
                        <li><a href="<?php echo $php_path; ?>laboratoires.php"><?php echo __('nav_labs'); ?></a></li>
                    </ul>
                </li>
                <li>
                    <a href="#"><?php echo __('nav_vie_estudiantine'); ?></a>
                    <ul class="dropdown">
                        <li><a href="<?php echo $php_path; ?>vie_estudiantine.php?category=internat"><?php echo __('nav_internship_restoration'); ?></a></li>
                        <li><a href="<?php echo $php_path; ?>vie_estudiantine.php?category=library"><?php echo __('nav_library'); ?></a></li>
                        <li><a href="<?php echo $php_path; ?>vie_estudiantine.php?category=foyer"><?php echo __('nav_foyer_study'); ?></a></li>
                        <li><a href="<?php echo $php_path; ?>vie_estudiantine.php?category=adei"><?php echo __('nav_bde'); ?></a></li>
                        <li><a href="<?php echo $php_path; ?>vie_estudiantine.php?category=clubs"><?php echo __('nav_clubs'); ?></a></li>
                        <li><a href="<?php echo $php_path; ?>vie_estudiantine.php?category=sports"><?php echo __('nav_sports'); ?></a></li>
                        <li><a href="<?php echo $php_path; ?>vie_estudiantine.php?category=health"><?php echo __('nav_health'); ?></a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
