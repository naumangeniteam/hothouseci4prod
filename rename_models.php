<?php

$modelsPath = __DIR__ . '/app/Models/'; // Path to models directory

// Get all PHP files in the Models directory
$modelFiles = glob($modelsPath . '*.php');

foreach ($modelFiles as $file) {
    $filename = basename($file, '.php'); // Get filename without extension

    // Convert snake_case to PascalCase (Admin_model -> AdminModel)
    $newFilename = str_replace('_', ' ', $filename); // Convert underscores to spaces
    $newFilename = ucwords($newFilename); // Capitalize words
    $newFilename = str_replace(' ', '', $newFilename) . '.php'; // Remove spaces and append .php

    $newFilePath = $modelsPath . $newFilename;

    // Read file content
    $content = file_get_contents($file);

    // Replace class name inside the file
    $pattern = '/class\s+' . preg_quote($filename, '/') . '\s+/';
    $replacement = 'class ' . basename($newFilename, '.php') . ' ';
    $content = preg_replace($pattern, $replacement, $content);

    // Save the modified content to the new file
    file_put_contents($newFilePath, $content);

    // Delete the old file
    unlink($file);

    echo "Renamed: $file -> $newFilePath\n";
}

echo "✅ All models renamed successfully!\n";
