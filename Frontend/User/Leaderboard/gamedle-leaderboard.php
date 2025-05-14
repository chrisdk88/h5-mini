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
    <title>DLES - Gamedle-Leaderboard</title>

    <style>
        @import url('/H5-mini/Frontend/User/Leaderboard/CSS/style.css');
    </style>

</head>

<body class="<?= $gamedleBackgroundColor ?>">

    <?php include_once($_SERVER['DOCUMENT_ROOT'] . "/H5-mini/Frontend/templates/header.php"); ?>

    <section>

        <div class="<?= $defaultCenterAndFixedHeight ?>">

            <!-- Back btn -->
            <a href="<?= $baseURL ?>gamedle" class="absolute top-[100px] right-[30px] <?= $redirectedIcon ?> hidden md:block"> <svg
                    class="w-6 h-6 text-gray-800 dark:text-black" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                    fill="none" viewBox="0 0 14 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 5H1m0 0 4 4M1 5l4-4" />
                </svg>
            </a>

            <h1 class="<?= $sectionHeading ?>">Gamedle Leaderboard</h1>

            <content>

                <main id="leaderboard-container-Gamedle">

                    <!-- Centered Game Column -->
                    <div class="flex flex-col items-center">


                        <!-- Leaderboard -->
                        <div id="leaderboard" style="display: flex; gap: 2rem;">

                            <!-- Singleplayer Section -->
                            <div class="leaderboard-section">

                                <div class="leaderboard-title">Singleplayer</div>

                                <div class="leaderboard-tabs">
                                    <button class="active">Daily</button>
                                    <button>Weekly</button>
                                    <button>Monthly</button>
                                    <button>All Time</button>
                                </div>

                                <div class="leaderboard-box" id="singleplayer-content">
                                    <!-- Inject player entries here -->
                                    <div class="leaderboard-entry">
                                        <span>Position: </span>
                                        <span>Pts: </span>
                                    </div>
                                </div>
                                <button class="load-more">Load More</button>
                            </div>

                            <!-- Multiplayer Section -->
                            <div class="leaderboard-section">
                                <div class="leaderboard-title">Multiplayer</div>

                                <div class="leaderboard-tabs">
                                    <button class="active">Daily</button>
                                    <button>Weekly</button>
                                    <button>Monthly</button>
                                    <button>All Time</button>
                                </div>

                                <div class="leaderboard-box" id="multiplayer-content">
                                    <!-- Inject player entries here -->
                                </div>
                                <button class="load-more">Load More</button>
                            </div>
                        </div>

                    </div>
                </main>

            </content>

    </section>

    <?php include_once($_SERVER['DOCUMENT_ROOT'] . "/H5-mini/Frontend/templates/footer.php"); ?>

    <!-- Leaderboard Script -->¨
    <script>
        const singleplayerContainer = document.getElementById("singleplayer-content");
        const multiplayerContainer = document.getElementById("multiplayer-content");

        const singleplayerTabs = document.querySelectorAll(".leaderboard-section:nth-child(1) .leaderboard-tabs button");
        const multiplayerTabs = document.querySelectorAll(".leaderboard-section:nth-child(2) .leaderboard-tabs button");

        singleplayerTabs.forEach(button => {
            button.addEventListener("click", () => {
                const type = button.textContent.trim().toLowerCase();
                setActiveTab(singleplayerTabs, button);
                if (leaderboardData) renderLeaderboard(type, singleplayerContainer, leaderboardData[type]);
            });
        });

        multiplayerTabs.forEach(button => {
            button.addEventListener("click", () => {
                const type = button.textContent.trim().toLowerCase();
                setActiveTab(multiplayerTabs, button);
                if (leaderboardData) {
                    const multiplayerOnly = leaderboardData[type]?.filter(entry => entry.username.includes("[MP]"));
                    renderLeaderboard(type, multiplayerContainer, multiplayerOnly);
                }
            });
        });
    </script>

    <!-- Token -->
    <script>
        const userToken = "<?php echo $_SESSION['user_token'] ?? ''; ?>"; // Ensure the token is passed to JavaScript
        localStorage.setItem("jwt_token", userToken); // Store the token in localStorage
    </script>

    <!-- User ID -->
    <script>
        const userId = "<?php echo $userId; ?>"; // Pass the userId from PHP to JavaScript
        localStorage.setItem("user_id", userId); // Store userId in localStorage for use in JS
    </script>

    <script src="/H5-mini/Frontend/User/Leaderboard/JavaScript/gamedle-leaderboard.js"></script>

</body>

</html>