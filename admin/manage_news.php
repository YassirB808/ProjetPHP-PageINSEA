<?php
require_once 'auth_check.php';
require_once '../components/PHP/db_connect.php';
require_once '../components/PHP/lang_handler.php';
require_once 'image_processor.php';
require_once 'translator.php';
require_once 'icons.php';

$message = '';
$edit_mode = false;
$edit_id = 0;
$edit_data = [
    'title_fr' => '',
    'content_fr' => '',
    'link_url' => '',
    'image_url' => '',
    'publish_date' => date('Y-m-d'),
    'is_featured' => 0
];

// Handle Edit Mode Fetch
if (isset($_GET['edit'])) {
    $edit_mode = true;
    $edit_id = (int)$_GET['edit'];
    $stmt = $pdo->prepare("SELECT n.*, nt.title, nt.content FROM news n JOIN news_translations nt ON n.id = nt.news_id WHERE n.id = ? AND nt.language_id = 1");
    $stmt->execute([$edit_id]);
    $res = $stmt->fetch();
    if ($res) {
        $edit_data = [
            'title_fr' => $res['title'],
            'content_fr' => $res['content'],
            'link_url' => $res['link_url'],
            'image_url' => $res['image_url'],
            'publish_date' => $res['publish_date'],
            'is_featured' => $res['is_featured']
        ];
    }
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM news WHERE id = ?");
    if ($stmt->execute([$id])) {
        $message = "<div class='alert alert-success'>Article supprimé.</div>";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title_fr = $_POST['title_fr'] ?? '';
    $content_fr = $_POST['content_fr'] ?? '';
    $link_url = $_POST['link_url'] ?? NULL;
    $publish_date = $_POST['publish_date'] ?? date('Y-m-d');
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $post_id = $_POST['edit_id'] ?? 0;
    
    $db_image_path = $_POST['existing_image'] ?? NULL;
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "../components/images/others/";
        $uploaded_file = processAndSaveImage($_FILES['image']['tmp_name'], $target_dir, 'news');
        if ($uploaded_file) $db_image_path = "others/" . $uploaded_file;
    }

    if (!empty($title_fr) && !empty($content_fr)) {
        try {
            $pdo->beginTransaction();
            $title_en = autoTranslate($title_fr, 'fr', 'en');
            $content_en = autoTranslate($content_fr, 'fr', 'en');
            $title_ar = autoTranslate($title_fr, 'fr', 'ar');
            $content_ar = autoTranslate($content_fr, 'fr', 'ar');

            if ($post_id > 0) {
                $stmt = $pdo->prepare("UPDATE news SET image_url = ?, link_url = ?, publish_date = ?, is_featured = ? WHERE id = ?");
                $stmt->execute([$db_image_path, $link_url, $publish_date, $is_featured, $post_id]);
                $stmt_up_t = $pdo->prepare("UPDATE news_translations SET title = ?, content = ? WHERE news_id = ? AND language_id = ?");
                $stmt_up_t->execute([$title_fr, $content_fr, $post_id, 1]);
                $stmt_up_t->execute([$title_en, $content_en, $post_id, 2]);
                $stmt_up_t->execute([$title_ar, $content_ar, $post_id, 3]);
                $message = "<div class='alert alert-success'>Article mis à jour.</div>";
            } else {
                $stmt = $pdo->prepare("INSERT INTO news (publish_date, image_url, link_url, is_featured) VALUES (?, ?, ?, ?)");
                $stmt->execute([$publish_date, $db_image_path, $link_url, $is_featured]);
                $news_id = $pdo->lastInsertId();
                $stmt_trans = $pdo->prepare("INSERT INTO news_translations (news_id, language_id, title, content) VALUES (?, ?, ?, ?)");
                $stmt_trans->execute([$news_id, 1, $title_fr, $content_fr]);
                $stmt_trans->execute([$news_id, 2, $title_en, $content_en]);
                $stmt_trans->execute([$news_id, 3, $title_ar, $content_ar]);
                $message = "<div class='alert alert-success'>Article publié.</div>";
            }
            $pdo->commit();
            if ($post_id > 0) { header('Location: manage_news.php?msg=updated'); exit; }
        } catch (Exception $e) { $pdo->rollBack(); $message = "<div class='alert alert-error'>Erreur: ".$e->getMessage()."</div>"; }
    }
}

if (isset($_GET['msg']) && $_GET['msg'] == 'updated') $message = "<div class='alert alert-success'>Article mis à jour.</div>";
$stmt = $pdo->query("SELECT n.id, nt.title, n.publish_date FROM news n JOIN news_translations nt ON n.id = nt.news_id WHERE nt.language_id = 1 ORDER BY n.publish_date DESC");
$news_list = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Actualités - INSEA Admin</title>
    <link rel="stylesheet" href="admin_style.css">
</head>
<body>
    <div class="admin-layout">
        <aside class="sidebar">
            <div class="sidebar-brand">
                <img src="../components/images/logos/insea_logo.png" alt="">
                <span>INSEA ADMIN</span>
            </div>
            <nav class="sidebar-nav">
                <a href="index.php" class="nav-link">Dashboard</a>
                <a href="manage_news.php" class="nav-link active">Actualités</a>
                <a href="manage_gallery.php" class="nav-link">Galerie Photos</a>
                <a href="manage_partners.php" class="nav-link">Partenariats</a>
                <a href="manage_jobs.php" class="nav-link">Offres d'Emploi</a>
                <a href="manage_graduations.php" class="nav-link">Diplômes</a>
                <a href="manage_labs.php" class="nav-link">Laboratoires</a>
                <a href="manage_calendar.php" class="nav-link">Calendrier</a>
                <a href="manage_student_life.php" class="nav-link">Vie Étudiante</a>
            </nav>
        </aside>

        <main class="main-content">
            <div class="content-wrapper">
                <header class="page-header">
                    <div class="page-title">
                        <h1>Actualités</h1>
                        <p>Gérer les annonces et événements.</p>
                    </div>
                    <div class="user-profile">
                        <a href="logout.php" onclick="confirmLogout('logout.php'); return false;" class="logout-link">Déconnexion</a>
                    </div>
                    </header>

                    <?php include 'modals.php'; ?>
                <?php echo $message; ?>

                <div class="card">
                    <h2 class="card-title"><?php echo $edit_mode ? 'Modifier' : 'Ajouter'; ?> un article</h2>
                    <form action="" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="edit_id" value="<?php echo $edit_id; ?>">
                        <input type="hidden" name="existing_image" value="<?php echo $edit_data['image_url']; ?>">
                        
                        <div class="form-row">
                            <div class="form-group" style="grid-column: span 2;">
                                <label>Titre (Français)</label>
                                <input type="text" name="title_fr" class="form-control" required value="<?php echo htmlspecialchars($edit_data['title_fr']); ?>">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Date de publication</label>
                                <input type="date" name="publish_date" class="form-control" required value="<?php echo $edit_data['publish_date']; ?>">
                            </div>
                            <div class="form-group">
                                <label>Lien externe (Optionnel)</label>
                                <input type="text" name="link_url" class="form-control" placeholder="https://..." value="<?php echo htmlspecialchars($edit_data['link_url']); ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Contenu (Français)</label>
                            <textarea name="content_fr" class="form-control" rows="5" required><?php echo htmlspecialchars($edit_data['content_fr']); ?></textarea>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Image</label>
                                <input type="file" name="image" class="form-control" accept="image/*">
                            </div>
                            <div class="form-group" style="display: flex; align-items: center; padding-top: 25px; gap: 10px;">
                                <input type="checkbox" name="is_featured" id="feat" style="width: auto;" <?php echo $edit_data['is_featured'] ? 'checked' : ''; ?>>
                                <label for="feat" style="margin: 0;">Mettre à la une</label>
                            </div>
                        </div>
                        <button type="submit" class="btn-primary"><?php echo $edit_mode ? 'Mettre à jour' : 'Publier'; ?></button>
                        <?php if($edit_mode): ?><a href="manage_news.php" class="btn-cancel">Annuler</a><?php endif; ?>
                    </form>
                </div>

                <div class="card">
                    <h2 class="card-title">Liste des articles</h2>
                    <table class="data-table">
                        <thead><tr><th>Date</th><th>Titre</th><th style="text-align:right">Actions</th></tr></thead>
                        <tbody>
                            <?php foreach ($news_list as $n): ?>
                            <tr>
                                <td style="color:var(--gray-600); font-size:0.85rem;"><?php echo $n['publish_date']; ?></td>
                                <td style="font-weight:600;"><?php echo htmlspecialchars($n['title']); ?></td>
                                <td style="text-align:right">
                                    <div class="action-btns" style="justify-content: flex-end;">
                                        <a href="?edit=<?php echo $n['id']; ?>" class="link-edit" title="Modifier"><?php echo icon_pen(); ?></a>
                                        <a href="?delete=<?php echo $n['id']; ?>" class="link-delete" title="Supprimer"><?php echo icon_trash(); ?></a>
                                    </div>
                                </td>

                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
