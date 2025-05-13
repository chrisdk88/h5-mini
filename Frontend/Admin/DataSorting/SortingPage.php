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
</head>

<body class="<?= $wordleBackgroundColor ?>">

    <?php include_once($_SERVER['DOCUMENT_ROOT'] . "/H5-mini/Frontend/templates/header.php"); ?>
    <div class="<?= $defaultCenterAndFixedHeight ?>">

        <!-- Back btn -->
        <a href="<?= $baseURL ?>adminPage" class="absolute top-[100px] right-[30px] <?= $redirectedIcon ?> hidden md:block"> <svg
                class="w-6 h-6 text-gray-800 dark:text-black" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                fill="none" viewBox="0 0 14 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 5H1m0 0 4 4M1 5l4-4" />
            </svg>
        </a>

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

    </div>
    <?php include_once($_SERVER['DOCUMENT_ROOT'] . "/H5-mini/Frontend/templates/footer.php"); ?>

</body>

</html>