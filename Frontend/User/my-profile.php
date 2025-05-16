<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/H5-mini/Frontend/includes/auth.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/H5-mini/Frontend/includes/links.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/H5-mini/Frontend/includes/tailwind-styling.php");

// Redirect if not logged in
require_login();

// Decode JWT token to get user ID
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

if (!$userId) {
    die("Unable to retrieve user ID from token.");
}

$username = $decoded['name'] ?? 'Unknown';

// Fetch XP and level data
$exp_url = $baseAPI . "Users/GetUsersExpAndLevel/$userId";
$ch = curl_init($exp_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer " . $_SESSION['user_token']
]);
$exp_response = curl_exec($ch);
curl_close($ch);
$expData = json_decode($exp_response, true);

$level = $expData['level'] ?? 0;
$currentLevelExp = $expData['currentLevelExp'] ?? 0;
$expToNext = $expData['expToNextLevel'] ?? 100;
$progress = $expData['progressPercentage'] ?? 0;

// Profile Image logic
$profileImagePath = "/H5-mini/Frontend/Profile-images/{$userId}.jpg";
$absolutePath = $_SERVER['DOCUMENT_ROOT'] . $profileImagePath;
$hasImage = file_exists($absolutePath);
$profileImageToShow = $hasImage
  ? $profileImagePath
  : $baseURL . "Profile-images/person.png";

// Fetch leaderboard data
$leaderboard_url = $baseAPI . "Leaderboards";
$ch = curl_init($leaderboard_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
  "Authorization: Bearer " . $_SESSION['user_token']
]);
$leaderboard_response = curl_exec($ch);
curl_close($ch);
$leaderboards = json_decode($leaderboard_response, true);

$wordleData = ['score' => 0, 'position' => 'N/A'];
if (isset($leaderboards['allTime']) && is_array($leaderboards['allTime'])) {
    foreach ($leaderboards['allTime'] as $entry) {
        if (($entry['username'] ?? '') === $username) {
            $wordleData = [
                'score' => $entry['totalScore'] ?? 0,
                'position' => isset($entry['position']) ? $entry['position'] . 'th' : 'N/A'
            ];
            break;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>DLES - My Profile</title>
</head>

<body class="<?= $defaultBackgroundColor ?>">

  <?php include_once($_SERVER['DOCUMENT_ROOT'] . "/H5-mini/Frontend/templates/header.php"); ?>

  <section class="<?= $defaultCenterAndFixedHeight ?> py-8 px-4">
    <h2 class="<?= $sectionHeading ?>">My Profile</h2>

    <div class="bg-black p-6 rounded-xl text-white w-full max-w-3xl mx-auto shadow-lg space-y-6">

      <!-- Profile Section -->
      <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-6 bg-gray-800 p-6 rounded-lg">
        <div class="flex gap-4 items-center w-full max-w-[370px]">
          <!-- Profile Picture -->
          <div class="w-24 h-24 bg-gray-500 rounded overflow-hidden">
            <img src="<?= $profileImageToShow ?>" alt="Profile Picture" class="w-full h-full object-cover">
          </div>

          <!-- Username and XP info -->
          <div class="flex-1 min-w-0">
            <p class="font-semibold text-xl"><?= htmlspecialchars($username) ?></p>
            <p class="text-sm text-gray-300">Level <?= $level ?></p>

            <!-- Progress Bar -->
            <div
              class="w-full max-w-[320px] sm:max-w-[300px] md:max-w-[300px] h-4 bg-gray-600 rounded-full mt-2 relative">
              <div class="absolute top-0 left-0 h-full bg-yellow-500 rounded-full" style="width: <?= $progress ?>%;">
              </div>
            </div>

            <p class="text-sm text-gray-400 mt-1"><?= $currentLevelExp ?> / <?= $expToNext ?> XP</p>
          </div>
        </div>

        <!-- Edit profile button -->
        <div class="self-start sm:self-auto">
          <a href="<?= $baseURL ?>edit-profile"
            class="bg-white text-black px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-100 transition">
            Edit Profile
          </a>
        </div>
      </div>

      <!-- Game Statistics -->
      <div class="bg-gray-700 p-6 rounded-lg space-y-4">
        <?php
        $games = [
          'Wordle' => ['label' => 'Wordle', 'key' => 'wordle', 'score' => $wordleData['score'], 'position' => $wordleData['position']],
          'Loldle' => ['label' => 'Loldle', 'key' => 'loldle', 'score' => 950, 'position' => '12th'],
          'Crosswordle' => ['label' => 'Crosswordle', 'key' => 'crosswordle', 'score' => 870, 'position' => '8th']
        ];

        foreach ($games as $label => $info) {
          echo '
          <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between bg-gray-600 text-white px-4 py-3 rounded-lg gap-2">
            <div class="sm:w-1/4 font-semibold">'.$label.'</div>
            <div class="sm:w-1/4 text-sm">Score: '.$info['score'].'</div>
            <div class="sm:w-1/4 text-sm">Placement: '.$info['position'].'</div>
            <div class="sm:w-1/4 text-right">
              <a href="statistic?game='.$info['key'].'" class="bg-white text-black px-3 py-1 rounded hover:bg-gray-100 text-sm font-medium transition">
                Statistics
              </a>
            </div>
          </div>';
        }
      ?>
      </div>
    </div>
  </section>

  <?php include_once($_SERVER['DOCUMENT_ROOT'] . "/H5-mini/Frontend/templates/footer.php"); ?>
</body>

</html>