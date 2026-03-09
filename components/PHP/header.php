<?php include_once __DIR__ . '/lang_handler.php'; 
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
    <link rel="stylesheet" href="<?php echo $assets_path; ?>CSS/style.css">
</head>
<body>

    <!-- MAIN BRANDING HEADER -->
    <header class="main-header">
        <div class="logo-block">
            <a href="<?php echo $index_path; ?>" style="display: flex; align-items: center; gap: 20px;">
                <img src="<?php echo $assets_path; ?>images/logos/INSEA_logo.png" alt="INSEA Logo">
                <div class="logo-text">
                    <h1><?php echo __('logo_title'); ?></h1>
                    <p><?php echo __('logo_subtitle'); ?></p>
                </div>
            </a>
        </div>

        <div class="header-right" style="display: flex; align-items: center; gap: 20px;">
            <div class="lang-switcher" style="display: flex; gap: 10px; font-weight: bold; font-size: 14px;">
                <a href="?lang=fr" style="color: <?php echo get_lang_code() == 'fr' ? 'var(--insea-green)' : '#333'; ?>; text-decoration: none;">FR</a>
                <a href="?lang=en" style="color: <?php echo get_lang_code() == 'en' ? 'var(--insea-green)' : '#333'; ?>; text-decoration: none;">EN</a>
                <a href="?lang=ar" style="color: <?php echo get_lang_code() == 'ar' ? 'var(--insea-green)' : '#333'; ?>; text-decoration: none;">AR</a>
            </div>
            <div class="search-header">
                <input type="text" placeholder="<?php echo __('search_placeholder'); ?>">
                <img src="<?php echo $assets_path; ?>images/logos/search_icon.png" alt="Rechercher" style="height: 18px; width: auto; opacity: 0.6;">
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
                        <li><a href="#"><?php echo __('nav_internship_restoration'); ?></a></li>
                        <li><a href="#"><?php echo __('nav_library'); ?></a></li>
                        <li><a href="#"><?php echo __('nav_foyer_study'); ?></a></li>
                        <li><a href="#"><?php echo __('nav_bde'); ?></a></li>
                        <li><a href="#"><?php echo __('nav_clubs'); ?></a></li>
                        <li><a href="#"><?php echo __('nav_sports'); ?></a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
