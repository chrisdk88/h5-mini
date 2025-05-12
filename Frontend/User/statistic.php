<?php
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
$username = $decoded['name'] ?? 'Unknown';

if (!$userId) die("User ID not found in token.");

$selectedGame = strtolower($_GET['game'] ?? '');
$allowedGames = ['wordle', 'loldle', 'crosswordle'];

if (!in_array($selectedGame, $allowedGames)) {
    die("Invalid or missing game type.");
}

$gameName = ucfirst($selectedGame);

// Fetch leaderboard data
$leaderboardUrl = $baseAPI . "Leaderboards";
$ch = curl_init($leaderboardUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Bearer " . $_SESSION['user_token']]);
$leaderboardResponse = curl_exec($ch);
curl_close($ch);
$leaderboardData = json_decode($leaderboardResponse, true);

$placement = 'N/A';
$exp = 0;

if (isset($leaderboardData['allTime']) && is_array($leaderboardData['allTime'])) {
  foreach ($leaderboardData['allTime'] as $entry) {
    if (($entry['username'] ?? '') === $username) {
      $placement = $entry['position'] ?? 'N/A';
      $exp = $entry['totalScore'] ?? 0;
      break;
    }
  }
}

// Fetch user game statistics
$statsUrl = $baseAPI . "Scores/usersScoreSummary/$userId";
$ch = curl_init($statsUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Bearer " . $_SESSION['user_token']]);
$statsResponse = curl_exec($ch);
curl_close($ch);

$decodedStats = json_decode($statsResponse, true);
$stats = [];

$selectedGame = strtolower($_GET['game'] ?? '');

if (isset($decodedStats[$selectedGame]) && is_array($decodedStats[$selectedGame])) {
  foreach ($decodedStats[$selectedGame] as $s) {
    $stats[] = [
      "entryLabel" => $s['word'] ?? $s['champion'] ?? '-',
      "attempts" => ($s['attempts'] ?? '-') . "/6",
      "type" => $s['game_mode'] ?? '-',
      "points" => $s['points'] ?? 0,
      "time" => $s['game_time'] ?? '-'
    ];
  }
}


$initialLimit = 10;
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>DLES - Game Statistics</title>
  <script>
  let showLimit = <?= $initialLimit ?>;

  function loadMore() {
    const entries = document.querySelectorAll(".stat-entry");
    let shown = 0;
    for (let i = 0; i < entries.length && shown < 10; i++) {
      if (entries[i].style.display === "none") {
        entries[i].style.display = "flex";
        shown++;
      }
    }
    const hiddenLeft = [...entries].some(e => e.style.display === "none");
    if (!hiddenLeft) {
      document.getElementById("loadMoreBtn").style.display = "none";
    }
  }
  </script>
</head>

<body class="<?= $defaultBackgroundColor ?>">
  <?php include_once($_SERVER['DOCUMENT_ROOT'] . "/H5-mini/Frontend/templates/header.php"); ?>

  <section class="<?= $defaultCenterAndFixedHeight ?>">
    <h2 class="<?= $sectionHeading ?>"><?= htmlspecialchars($gameName) ?>: Statistics</h2>
    <div class="bg-black text-white p-4 rounded-lg max-w-4xl mx-auto min-w-[600px]">
      <div class="bg-gray-300 text-black flex justify-between px-6 py-2 rounded-lg font-semibold">
        <span><?= $gameName ?>:</span>
        <span>Score: <?= $exp ?></span>
        <span>Placement: <?= $placement ?><?= is_numeric($placement) ? 'th' : '' ?></span>
      </div>

      <div class="bg-gray-400 p-4 mt-4 rounded-md space-y-2">
        <?php if (count($stats) === 0): ?>
        <p class="text-center text-black">No statistics available for this game.</p>
        <?php endif; ?>

        <?php foreach ($stats as $index => $entry): ?>
        <div class="bg-black text-white px-4 py-2 rounded flex flex-wrap justify-between items-center stat-entry"
          style="<?= $index >= $initialLimit ? 'display: none;' : 'display: flex;' ?>">
          <span><?= ucfirst($selectedGame) === 'Loldle' ? 'Champion' : 'Word' ?>:&nbsp;<?= htmlspecialchars($entry['entryLabel']) ?>&nbsp;|&nbsp;</span>
          <span>Attempts:&nbsp;<?= $entry['attempts'] ?>&nbsp;|&nbsp;</span>
          <span>Game Mode:&nbsp;<?= $entry['type'] ?>&nbsp;|&nbsp;</span>
          <span>Score:&nbsp;<?= $entry['points'] ?>&nbsp;|&nbsp;</span>
          <span>Time:&nbsp;<?= $entry['time'] ?></span>
        </div>
        <?php endforeach; ?>
      </div>

      <?php if (count($stats) > $initialLimit): ?>
      <div class="text-center mt-4">
        <button id="loadMoreBtn" onclick="loadMore()"
          class="bg-white text-black px-4 py-1 rounded hover:bg-gray-100">Load More</button>
      </div>
      <?php endif; ?>
    </div>
  </section>

  <?php include_once($_SERVER['DOCUMENT_ROOT'] . "/H5-mini/Frontend/templates/footer.php"); ?>
</body>

</html>