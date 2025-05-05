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

$decoded = decode_jwt_payload($_SESSION['user_token']);
$userId = $decoded['http://schemas.xmlsoap.org/ws/2005/05/identity/claims/nameidentifier'] ?? null;
if (!$userId) die("User ID not found in token.");

$basePath = __DIR__ . '/WordsList/Categories';
$categories = array_filter(glob($basePath . '/*'), 'is_dir');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>DLES - Wordle Upload</title>

    <script>
        const userToken = "<?= $_SESSION['user_token'] ?>";
    </script>
</head>

<body>
    <?php include_once($_SERVER['DOCUMENT_ROOT'] . "/H5-mini/Frontend/templates/header.php"); ?>
    <div class="<?= $defaultCenterAndFixedHeight ?>">
        <h1 class="<?= $sectionHeading ?>">Word List Uploader</h1>

        <div class="space-y-4">
            <div>
                <label for="categorySelect" class="block text-sm font-medium text-gray-700">Category</label>
                <select id="categorySelect" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500">
                    <option value="">-- Choose Category --</option>
                    <?php foreach ($categories as $catPath):
                        $catName = basename($catPath);
                        $catId = preg_replace('/[^0-9]/', '', $catName);

                        // Debugging: Log $catName and $catId
                        error_log("Category Name: $catName, Category ID: $catId")
                    ?>
                        <option value="<?= htmlspecialchars($catId) ?>"><?= htmlspecialchars($catName) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label for="fileSelect" class="block text-sm font-medium text-gray-700">File</label>
                <select id="fileSelect" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500"></select>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 mt-4">
                <button onclick="loadTxtFile()" class="w-full sm:w-auto px-4 py-2 bg-purple-600 text-white font-semibold rounded-md hover:bg-purple-700 transition">Load File</button>
                <button onclick="uploadToDatabase()" class="w-full sm:w-auto px-4 py-2 bg-green-600 text-white font-semibold rounded-md hover:bg-green-700 transition">Upload to Database</button>
                <button onclick="logCategoryIds()" class="w-full sm:w-auto px-4 py-2 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 transition">Log Category IDs</button>
            </div>
        </div>

        <div>
            <h3 class="text-lg text-center font-semibold text-gray-800 mt-6">File Content:</h3>
            <pre id="fileContent" class="mt-2 p-4 bg-gray-100 border border-gray-300 rounded-md overflow-x-auto text-sm text-gray-700">No file loaded.</pre>
        </div>

        <script src="/H5-mini/Frontend/UploadWords/JavaScript/fileSelector.js"></script>
        <script src="/H5-mini/Frontend/UploadWords/JavaScript/fileLoader.js"></script>
        <script src="/H5-mini/Frontend/UploadWords/JavaScript/fileUploader.js"></script>
        <script src="/H5-mini/Frontend/UploadWords/JavaScript/fetchCategories.js"></script>

    </div>
    <?php include_once($_SERVER['DOCUMENT_ROOT'] . "/H5-mini/Frontend/templates/footer.php"); ?>
</body>

</html>