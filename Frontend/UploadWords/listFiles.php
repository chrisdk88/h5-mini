<?php

$baseDir = __DIR__ . '/WordsList/Categories';

if (!isset($_GET['category'])) {
    http_response_code(400);
    echo json_encode(["error" => "Missing category"]);
    exit;
}

$category = basename($_GET['category']);
$dir = "$baseDir/$category";

// Debugging: Log the directory being accessed
error_log("Accessing directory: $dir");

if (!is_dir($dir)) {
    http_response_code(404);
    echo json_encode(["error" => "Category not found"]);
    exit;
}

$files = glob("$dir/*.txt");
$fileNames = array_map('basename', $files);

// Debugging: Log the files found
error_log("Files found: " . json_encode($fileNames));

header('Content-Type: application/json');
echo json_encode($fileNames);
