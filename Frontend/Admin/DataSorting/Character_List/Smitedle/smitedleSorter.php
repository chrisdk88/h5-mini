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
    <title>DLES - Smitedle Sorting</title>
</head>

<body class="<?= $wordleBackgroundColor ?>">

    <?php include_once($_SERVER['DOCUMENT_ROOT'] . "/H5-mini/Frontend/templates/header.php"); ?>
    <div class="<?= $defaultCenterAndFixedHeight ?>">

        <!-- Back btn -->
        <a href="<?= $baseURL ?>sortingPage" class="absolute top-[100px] right-[30px] <?= $redirectedIcon ?> hidden md:block"> <svg
                class="w-6 h-6 text-gray-800 dark:text-black" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                fill="none" viewBox="0 0 14 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 5H1m0 0 4 4M1 5l4-4" />
            </svg>
        </a>

        <h1 class="<?= $sectionHeading ?>">Smitedle Data Sorter</h1>

        <div class="space-y-4">
            <div>
                <label for="fileSelect1" class="block text-sm font-medium text-gray-700">File 1</label>
                <select id="fileSelect1" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500">
                    <option value="">-- Choose File 1 --</option>
                    <?php foreach ($fileegories as $filePath):
                        $fileName = basename($filePath);
                        $fileId = preg_replace('/[^0-9]/', '', $fileName);

                        // Debugging: Log $fileName and $fileId
                        error_log("File 1 Name: $fileName, File 1 ID: $fileId")
                    ?>
                        <option value="<?= htmlspecialchars($fileId) ?>"><?= htmlspecialchars($fileName) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label for="fileSelect1" class="block text-sm font-medium text-gray-700">File 1</label>
                <select id="fileSelect1" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500"></select>
            </div>

            <div>
                <label for="fileSelect2" class="block text-sm font-medium text-gray-700">File 2</label>
                <select id="fileSelect2" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500">
                    <option value="">-- Choose File 2 --</option>
                    <?php foreach ($fileegories as $filePath):
                        $fileName = basename($filePath);
                        $fileId = preg_replace('/[^0-9]/', '', $fileName);

                        // Debugging: Log $fileName and $fileId
                        error_log("File 1 Name: $fileName, File 1 ID: $fileId")
                    ?>
                        <option value="<?= htmlspecialchars($fileId) ?>"><?= htmlspecialchars($fileName) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label for="fileSelect2" class="block text-sm font-medium text-gray-700">File 2</label>
                <select id="fileSelect2" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500"></select>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 mt-4">
                <button onclick="loadFiles()" class="w-full sm:w-auto px-4 py-2 bg-purple-600 text-white font-semibold rounded-md hover:bg-purple-700 transition">Load Files</button>
                <button onclick="sortData()" class="w-full sm:w-auto px-4 py-2 bg-green-600 text-white font-semibold rounded-md hover:bg-green-700 transition">Sort Data</button>
            </div>
        </div>

        <div>
            <h3 class="text-lg text-center font-semibold text-black-800 mt-6">File 1 Content:</h3>
            <pre id="file1Content" class="mt-2 p-4 bg-gray-100 border border-gray-300 rounded-md overflow-x-auto text-sm text-gray-700">No file loaded.</pre>
        </div>

        <div>
            <h3 class="text-lg text-center font-semibold text-black-800 mt-6">File 2 Content:</h3>
            <pre id="file2Content" class="mt-2 p-4 bg-gray-100 border border-gray-300 rounded-md overflow-x-auto text-sm text-gray-700">No file loaded.</pre>
        </div>

        <div>
            <h3 class="text-lg text-center font-semibold text-black-800 mt-6">File Sorted Content:</h3>
            <pre id="fileSortedContent" class="mt-2 p-4 bg-gray-100 border border-gray-300 rounded-md overflow-x-auto text-sm text-gray-700">No file loaded.</pre>
        </div>

    </div>
    <?php include_once($_SERVER['DOCUMENT_ROOT'] . "/H5-mini/Frontend/templates/footer.php"); ?>

</body>

</html>