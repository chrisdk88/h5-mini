<?php 
  include_once($_SERVER['DOCUMENT_ROOT'] . "/H5-MINI/Frontend/templates/header.php") 
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin</title>
</head>

<body class="bg-[#006400] text-white items-center">
  <!-- div til håndtering af overskrift -->
  <div class="bg-[#252525] text-center">
    <h1>Admin Page</h1>
  </div >
  
  <!-- div til link knapper (leadboard edidt og user administration)-->
  <div class="bg-[#000000] text-center container ">
    <h2>test</h2>
    <a class="bg-[#999999]" href="/H5-MINI/Frontend/Pages/AdminPages/userAdministration.php"> Administrer brugerer </a> <br>
    <a class="bg-[#999999]"  href="/H5-MINI/Frontend/Pages/AdminPages/leaderboardAndStatisticsAdministration.php"> Administrer leaderboard/statistik</a>
  </div>
</body>

</html>