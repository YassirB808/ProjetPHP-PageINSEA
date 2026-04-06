<?php
require_once 'auth_check.php';
require_once '../components/PHP/db_connect.php';

// Fetch quick counts for the stats
$count_news = $pdo->query("SELECT COUNT(*) FROM news")->fetchColumn();
$count_gallery = $pdo->query("SELECT COUNT(*) FROM gallery")->fetchColumn();
$count_jobs = $pdo->query("SELECT COUNT(*) FROM job_offers")->fetchColumn();
$count_partners = $pdo->query("SELECT COUNT(*) FROM partners")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - INSEA Dashboard</title>
    <link rel="stylesheet" href="../components/CSS/style.css">
    <style>
        :root {
            --admin-dark: #0f172a;
            --admin-sidebar: #1e293b;
            --admin-accent: #2A8049;
            --admin-gold: #D4AF37;
            --text-muted: #64748b;
            --card-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        }
        
        body { 
            background-color: #f8fafc; 
            margin: 0;
            font-family: 'Inter', -apple-system, sans-serif;
            color: #1e293b;
        }

        .layout { display: flex; min-height: 100vh; }

        /* Sidebar Styling */
        .sidebar {
            width: 260px;
            background: var(--admin-sidebar);
            color: white;
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            z-index: 100;
        }

        .sidebar-brand {
            padding: 32px 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        .sidebar-brand img { height: 40px; }
        .sidebar-brand span { font-weight: 700; letter-spacing: 0.05em; font-size: 1.1rem; }

        .nav-list { flex: 1; padding: 24px 12px; }
        .nav-item {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            color: #94a3b8;
            text-decoration: none;
            border-radius: 8px;
            margin-bottom: 4px;
            transition: all 0.2s;
            font-size: 0.95rem;
            font-weight: 500;
        }
        .nav-item:hover { background: rgba(255,255,255,0.05); color: white; }
        .nav-item.active { background: var(--admin-accent); color: white; }

        /* Main Content */
        .content { flex: 1; margin-left: 260px; padding: 40px; }

        .header-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 32px;
        }
        .header-title h1 { margin: 0; font-size: 1.8rem; font-weight: 800; color: var(--admin-dark); }
        .header-title p { margin: 4px 0 0; color: var(--text-muted); font-size: 0.9rem; }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 16px;
            background: white;
            padding: 8px 16px;
            border-radius: 50px;
            box-shadow: var(--card-shadow);
        }
        .user-name { font-weight: 600; font-size: 0.9rem; }
        .logout-link {
            font-size: 0.8rem;
            color: #ef4444;
            text-decoration: none;
            font-weight: 700;
            text-transform: uppercase;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 24px;
            margin-bottom: 48px;
        }
        .stat-card {
            background: white;
            padding: 24px;
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            border: 1px solid #e2e8f0;
        }
        .stat-label { color: var(--text-muted); font-size: 0.85rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.025em; }
        .stat-value { font-size: 2rem; font-weight: 800; color: var(--admin-dark); margin-top: 8px; }

        /* Modules Grid */
        .section-label { font-size: 1.1rem; font-weight: 700; margin-bottom: 24px; color: var(--admin-dark); }
        
        .modules-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 24px;
        }
        .module-card {
            background: white;
            padding: 32px;
            border-radius: 16px;
            text-decoration: none;
            color: inherit;
            box-shadow: var(--card-shadow);
            border: 1px solid #e2e8f0;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
        }
        .module-card:hover {
            transform: translateY(-4px);
            border-color: var(--admin-accent);
            box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1);
        }
        .module-card h3 { margin: 0; color: var(--admin-dark); font-size: 1.25rem; font-weight: 700; }
        .module-card p { margin: 12px 0 0; color: var(--text-muted); font-size: 0.9rem; line-height: 1.5; flex: 1; }
        .module-link {
            margin-top: 24px;
            color: var(--admin-accent);
            font-weight: 700;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .module-link::after { content: '→'; transition: transform 0.2s; }
        .module-card:hover .module-link::after { transform: translateX(4px); }

    </style>
</head>
<body>

    <div class="layout">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-brand">
                <img src="../components/images/logos/insea_logo.png" alt="">
                <span>INSEA ADMIN</span>
            </div>
            <nav class="nav-list">
                <a href="index.php" class="nav-item active">Tableau de Bord</a>
                <a href="manage_news.php" class="nav-item">Actualités</a>
                <a href="manage_gallery.php" class="nav-item">Galerie Photos</a>
                <a href="manage_partners.php" class="nav-item">Partenariats</a>
                <a href="manage_jobs.php" class="nav-item">Offres d'Emploi</a>
                <a href="manage_graduations.php" class="nav-item">Remise des Diplômes</a>
                <a href="manage_labs.php" class="nav-item">Laboratoires</a>
                <a href="manage_calendar.php" class="nav-item">Calendrier</a>
                <a href="manage_student_life.php" class="nav-item">Vie Étudiante</a>
                
                <div style="margin-top: 40px; padding: 0 16px;">
                    <a href="../index.php" target="_blank" class="nav-item" style="background: rgba(255,255,255,0.05); color: white; justify-content: center;">Voir le site public</a>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="content">
            <div class="header-bar">
                <div class="header-title">
                    <h1>Tableau de Bord</h1>
                    <p>Bienvenue, <?php echo htmlspecialchars($_SESSION['admin_user']); ?>. Gérer les contenus de l'institut.</p>
                </div>
                <div class="user-profile">
                    <span class="user-name">Administrateur</span>
                    <a href="logout.php" class="logout-link">Déconnexion</a>
                </div>
            </div>

            <!-- Stats -->
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

            <div class="section-label">Gestion des Modules</div>
            <div class="modules-grid">
                <a href="manage_news.php" class="module-card">
                    <h3>Actualités</h3>
                    <p>Publier et éditer les articles, événements et annonces officielles de l'institut.</p>
                    <div class="module-link">Gérer le contenu</div>
                </a>
                <a href="manage_gallery.php" class="module-card">
                    <h3>Galerie Photos</h3>
                    <p>Gérer les visuels du campus, des événements et des activités étudiantes.</p>
                    <div class="module-link">Gérer le contenu</div>
                </a>
                <a href="manage_partners.php" class="module-card">
                    <h3>Partenariats</h3>
                    <p>Mettre à jour la liste des entreprises et institutions partenaires nationales et internationales.</p>
                    <div class="module-link">Gérer le contenu</div>
                </a>
                <a href="manage_jobs.php" class="module-card">
                    <h3>Offres d'Emploi</h3>
                    <p>Diffuser les opportunités de carrière et stages pour les étudiants et lauréats.</p>
                    <div class="module-link">Gérer le contenu</div>
                </a>
                <a href="manage_graduations.php" class="module-card">
                    <h3>Remise des Diplômes</h3>
                    <p>Gérer l'historique des promotions et les archives photos des cérémonies.</p>
                    <div class="module-link">Gérer le contenu</div>
                </a>
                <a href="manage_student_life.php" class="module-card">
                    <h3>Vie Étudiante</h3>
                    <p>Modifier les informations relatives à l'ADEI, l'internat et les activités sociales.</p>
                    <div class="module-link">Gérer le contenu</div>
                </a>
            </div>
        </main>
    </div>

</body>
</html>
