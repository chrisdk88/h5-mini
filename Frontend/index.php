<?php 
include_once($_SERVER['DOCUMENT_ROOT'] . "/H5-mini/Frontend/includes/links.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/H5-mini/Frontend/includes/tailwind-styling.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>DLES - Welcome</title>
</head>

<body class="bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-100">
  <?php include_once($_SERVER['DOCUMENT_ROOT'] . "/H5-MINI/Frontend/templates/header.php"); ?>

  <!-- Hero Section -->
  <section class="text-center py-20 bg-gradient-to-br from-blue-600 to-indigo-700 text-white">
    <h1 class="text-4xl md:text-5xl font-bold mb-4">Welcome to DLES Game Universe</h1>
    <p class="text-lg md:text-xl max-w-2xl mx-auto mb-6">
      Challenge your friends in Wordle and other exciting games! Earn points, build your friend list, and enjoy the
      thrill of competition.
    </p>
    <a href="<?= $baseURL ?>login"
      class="inline-block px-6 py-3 bg-white text-blue-700 font-semibold rounded-lg shadow hover:bg-gray-100 transition">
      Log In Now
    </a>
  </section>

  <!-- Game Highlights -->
  <section class="py-16 px-4 max-w-6xl mx-auto">
    <h2 class="text-3xl font-bold text-center mb-12">What Can You Play?</h2>
    <div class="grid md:grid-cols-3 gap-8">
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 hover:shadow-xl transition">
        <h3 class="text-xl font-semibold mb-2">Wordle</h3>
        <p>Guess the hidden word in just 6 tries! Race against the clock or challenge your friends.</p>
      </div>
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 hover:shadow-xl transition">
        <h3 class="text-xl font-semibold mb-2">Loldle</h3>
        <p>Can you guess the League of Legends champion in 6 clues? Put your game knowledge to the test!</p>
      </div>
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 hover:shadow-xl transition">
        <h3 class="text-xl font-semibold mb-2">Crosswordle</h3>
        <p>Enjoy a unique twist on classic crosswords with logic based word puzzles. Perfect for wordsmiths!</p>
      </div>
    </div>
  </section>

  <!-- Call to Action -->
  <section class="text-center py-16 bg-blue-100 dark:bg-gray-800">
    <h2 class="text-2xl font-bold mb-4">Ready to become the best?</h2>
    <p class="mb-6">Create your account and challenge your friends today!</p>
    <a href="<?= $baseURL ?>signup"
      class="inline-block px-6 py-3 bg-blue-700 text-white font-semibold rounded-lg shadow hover:bg-blue-800 transition">
      Get Started
    </a>
  </section>

  <?php include_once($_SERVER['DOCUMENT_ROOT'] . "/H5-MINI/Frontend/templates/footer.php"); ?>
</body>

</html>