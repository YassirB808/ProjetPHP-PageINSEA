<?php
/**
 * Robust Translation Utility
 * Simple version to prevent hanging issues.
 */

function autoTranslate($text, $source = 'fr', $target = 'en') {
    if (empty($text)) return '';
    
    // Safety: limit length to prevent API errors
    if (function_exists('mb_substr')) {
        $text = mb_substr($text, 0, 1000);
    } else {
        $text = substr($text, 0, 1000);
    }
    
    $url = "https://api.mymemory.translated.net/get?q=" . urlencode($text) . "&langpair=" . $source . "|" . $target;

    $response = false;

    if (function_exists('curl_init')) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $response = curl_exec($ch);
    } 
    else {
        $ctx = stream_context_create(["ssl" => ["verify_peer" => false], "http" => ["timeout" => 10]]);
        $response = @file_get_contents($url, false, $ctx);
    }

    if ($response) {
        $json = json_decode($response, true);
        if (isset($json['responseData']['translatedText']) && !empty($json['responseData']['translatedText'])) {
            if (strpos($json['responseData']['translatedText'], 'MYMEMORY') === false) {
                return html_entity_decode($json['responseData']['translatedText'], ENT_QUOTES, 'UTF-8');
            }
        }
    }
    
    return $text; 
}
?>
