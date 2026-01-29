<?php
$ds = DIRECTORY_SEPARATOR;
$storeFolder = 'uploads';

if (!empty($_FILES)) {
    $tempFile = $_FILES['file']['tmp_name'];

    // Ensure the uploads folder exists
    $targetPath = dirname(__FILE__) . $ds . $storeFolder . $ds;
    if (!is_dir($targetPath)) {
        mkdir($targetPath, 0755, true);
    }

    // Get original filename and sanitize it
    $originalName = $_FILES['file']['name'];
    $filename = basename($originalName); // remove any directory path
    $filename = preg_replace("/[^A-Za-z0-9_\-\.]/", '_', $filename);

    $filename = uniqid() . '_' . $filename;

    $targetFile = $targetPath . $filename;

    // Move uploaded file safely
    if (move_uploaded_file($tempFile, $targetFile)) {
        echo json_encode(['success' => true, 'file' => $filename]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to move uploaded file.']);
    }
}
?>
