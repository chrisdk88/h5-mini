<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . "/H5-mini/Frontend/includes/auth.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/H5-MINI/Frontend/includes/links.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/H5-MINI/Frontend/includes/tailwind-styling.php");

require_login();

// Initialize error message
$error_message = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['emailOrUsername'];
    $password = $_POST['password'];

    // API URL
    $api_url = $baseAPI . "Users/login";

    // Prepare API request data
    $data = json_encode([
        "username" => $username,
        "password" => $password
    ]);

    // Set up cURL request
    $ch = curl_init($api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json"
    ]);

    // Execute request & get response
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_error = curl_error($ch);
    curl_close($ch);

    // Check if API response is valid JSON
    $result = json_decode($response, true);

    if ($http_code == 200 && isset($result['token'])) {
        // Login successful, store token in session
        $_SESSION['user_token'] = $result['token'];
        header("Location: " . $baseURL . "dashboard");
        exit;
    } else {
        // Handle API errors
        if ($curl_error) {
            $error_message = "Connection error. Please try again. " . $http_code;
        } elseif (isset($result['message'])) {
            $error_message = $result['message'];
        } else {
            $error_message = "Invalid username or password. " . $http_code;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DLES - Gamedle</title>
</head>

<body class="<?= $gamedleBackgroundColor ?>">
    <!-- Header -->
    <?php include_once($_SERVER['DOCUMENT_ROOT'] . "/H5-MINI/Frontend/templates/header.php"); ?>

    <!-- Main -->
    <section>
        <div class="<?= $defaultCenterAndFixedHeight ?>">
            <a href="<?= $baseURL ?>dashboard" class="absolute top-[100px] right-[30px] <?= $redirectedIcon ?>"> <svg
                    class="w-6 h-6 text-black" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 14 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 5H1m0 0 4 4M1 5l4-4" />
                </svg></a>

            <div class="flex flex-row flex-wrap justify-center gap-20">

                <div class="gamedle-daily-section flex flex-col">
                    <h1 class="<?= $sectionHeading ?>">Gamedle - Daily</h1>
                    <a href="<?= $baseURL ?>gamedle-classic" class="<?= $redirectedButton ?>">Classic</a>
                    <a href="<?= $baseURL ?>gamedle-artwork" class="<?= $redirectedButton ?>">Artwork</a>
                    <a href="<?= $baseURL ?>gamedle-character" class="<?= $redirectedButton ?>">Character</a>
                    <a href="<?= $baseURL ?>gamedle-guess" class="<?= $redirectedButton ?> mb-8">Guess</a>
                </div>

                <div class="gamedle-endless-section flex flex-col">
                    <h1 class="<?= $sectionHeading ?>">Gamedle - Endless</h1>
                    <a href="<?= $baseURL ?>gamedle-classicEndless" class="<?= $redirectedButton ?>">Classic Endless</a>
                    <a href="<?= $baseURL ?>gamedle-artworkEndless" class="<?= $redirectedButton ?>">Artwork Endless</a>
                    <a href="<?= $baseURL ?>gamedle-characterEndless" class="<?= $redirectedButton ?>">Character Endless</a>
                    <a href="<?= $baseURL ?>gamedle-guessEndless" class="<?= $redirectedButton ?>">Guess Endless</a>
                    <a href="<?= $baseURL ?>gamedle-multiplayer" class="<?= $redirectedButton ?>">Multiplayer</a>
                </div>

                <a href="<?= $baseURL ?>gamedle-leaderboard" class="<?= $redirectedButton ?>">Leaderboard</a>

            </div>

            <!-- Game rule btn -->
            <button id="openModal"
                class="absolute bottom-[100px] right-[30px] w-10 h-10 rounded-full bg-gray-800 text-white flex items-center justify-center hover:bg-gray-700">
                ?
            </button>

            <!-- Modal -->
            <div id="rulesModal" class="fixed inset-0 bg-black bg-opacity-25 flex items-center justify-center hidden">
                <div class="bg-white p-6 rounded-lg shadow-lg max-w-sm w-full">
                    <h1 class="text-2xl font-bold mb-4 text-left">Game Rules</h1>
                    <h2 class="text-xl font-bold mb-4 text-left">How to Play Gamedle</h2>
                    <ul class="list-disc list-inside text-gray-700 mb-6">
                        <li>Each guess must be a valid five-letter word.</li>
                        <li>The color of a tile will change to show you how close your guess was.</li>
                        <li>If the tile turns green, the letter is in the word and in the correct spot.</li>
                        <li>If the tile turns yellow, the letter is in the word but in the wrong spot.</li>
                        <li>If the tile turns gray, the letter is not in the word.</li>
                    </ul>
                    <div class="text-right">
                        <button id="closeModal" class="mt-2 px-4 py-2 bg-gray-800 text-white rounded hover:bg-gray-700">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include_once($_SERVER['DOCUMENT_ROOT'] . "/H5-MINI/Frontend/templates/footer.php"); ?>

    <!-- Script -->
    <script>
        const openModalButton = document.getElementById('openModal');
        const closeModalButton = document.getElementById('closeModal');
        const modal = document.getElementById('rulesModal');

        openModalButton.addEventListener('click', () => {
            modal.classList.remove('hidden');
        });

        closeModalButton.addEventListener('click', () => {
            modal.classList.add('hidden');
        });

        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.classList.add('hidden');
            }
        });
    </script>
</body>

</html>