<?php
require_once 'auth_check.php';

function testTranslation($text, $target) {
    $url = "https://api.mymemory.translated.net/get?q=" . urlencode($text) . "&langpair=fr|" . $target;
    
    echo "<h3>Testing translation to: $target</h3>";
    echo "URL: <code>$url</code><br><br>";

    // Method 1: cURL
    echo "<strong>Method 1 (cURL):</strong> ";
    if (function_exists('curl_init')) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $res = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);
        
        if ($err) echo "<span style='color:red'>cURL Error: $err</span>";
        else echo "<span style='color:green'>Success!</span><br><pre>" . print_r(json_decode($res, true), true) . "</pre>";
    } else {
        echo "<span style='color:orange'>cURL extension not enabled.</span><br>";
    }

    // Method 2: file_get_contents
    echo "<br><strong>Method 2 (file_get_contents):</strong> ";
    $ctx = stream_context_create(["ssl" => ["verify_peer" => false, "verify_peer_name" => false], "http" => ["timeout" => 10]]);
    $res = @file_get_contents($url, false, $ctx);
    
    if ($res === false) {
        echo "<span style='color:red'>Failed to connect. Check if 'extension=openssl' is enabled in php.ini</span>";
    } else {
        echo "<span style='color:green'>Success!</span><br><pre>" . print_r(json_decode($res, true), true) . "</pre>";
    }
    echo "<hr>";
}

?>
<!DOCTYPE html>
<html>
<head><title>Translation Diagnostic</title></head>
<body style="font-family: monospace; padding: 20px;">
    <h1>Diagnostic de Traduction</h1>
    <?php 
    testTranslation("Bonjour tout le monde", "en"); 
    testTranslation("Bienvenue à l'école", "ar");
    ?>
</body>
</html>
