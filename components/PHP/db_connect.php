<?php
// Database connection configuration
$host = '127.0.0.1';
$port = '3330';
$db   = 'insea_db';
$user = 'root'; // Change this to your database username
$pass = '';     // Change this to your database password
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

/**
 * Helper function to get the language ID from the code
 */
function get_language_id($pdo, $lang_code) {
    $stmt = $pdo->prepare("SELECT id FROM languages WHERE code = ?");
    $stmt->execute([$lang_code]);
    return $stmt->fetchColumn();
}
?>
