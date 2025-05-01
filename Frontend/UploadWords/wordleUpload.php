<?php
// wordleUpload.php

$baseDir = __DIR__ . '/WordsList/Categories/';

if (isset($_GET['category']) && isset($_GET['filename'])) {
    $category = basename($_GET['category']);
    $filename = basename($_GET['filename']);

    $filePath = $baseDir . $category . '/' . $filename;

    if (file_exists($filePath) && pathinfo($filePath, PATHINFO_EXTENSION) === 'txt') {
        header('Content-Type: text/plain');
        echo file_get_contents($filePath);
    } else {
        http_response_code(404);
        echo 'File not found.';
    }
} else {
    http_response_code(400);
    echo 'Missing parameters.';
}
