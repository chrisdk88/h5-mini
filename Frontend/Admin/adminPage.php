<?php 
  session_start();
  include_once($_SERVER['DOCUMENT_ROOT'] . "/H5-mini/Frontend/includes/auth.php");
  include_once($_SERVER['DOCUMENT_ROOT'] . "/H5-mini/Frontend/includes/links.php");
  include_once($_SERVER['DOCUMENT_ROOT'] . "/H5-mini/Frontend/includes/tailwind-styling.php");
  require_admin();

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
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin</title>
</head>

<body class="<?= $defaultBackgroundColor ?>">
<?php 
  include_once($_SERVER['DOCUMENT_ROOT'] . "/H5-MINI/Frontend/templates/header.php") 
?>
 <section>
    <div class="<?= $defaultCenterAndFixedHeight ?>">
      <div class="<?=$theBigAdminBox?>">
        <h1 class="<?=$adminHeading?>">Administration of :</h1>
        <!-- div til link knapper (leadboard edidt og user administration)-->
        <div class="">
          <a class="<?=$redirectedButton?> <?=$adminBox?>" href="/H5-MINI/Frontend/Admin/userAdministration.php"> Users </a>
          <a class="<?=$redirectedButton?> <?=$adminBox?>"  href="/H5-MINI/Frontend/Admin/leaderboardAndStatisticsAdministration.php"> Leaderboard/Statistics</a>
          <a class="<?=$redirectedButton?> <?=$adminBox?>"  href="<?= $baseURL ?>wordleUpload"> Upload words</a>
          <a class="<?=$redirectedButton?> <?=$adminBox?>"  href="<?= $baseURL ?>sortingPage"> sort words</a>
        </div>
     </div>
    </div>
  </section>
 
  
  <?php include_once($_SERVER['DOCUMENT_ROOT'] . "/H5-mini/Frontend/templates/footer.php"); ?>
</body>

</html>