<?php

session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . "/H5-mini/Frontend/includes/auth.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/H5-mini/Frontend/includes/links.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/H5-mini/Frontend/includes/tailwind-styling.php");

require_login();

function decode_jwt_payload($jwt)
{
    $parts = explode('.', $jwt);
    if (count($parts) !== 3) return null;
    $payload = $parts[1];
    $payload = str_replace(['-', '_'], ['+', '/'], $payload);
    $payload .= str_repeat('=', (4 - strlen($payload) % 4) % 4);
    return json_decode(base64_decode($payload), true);
}

// Decode the JWT and check for valid token
$decoded = decode_jwt_payload($_SESSION['user_token']);
if (!$decoded) {
    die("Invalid or missing token");
}
$userId = $decoded['http://schemas.xmlsoap.org/ws/2005/05/identity/claims/nameidentifier'] ?? null;
if (!$userId) die("User ID not found in token.");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>DLES - Sorting Data</title>

    <style>
        @import url('/H5-mini/Frontend/User/Wordle/CSS/style.css');
    </style>

</head>

<body class="<?= $wordleBackgroundColor ?>">

    <?php include_once($_SERVER['DOCUMENT_ROOT'] . "/H5-mini/Frontend/templates/header.php"); ?>
    <div class="<?= $defaultCenterAndFixedHeight ?>">
        <h1 class="<?= $sectionHeading ?>">Sorting Data</h1>

        <div class="flex flex-col sm:flex-row gap-4 mt-4">
            <!-- Words -->
            <a href="<?= $baseURL ?>wordleSorter" class="w-full sm:w-auto px-4 py-2 bg-green-600 text-white font-semibold rounded-md hover:bg-green-700 transition">Wordle Sorter</a>
            <a href="<?= $baseURL ?>crosswordleSorter" class="w-full sm:w-auto px-4 py-2 bg-green-800 text-white font-semibold rounded-md hover:bg-green-900 transition">Crosswordle Sorter</a>

            <!-- Game -->
            <a href="<?= $baseURL ?>gamedleSorter" class="w-full sm:w-auto px-4 py-2 bg-orange-600 text-white font-semibold rounded-md hover:bg-orange-700 transition">Gamedle Sorter</a>

            <!-- Characters -->
            <a href="<?= $baseURL ?>loldleSorter" class="w-full sm:w-auto px-4 py-2 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 transition">Loldle Sorter</a>
            <a href="<?= $baseURL ?>smitedleSorter" class="w-full sm:w-auto px-4 py-2 bg-yellow-600 text-white font-semibold rounded-md hover:bg-yellow-700 transition">Smitedle Sorter</a>
        </div>

        <script src="<?= $baseDataURL ?>Words_List/wordsSorter.js"></script>

    </div>
    <?php include_once($_SERVER['DOCUMENT_ROOT'] . "/H5-mini/Frontend/templates/footer.php"); ?>

</body>

</html>