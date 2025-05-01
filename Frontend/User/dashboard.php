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
  <title>DLES - Dashboard</title>
</head>

<body class="<?= $defaultBackgroundColor ?>">

  <?php include_once($_SERVER['DOCUMENT_ROOT'] . "/H5-mini/Frontend/templates/header.php"); ?>

  <section>
    <div class="<?= $defaultCenterAndFixedHeight ?>">
      <div class="">
        <h1 class="<?= $sectionHeading ?>">DLES</h1>
        <div class="flex items-center justify-center">
          <a href="<?= $baseURL ?>wordle" class="px-4 py-2 bg-[#1fd655] mr-[10px]">Wordle</a>
          <a href="<?= $baseURL ?>loldle" class="px-4 py-2 bg-[#aaaaaa] mr-[10px]">Loldle</a>
          <a href="<?= $baseURL ?>crosswordle" class="px-4 py-2 bg-[#cccccc] mr-[10px]">Crosswordle</a>
          <a href="<?= $baseURL ?>gamedle" class="px-4 py-2 bg-[#ff4d00]">Gamedle</a>
        </div>
      </div>
    </div>
  </section>

  <?php include_once($_SERVER['DOCUMENT_ROOT'] . "/H5-mini/Frontend/templates/footer.php"); ?>

  </script>

</body>

</html>