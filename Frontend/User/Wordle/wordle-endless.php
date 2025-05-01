<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . "/H5-mini/Frontend/includes/auth.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/H5-mini/Frontend/includes/links.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/H5-mini/Frontend/includes/tailwind-styling.php");

require_login();

function decode_jwt_payload($jwt) {
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

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>DLES - Wordle-endless</title>
</head>

<body class="<?= $wordleBackgroundColor ?>">

  <?php include_once($_SERVER['DOCUMENT_ROOT'] . "/H5-mini/Frontend/templates/header.php"); ?>

  <section>
    <div class="<?= $defaultCenterAndFixedHeight ?>">
      <!-- Back btn -->
      <a href="<?= $baseURL ?>wordle" class="absolute top-[100px] right-[30px] <?= $redirectedIcon ?>"> <svg
          class="w-6 h-6 text-gray-800 dark:text-black" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
          fill="none" viewBox="0 0 14 10">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M13 5H1m0 0 4 4M1 5l4-4" />
        </svg>
      </a>

      <div class="<?= $sectionHeading ?>">Wordle</div>

      <div>MADS</div>

      <!-- Game rule btn -->
      <button id="openModal"
        class="absolute bottom-[100px] right-[30px] w-10 h-10 rounded-full bg-gray-800 text-white flex items-center justify-center hover:bg-gray-700">
        ?
      </button>

      <!-- Modal -->
      <div id="rulesModal" class="fixed inset-0 bg-black bg-opacity-25 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-sm w-full">
          <h1 class="text-2xl font-bold mb-4 text-left">Game Rules</h1>
          <h2 class="text-xl font-bold mb-4 text-left">How to Play Wordle</h2>
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

  <?php include_once($_SERVER['DOCUMENT_ROOT'] . "/H5-mini/Frontend/templates/footer.php"); ?>

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