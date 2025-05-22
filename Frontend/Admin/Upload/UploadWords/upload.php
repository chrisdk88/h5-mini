<?php

session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . "/H5-mini/Frontend/includes/auth.php");
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['message' => 'Method not allowed.']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['category'], $data['fileName'], $data['fileContent'])) {
    http_response_code(400);
    echo json_encode(['message' => 'Invalid input.']);
    exit;
}

$category = basename($data['category']);
$fileName = basename($data['fileName']);
$fileContent = $data['fileContent'];

$baseDir = __DIR__ . "/UploadWords/WordsList/Categories/$category";
if (!is_dir($baseDir)) {
    http_response_code(404);
    echo json_encode(['message' => 'Category not found.']);
    exit;
}

// Example: Save to database
include_once($_SERVER['DOCUMENT_ROOT'] . "/H5-mini/Backend/db.php");

$stmt = $db->prepare("INSERT INTO word_files (category, file_name, content) VALUES (?, ?, ?)");
if ($stmt->execute([$category, $fileName, $fileContent])) {
    echo json_encode(['message' => 'File uploaded successfully.']);
} else {
    http_response_code(500);
    echo json_encode(['message' => 'Failed to upload the file.']);
}