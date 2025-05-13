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
  <title>DLES - Wordle-endless</title>

  <style>
    @import url('/H5-mini/Frontend/User/Wordle/CSS/style.css');
  </style>

</head>

<body class="<?= $wordleBackgroundColor ?>">

  <?php include_once($_SERVER['DOCUMENT_ROOT'] . "/H5-mini/Frontend/templates/header.php"); ?>

  <section>

    <div class="<?= $defaultCenterAndFixedHeight ?>">

      <!-- Back btn -->
      <a href="<?= $baseURL ?>wordle" class="absolute top-[100px] right-[30px] <?= $redirectedIcon ?> hidden md:block"> <svg
          class="w-6 h-6 text-gray-800 dark:text-black" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
          fill="none" viewBox="0 0 14 10">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M13 5H1m0 0 4 4M1 5l4-4" />
        </svg>
      </a>

      <h1 class="<?= $sectionHeading ?>">Wordle Endless</h1>

      <content>

        <!-- Streak Box -->
        <div class="absolute 2xl:left-138 xl:left-65 lg:left-32 md:left-0 top-55 ml-4">
          <div id="streaks" class="border border-gray-300 rounded-lg p-4 shadow-md bg-white hidden md:block">
            <h3 class="text-xl font-bold mb-2">Streaks</h3>
            <p>Current Streak: <span id="current-streak">0</span></p>
            <p>Highest Streak: <span id="highest-streak">0</span></p>
          </div>
        </div>


        <main id="game-container-Wordle">

          <!-- Centered Game Column -->
          <div class="flex flex-col items-center">

            <!-- Start Game Button -->
            <button id="start-game-button" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-500">
              Start Game
            </button>

            <!-- Timer Box -->
            <div id="timer" style="display:none" class="w-100 mb-4 px-2 py-2 border border-gray-300 rounded-lg shadow bg-white text-xl font-mono">
              <div class="text-center"> <span id="time-left">01:40</span> sec </div>
            </div>

            <!-- Game Board -->
            <div id="game" style="display: none">
              <div id="board"></div>
              <div id="keyboard"></div>
            </div>
          </div>
        </main>

      </content>

      <!-- Game rule btn -->
      <button id="openModal"
        class="absolute bottom-[100px] right-[30px] w-10 h-10 rounded-full bg-gray-800 text-white flex items-center justify-center hover:bg-gray-700">
        ?
      </button>

      <!-- Game Modal -->
      <div id="rulesModal" class="fixed inset-0 bg-black bg-opacity-25 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-sm w-full">
          <h1 class="text-2xl font-bold mb-4 text-left">Game Rules</h1>
          <h2 class="text-xl font-bold mb-4 text-left">How to Play Wordle</h2>
          <ul class="list-disc list-inside text-gray-700 mb-6">

            <li>Each guess must be a valid five-letter word.</li>

            <li class="mt-4">The color of a tile will change to show you how close your guess was.</li>

            <li class="mt-4">If the tile turns:
              <ul class="list-disc list-inside ml-6">
                <li class="mt-2">Green, the letter is in the word and in the correct spot.</li>
                <li class="mt-2">Yellow, the letter is in the word but in the wrong spot.</li>
                <li class="mt-2">Gray, the letter is not in the word.</li>
              </ul>
            </li>
          </ul>
          <div class="text-right">
            <button id="closeModal" class="mt-2 px-4 py-2 bg-gray-800 text-white rounded hover:bg-gray-700">
              Close
            </button>
          </div>
        </div>
      </div>

      <!-- Points & EXP Modal -->
      <div id="points-exp-modal" class="fixed inset-0 bg-black bg-opacity-25 flex items-center justify-center hidden z-50">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-sm w-full relative">
          <h2 class="text-xl font-bold mb-4">Points & EXP Earned</h2>

          <p class="text-gray-700 mb-2">Points: <span id="points-earned" class="font-semibold">0</span></p>
          <p class="text-gray-700">EXP: <span id="exp-earned" class="font-semibold">0</span></p>

          <div class="text-right">
            <button id="endGame-button" class="mt-2 px-4 py-2 bg-gray-800 text-white rounded hover:bg-gray-700">
              End
            </button>

            <button id="continueGame-button" class="mt-2 px-4 py-2 bg-gray-800 text-white rounded hover:bg-gray-700">
              Continue
            </button>
          </div>
        </div>
      </div>

  </section>

  <?php include_once($_SERVER['DOCUMENT_ROOT'] . "/H5-mini/Frontend/templates/footer.php"); ?>

  <!-- Modal Script -->
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

  <!-- Points & EXP Modal Script -->
  <script>
    const pointsExpModal = document.getElementById('points-exp-modal');

    // Close modal if clicking outside the modal content
    pointsExpModal.addEventListener('click', (e) => {
      if (e.target === pointsExpModal) {
        pointsExpModal.classList.add('hidden');
      }
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

  <!-- Game Start Script -->
  <script>
    const startGameButton = document.getElementById('start-game-button');
    const timerElement = document.getElementById('timer');

    // Function to start the game
    async function startGame() {
      startGameButton.style.display = 'none';
      timerElement.style.display = 'block';
      await startEndlessGame();
    }

    // Event listener to start the game when the button is clicked
    startGameButton.addEventListener('click', startGame);
  </script>

  <!-- Game End or Continue Modal Script -->
  <script>
    // Show the points/exp modal with buttons for "Continue" and "End Game"
    const continueButton = document.getElementById("continueGame-button");
    const endGameButton = document.getElementById("endGame-button");

    // Add event listeners for the buttons
    continueButton.addEventListener("click", () => {
      resetGame();
      pointsExpModal.classList.add("hidden"); // Hide the modal
    });

    endGameButton.addEventListener("click", () => {
      endGame();
      pointsExpModal.classList.add("hidden"); // Hide the modal
    });
  </script>

  <script src="/H5-mini/Frontend/User/Wordle/JavaScript/wordle-endless.js"></script>

</body>

</html>