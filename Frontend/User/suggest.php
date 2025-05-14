<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . "/H5-mini/Frontend/includes/links.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/H5-mini/Frontend/includes/tailwind-styling.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>DLES - Suggest a Game</title>
</head>

<body class="text-white">
  <?php include_once($_SERVER['DOCUMENT_ROOT'] . "/H5-mini/Frontend/templates/header.php"); ?>

  <section class="flex flex-col items-center justify-center min-h-screen px-4 py-8">
    <div class="bg-gray-800 rounded-lg shadow-lg p-8 w-full max-w-xl">
      <h1 class="text-2xl font-bold mb-6 text-center">Suggest a Game</h1>

      <form method="POST" class="space-y-4">
        <div>
          <label for="gameName" class="block text-sm font-medium mb-1">Game Name</label>
          <input type="text" name="gameName" id="gameName"
            class="w-full px-3 py-2 bg-gray-700 text-white border border-gray-600 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
            required>
        </div>

        <div>
          <label for="genre" class="block text-sm font-medium mb-1">Genre</label>
          <input type="text" name="genre" id="genre"
            class="w-full px-3 py-2 bg-gray-700 text-white border border-gray-600 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
            required>
        </div>

        <div>
          <label for="description" class="block text-sm font-medium mb-1">Why should we add this game?</label>
          <textarea name="description" id="description" rows="5"
            class="w-full px-3 py-2 bg-gray-700 text-white border border-gray-600 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
            required></textarea>
        </div>

        <button type="submit"
          class="w-full py-2 px-4 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded transition">
          Submit Suggestion
        </button>
      </form>
    </div>
  </section>

  <?php include_once($_SERVER['DOCUMENT_ROOT'] . "/H5-mini/Frontend/templates/footer.php"); ?>
</body>

</html>