<?php
require_once 'auth_check.php';
require_once '../components/PHP/db_connect.php';
require_once '../components/PHP/lang_handler.php';
require_once 'image_processor.php';

$message = '';
$edit_mode = false;
$edit_id = 0;
$edit_data = [
    'name' => '',
    'type' => 'national',
    'logo_url' => ''
];

// Handle Edit Mode Fetch
if (isset($_GET['edit'])) {
    $edit_mode = true;
    $edit_id = (int)$_GET['edit'];
    $stmt = $pdo->prepare("SELECT * FROM partners WHERE id = ?");
    $stmt->execute([$edit_id]);
    $res = $stmt->fetch();
    if ($res) {
        $edit_data = [
            'name' => $res['name'],
            'type' => $res['type'],
            'logo_url' => $res['logo_url']
        ];
    }
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM partners WHERE id = ?");
    $stmt->execute([$id]);
    $message = "<div class='alert alert-success'>Partenaire supprimé.</div>";
}

// Handle Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $type = $_POST['type'] ?? 'national';
    $post_id = $_POST['edit_id'] ?? 0;

    $db_logo_path = $_POST['existing_logo'] ?? '';
    if (!empty($_FILES['logo']['name'])) {
        $target_dir = "../components/images/partenariats/";
        $uploaded_file = processAndSaveImage($_FILES['logo']['tmp_name'], $target_dir, 'partner', 400); // Smaller for logos
        if ($uploaded_file) {
            $db_logo_path = "partenariats/" . $uploaded_file;
        }
    }

    if (!empty($db_logo_path)) {
        try {
            if ($post_id > 0) {
                $stmt = $pdo->prepare("UPDATE partners SET name = ?, logo_url = ?, type = ? WHERE id = ?");
                $stmt->execute([$name, $db_logo_path, $type, $post_id]);
                $message = "<div class='alert alert-success'>Partenaire mis à jour.</div>";
            } else {
                $stmt = $pdo->prepare("INSERT INTO partners (name, logo_url, type) VALUES (?, ?, ?)");
                $stmt->execute([$name, $db_logo_path, $type]);
                $message = "<div class='alert alert-success'>Partenaire ajouté.</div>";
            }
            if ($post_id > 0) { header('Location: manage_partners.php?msg=updated'); exit; }
        } catch (Exception $e) {
            $message = "<div class='alert alert-error'>Erreur : " . $e->getMessage() . "</div>";
        }
    }
}

if (isset($_GET['msg']) && $_GET['msg'] == 'updated') $message = "<div class='alert alert-success'>Modifications enregistrées.</div>";

$stmt = $pdo->query("SELECT * FROM partners ORDER BY name ASC");
$partners = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gérer les Partenaires - Admin INSEA</title>
    <link rel="stylesheet" href="../components/CSS/style.css">
    <style>
        body { background: #f0f2f5; padding: 40px; }
        .admin-box { max-width: 900px; margin: 0 auto; }
        .back-link { display: inline-block; margin-bottom: 20px; color: var(--insea-green); font-weight: 700; text-decoration: none; }
        .partner-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 20px; }
        .partner-admin-item { background: white; padding: 15px; border-radius: 12px; box-shadow: var(--shadow); border: 1px solid var(--gray-200); text-align: center; position: relative; }
        .partner-admin-item img { max-width: 100%; height: 60px; object-fit: contain; margin-bottom: 10px; }
        .action-overlay { position: absolute; top: 5px; right: 5px; display: flex; gap: 3px; }
        .btn-act { padding: 2px 6px; border-radius: 3px; font-size: 0.65rem; color: white; text-decoration: none; font-weight: bold; }
        .btn-del { background: #dc3545; }
        .btn-ed { background: var(--insea-green); }
    </style>
</head>
<body>

    <div class="admin-box">
        <a href="index.php" class="back-link">← Retour au Dashboard</a>
        
        <header class="section-header" style="margin-top: 0; text-align: left;">
            <h1>Gestion des Partenaires</h1>
            <div class="line" style="margin: 12px 0;"></div>
        </header>

        <?php echo $message; ?>

        <div class="form-card" style="margin-bottom: 40px;">
            <h2 style="margin-bottom: 25px;"><?php echo $edit_mode ? 'Modifier le Partenaire' : 'Ajouter un Partenaire'; ?></h2>
            <form action="" method="POST" enctype="multipart/form-data" class="form-grid">
                <input type="hidden" name="edit_id" value="<?php echo $edit_id; ?>">
                <input type="hidden" name="existing_logo" value="<?php echo $edit_data['logo_url']; ?>">
                
                <div class="form-row-2">
                    <div>
                        <label>Nom de l'institution</label>
                        <input type="text" name="name" required value="<?php echo htmlspecialchars($edit_data['name']); ?>">
                    </div>
                    <div>
                        <label>Type</label>
                        <select name="type" required>
                            <option value="national" <?php echo $edit_data['type'] == 'national' ? 'selected' : ''; ?>>National</option>
                            <option value="international" <?php echo $edit_data['type'] == 'international' ? 'selected' : ''; ?>>International</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label>Logo (PNG/SVG) <?php echo $edit_mode ? '(Optionnel)' : ''; ?></label>
                    <input type="file" name="logo" accept="image/*" <?php echo $edit_mode ? '' : 'required'; ?>>
                </div>
                <button type="submit" class="btn-form-submit"><?php echo $edit_mode ? 'Enregistrer' : 'Ajouter'; ?></button>
                <?php if ($edit_mode): ?>
                    <a href="manage_partners.php" style="display: block; text-align: center; margin-top: 10px; color: var(--gray-500);">Annuler</a>
                <?php endif; ?>
            </form>
        </div>

        <h2 style="margin-bottom: 20px;">Partenaires actuels</h2>
        <div class="partner-grid">
            <?php foreach ($partners as $p): ?>
            <div class="partner-admin-item">
                <div class="action-overlay">
                    <a href="?edit=<?php echo $p['id']; ?>" class="btn-act btn-ed">Edit</a>
                    <a href="?delete=<?php echo $p['id']; ?>" class="btn-act btn-del" onclick="return confirm('Supprimer ?')">Del</a>
                </div>
                <img src="../components/images/<?php echo $p['logo_url']; ?>" alt="">
                <div style="font-size: 0.8rem; font-weight: bold;"><?php echo htmlspecialchars($p['name']); ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

</body>
</html>
