<?php

$cat = basename($_GET['category'] ?? '');
$base = __DIR__ . '/WordsList/Categories/' . $cat;

$files = [];
if (is_dir($base)) {
    foreach (glob("$base/*.txt") as $f) {
        $files[] = basename($f);
    }
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode($files);
