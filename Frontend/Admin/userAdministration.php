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
      <div class="<?=$theBigAdminBox?> items-center justify-center">
        <h1 class="<?=$adminHeading?>">User administration</h1>
        <form method="post" action="">
          <label class="<?=$formLabel?>" for="search">Søg efter bruger:</label>
          <input class="<?=$formTextarea?>" type="text" id="searchInput" oninput="adminSeach()" name="search" placeholder="Enter username...">
          <button class="<?=$formButton?>" id="" type="">Søg</button>
        </form>
      </div>

         <a href="<?= $baseURL ?>adminPage" class="absolute top-[100px] right-[30px] <?= $redirectedIcon ?>"> <svg
          class="w-6 h-6 text-black" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
          viewBox="0 0 14 10">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M13 5H1m0 0 4 4M1 5l4-4" />
        </svg></a>  
         <!-- <a class="<?=$redirectedButton?><?= $redirectedIcon ?>" href="/H5-MINI/Frontend/Admin/adminPage.php"> Admin </a>-->
    
  </section>
 
  <?php include_once($_SERVER['DOCUMENT_ROOT'] . "/H5-mini/Frontend/templates/footer.php"); ?>
</body>

</html>