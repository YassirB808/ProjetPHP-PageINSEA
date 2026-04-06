<?php
session_start();
require_once '../components/PHP/db_connect.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // For now, using the requested admin/admin credentials
    // Later we can use the database users table
    if ($username === 'admin' && $password === 'admin') {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_user'] = $username;
        header('Location: index.php');
        exit;
    } else {
        $error = 'Identifiants incorrects.';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Administration - INSEA</title>
    <link rel="stylesheet" href="../components/CSS/style.css">
    <style>
        body { 
            background: linear-gradient(135deg, var(--insea-green) 0%, var(--insea-green-dark) 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        .login-card img {
            height: 80px;
            margin-bottom: 20px;
        }
        .login-card h2 {
            margin-bottom: 30px;
            color: var(--gray-800);
            font-weight: 800;
        }
        .error-msg {
            background: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <img src="../components/images/logos/insea_logo.png" alt="INSEA">
        <h2>Admin Portal</h2>
        
        <?php if ($error): ?>
            <div class="error-msg"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="" method="POST" class="form-grid" style="display: flex; flex-direction: column; gap: 20px;">
            <div style="text-align: left;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600;">Utilisateur</label>
                <input type="text" name="username" required placeholder="admin" style="width: 100%; box-sizing: border-box; padding: 12px; border: 1px solid var(--gray-300); border-radius: 8px;">
            </div>
            <div style="text-align: left;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600;">Mot de passe</label>
                <input type="password" name="password" required placeholder="••••••••" style="width: 100%; box-sizing: border-box; padding: 12px; border: 1px solid var(--gray-300); border-radius: 8px;">
            </div>
            <button type="submit" class="btn-form-submit" style="width: 100%; margin-top: 10px;">Se Connecter</button>
        </form>
        
        <p style="margin-top: 20px; font-size: 0.8rem; color: var(--gray-500);">
            Accès réservé au personnel autorisé de l'INSEA.
        </p>
    </div>

</body>
</html>
