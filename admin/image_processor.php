<?php
/**
 * Image Processor Utility
 * Normalizes uploaded images to save space and ensure uniformity.
 */

function processAndSaveImage($file_temp, $target_dir, $prefix = 'img', $max_width = 1200) {
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $extension = strtolower(pathinfo($file_temp, PATHINFO_EXTENSION));
    $filename = $prefix . "_" . time() . "_" . bin2hex(random_bytes(4)) . "." . $extension;
    $target_path = $target_dir . $filename;

    // FALLBACK: If GD library is not installed, just move the file and return
    if (!function_exists('imagecreatefromjpeg')) {
        if (move_uploaded_file($file_temp, $target_path)) {
            return $filename;
        }
        return false;
    }

    // Get original dimensions
    list($width, $height, $type) = getimagesize($file_temp);

    // Create image resource based on type
    switch ($type) {
        case IMAGETYPE_JPEG: $src = imagecreatefromjpeg($file_temp); break;
        case IMAGETYPE_PNG:  $src = imagecreatefrompng($file_temp); break;
        case IMAGETYPE_GIF:  $src = imagecreatefromgif($file_temp); break;
        case IMAGETYPE_WEBP: $src = imagecreatefromwebp($file_temp); break;
        default: return false; // Unsupported type
    }

    // Calculate new dimensions (maintain aspect ratio)
    if ($width > $max_width) {
        $new_width = $max_width;
        $new_height = floor($height * ($max_width / $width));
    } else {
        $new_width = $width;
        $new_height = $height;
    }

    // Create new true color image
    $dst = imagecreatetruecolor($new_width, $new_height);

    // Handle transparency for PNG/GIF (convert to white background for JPG)
    $white = imagecolorallocate($dst, 255, 255, 255);
    imagefill($dst, 0, 0, $white);

    // Resize
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

    // Save as JPEG with 80% quality (Good balance between size and quality)
    imagejpeg($dst, $target_path, 80);

    // Free memory
    imagedestroy($src);
    imagedestroy($dst);

    return $filename;
}
?>
