<?php
/**
 * Robust Translation Utility
 * Handles long text by splitting into chunks to respect API limits.
 */

function autoTranslate($text, $source = 'fr', $target = 'en') {
    if (empty($text)) return '';
    
    // If text is short, translate directly
    // Use &html=1 to tell MyMemory we are sending HTML
    if (mb_strlen($text) <= 450) {
        return fetchTranslation($text, $source, $target);
    }

    // For long text, split by sentences or chunks to respect the 500 char limit of MyMemory
    // We try to split by periods to keep sentences intact
    $sentences = preg_split('/(?<=[.?!])\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);
    $translated_full = '';
    $current_chunk = '';

    foreach ($sentences as $sentence) {
        if (mb_strlen($current_chunk . $sentence) < 450) {
            $current_chunk .= ' ' . $sentence;
        } else {
            // Translate current chunk
            if (!empty($current_chunk)) {
                $translated_full .= fetchTranslation($current_chunk, $source, $target) . ' ';
                usleep(200000); // 200ms delay
            }
            // Start new chunk
            $current_chunk = $sentence;
            
            // If a single sentence is still > 450, force split it
            if (mb_strlen($current_chunk) >= 450) {
                $parts = mb_str_split($current_chunk, 400);
                foreach ($parts as $p) {
                    $translated_full .= fetchTranslation($p, $source, $target);
                }
                $current_chunk = '';
            }
        }
    }

    // Final chunk
    if (!empty($current_chunk)) {
        $translated_full .= fetchTranslation($current_chunk, $source, $target);
    }

    return trim($translated_full);
}

/**
 * Low-level API call to MyMemory
 */
function fetchTranslation($text, $source, $target) {
    if (empty(trim(strip_tags($text)))) return $text; // Don't translate if it's just tags or empty
    
    // Added &html=1 to preserve HTML structure where possible
    $url = "https://api.mymemory.translated.net/get?q=" . urlencode($text) . "&langpair=" . $source . "|" . $target . "&html=1";

    $response = false;
    if (function_exists('curl_init')) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $response = curl_exec($ch);
        curl_close($ch);
    } else {
        $ctx = stream_context_create(["ssl" => ["verify_peer" => false], "http" => ["timeout" => 10]]);
        $response = @file_get_contents($url, false, $ctx);
    }

    if ($response) {
        $json = json_decode($response, true);
        if (isset($json['responseData']['translatedText']) && !empty($json['responseData']['translatedText'])) {
            $trans = $json['responseData']['translatedText'];
            // MyMemory sometimes returns error messages in the translatedText field
            if (strpos($trans, 'MYMEMORY') === false && 
                strpos($trans, 'LIMIT EXCEEDED') === false) {
                // Decode entities because we WANT the actual < > tags in our DB
                return html_entity_decode($trans, ENT_QUOTES, 'UTF-8');
            }
        }
    }
    
    return $text; // Return original if translation fails
}

/**
 * Helper for PHP versions without mb_str_split
 */
if (!function_exists('mb_str_split')) {
    function mb_str_split($string, $split_length = 1) {
        if ($split_length <= 0) return false;
        $array = [];
        $strlen = mb_strlen($string);
        for ($i = 0; $i < $strlen; $i += $split_length) {
            $array[] = mb_substr($string, $i, $split_length);
        }
        return $array;
    }
}
?>
