<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . "/H5-MINI/Frontend/includes/links.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/H5-MINI/Frontend/includes/tailwind-styling.php");

// Redirect logged-in users
if (isset($_SESSION['user_token'])) {
    header("Location: " . $baseURL . "dashboard");
    exit;
}

// Initialize error message
$error_message = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['emailOrUsername'];
    $password = $_POST['password'];

    // API URL
    $api_url = $baseAPI . "Users/login";

    // Prepare API request data
    $data = json_encode([
        "username" => $username,
        "password" => $password
    ]);

    // Set up cURL request
    $ch = curl_init($api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json"
    ]);

    // Execute request & get response
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_error = curl_error($ch);
    curl_close($ch);

    // Check if API response is valid JSON
    $result = json_decode($response, true);

    if ($http_code == 200 && isset($result['token'])) {
        // Login successful, store token in session
        $_SESSION['user_token'] = $result['token'];
        header("Location: " . $baseURL . "dashboard");
        exit;
    } else {
        // Handle API errors
        if ($curl_error) {
            $error_message = "Connection error. Please try again. " . $http_code;
        } elseif (isset($result['message'])) {
            $error_message = $result['message'];
        } else {
            $error_message = "Invalid username or password. " . $http_code;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>DLES - Login</title>
</head>

<body class="<?= $defaultBackgroundColor ?>">
  <!-- Header -->
  <?php include_once($_SERVER['DOCUMENT_ROOT'] . "/H5-MINI/Frontend/templates/header.php"); ?>

  <!-- Main -->
  <section>
    <div class="<?= $defaultCenterAndFixedHeight ?>">
      <!-- Images and DLES text -->
      <div class="flex items-center mb-6 text-2xl font-semibold text-white">
        <img class="w-96 h-32 object-none" src="<?= $baseURL; ?>images/DLES-logo.png" alt="DLES logo">
      </div>

      <!-- Login form -->
      <div class="w-full rounded-lg shadow border sm:max-w-md xl:p-0 bg-gray-800 border-gray-700">
        <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
          <h1 class="text-xl font-bold leading-tight tracking-tight md:text-2xl text-white">
            Sign in to your account
          </h1>

          <!-- Error Message -->
          <?php if (!empty($error_message)) : ?>
          <p class="text-red-500 text-sm"><?= htmlspecialchars($error_message); ?></p>
          <?php endif; ?>

          <form class="space-y-4 md:space-y-6" method="POST">
            <div>
              <label for="emailOrUsername" class="block mb-2 text-sm font-medium text-white">
                Your username
              </label>
              <input type="text" name="emailOrUsername" id="emailOrUsername"
                class="border border-gray-300 rounded-lg block w-full p-2.5 bg-gray-700 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-blue-500"
                placeholder="Enter your username" required>
            </div>

            <div>
              <label for="password" class="block mb-2 text-sm font-medium text-white">Password</label>
              <input type="password" name="password" id="password" placeholder="••••••••"
                class="border border-gray-300 rounded-lg block w-full p-2.5 bg-gray-700 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-blue-500"
                required>
            </div>

            <button type="submit"
              class="w-full text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
              Login
            </button>

            <p class="text-sm font-light text-gray-400">
              Don’t have an account yet?
              <a href="<?= $baseURL ?>signup" class="font-medium hover:underline text-primary-500">
                Sign up
              </a>
            </p>
          </form>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <?php include_once($_SERVER['DOCUMENT_ROOT'] . "/H5-MINI/Frontend/templates/footer.php"); ?>
</body>

</html>