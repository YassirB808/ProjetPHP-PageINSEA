<?php
require_once 'auth_check.php';
require_once '../components/PHP/db_connect.php';

// Fetch quick counts for the stats
$count_news = $pdo->query("SELECT COUNT(*) FROM news")->fetchColumn();
$count_gallery = $pdo->query("SELECT COUNT(*) FROM gallery")->fetchColumn();
$count_jobs = $pdo->query("SELECT COUNT(*) FROM job_offers")->fetchColumn();
$count_partners = $pdo->query("SELECT COUNT(*) FROM partners")->fetchColumn();

// Check for missing extensions
$missing_exts = [];
if (!function_exists('curl_init')) $missing_exts[] = "cURL (Requis pour la traduction)";
if (!function_exists('imagecreatefromjpeg')) $missing_exts[] = "GD Library (Requis pour redimensionner les images)";
if (!function_exists('mb_substr')) $missing_exts[] = "mbstring (Requis pour l'Arabe)";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - INSEA</title>
    <link rel="stylesheet" href="admin_style.css">
</head>
<body>

    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-brand">
                <img src="../components/images/logos/insea_logo.png" alt="INSEA">
                <span>INSEA ADMIN</span>
            </div>
            <nav class="sidebar-nav">
                <a href="index.php" class="nav-link active">Tableau de Bord</a>
                <a href="manage_news.php" class="nav-link">Actualités</a>
                <a href="manage_gallery.php" class="nav-link">Galerie Photos</a>
                <a href="manage_partners.php" class="nav-link">Partenariats</a>
                <a href="manage_jobs.php" class="nav-link">Offres d'Emploi</a>
                <a href="manage_graduations.php" class="nav-link">Diplômes</a>
                <a href="manage_labs.php" class="nav-link">Laboratoires</a>
                <a href="manage_calendar.php" class="nav-link">Calendrier</a>
                <a href="manage_student_life.php" class="nav-link">Vie Étudiante</a>
                
                <div style="margin-top: auto; padding: 0 12px;">
                    <a href="../index.php" target="_blank" class="nav-link" style="background: rgba(255,255,255,0.05);">Voir le site public</a>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="page-header">
                <div class="page-title">
                    <h1>Tableau de Bord</h1>
                    <p>Bienvenue, <?php echo htmlspecialchars($_SESSION['admin_user']); ?>. Gérer les contenus de l'institut.</p>
                </div>
                <div class="user-profile">
                    <span class="user-name">Administrateur</span>
                    <a href="javascript:void(0)" onclick="confirmLogout('logout.php')" class="logout-link">Déconnexion</a>
                </div>
            </header>

            <?php include 'modals.php'; ?>

            <?php if (!empty($missing_exts)): ?>
            <div class="alert alert-error" style="background: #fffbeb; border-color: #fde68a; color: #92400e;">
                <h4 style="margin-bottom: 8px;">Attention : Configuration serveur incomplète</h4>
                <ul style="margin-left: 20px; font-size: 0.85rem;">
                    <?php foreach ($missing_exts as $ext): ?>
                        <li><?php echo $ext; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>

            <!-- Stats Grid -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-label">Actualités</div>
                    <div class="stat-value"><?php echo $count_news; ?></div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Photos Galerie</div>
                    <div class="stat-value"><?php echo $count_gallery; ?></div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Offres d'Emploi</div>
                    <div class="stat-value"><?php echo $count_jobs; ?></div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Partenaires</div>
                    <div class="stat-value"><?php echo $count_partners; ?></div>
                </div>
            </div>

            <h2 class="section-label">Gestion des Modules</h2>
            <div class="module-grid">
                <a href="manage_news.php" class="module-card">
                    <h3>Actualités</h3>
                    <p>Publier et éditer les articles, événements et annonces officielles.</p>
                </a>
                <a href="manage_gallery.php" class="module-card">
                    <h3>Galerie Photos</h3>
                    <p>Gérer les visuels du campus et des événements étudiants.</p>
                </a>
                <a href="manage_partners.php" class="module-card">
                    <h3>Partenariats</h3>
                    <p>Mettre à jour la liste des entreprises partenaires.</p>
                </a>
                <a href="manage_jobs.php" class="module-card">
                    <h3>Offres d'Emploi</h3>
                    <p>Diffuser les opportunités de carrière pour les lauréats.</p>
                </a>
                <a href="manage_graduations.php" class="module-card">
                    <h3>Diplômes</h3>
                    <p>Gérer l'historique des promotions et les photos des cérémonies.</p>
                </a>
                <a href="manage_student_life.php" class="module-card">
                    <h3>Vie Étudiante</h3>
                    <p>Modifier les informations relatives à l'ADEI et l'internat.</p>
                </a>
            </div>
        </main>
    </div>

</body>
</html>
