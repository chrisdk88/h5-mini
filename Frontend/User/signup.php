<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . "/H5-MINI/Frontend/includes/links.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/H5-MINI/Frontend/includes/tailwind-styling.php");
 
// Redirect if already logged in
if (isset($_SESSION['user_token'])) {
    header("Location: " . $baseURL . "dashboard");
    exit;
}
 
// Initialize values
$error_message = "";
$field_errors = [];
$email = "";
$username = "";
 
// Handle form submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm-password'];
 
    // Proceed with signup API call
    $api_url = $baseAPI . "Users/signup";
    $data = json_encode([
        "email" => $email,
        "username" => $username,
        "password" => $password
    ]);
 
    $ch = curl_init($api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
 
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_error = curl_error($ch);
    curl_close($ch);
 
    $result = json_decode($response, true);
 
    // Uncomment for debugging
    // var_dump($result);
 
    if ($http_code == 201 && isset($result['message'])) {
        header("Location: " . $baseURL . "user/login.php?signup=success");
        exit;
    } else {
        if (isset($result['errors']) && is_array($result['errors'])) {
            $field_errors = $result['errors'];
        } else {
            $error_message = $curl_error ?: ($result['message'] ?? "Signup failed. Please try again.");
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>DLES - Signup</title>
</head>

<body class="<?= $defaultBackgroundColor ?>">
  <?php include_once($_SERVER['DOCUMENT_ROOT'] . "/H5-MINI/Frontend/templates/header.php"); ?>

  <section>
    <div class="<?= $defaultCenterAndFixedHeight ?>">
      <div class="flex items-center mb-6 text-2xl font-semibold text-gray-900 dark:text-white">
        <img class="w-96 h-32 object-none" src="<?= $baseURL; ?>images/DLES-logo.png" alt="DLES logo">
      </div>

      <div
        class="w-full bg-white rounded-lg shadow dark:border sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
        <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
          <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
            Create an account
          </h1>

          <?php if (!empty($error_message)) : ?>
          <p class="text-red-500 text-sm"><?= htmlspecialchars($error_message); ?></p>
          <?php endif; ?>

          <form class="space-y-4 md:space-y-6" method="POST">
            <!-- Email -->
            <div>
              <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
              <input type="email" name="email" id="email" required placeholder="name@example.com"
                value="<?= htmlspecialchars($email); ?>"
                class="bg-gray-50 <?= isset($field_errors['Email']) ? 'border-red-500' : 'border-gray-300' ?> border text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
              <?php if (isset($field_errors['Email'])) : ?>
              <p class="text-red-500 text-sm mt-1"><?= htmlspecialchars($field_errors['Email']); ?></p>
              <?php endif; ?>
            </div>

            <!-- Username -->
            <div>
              <label for="username"
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Username</label>
              <input type="text" name="username" id="username" required placeholder="Username"
                value="<?= htmlspecialchars($username); ?>"
                class="bg-gray-50 <?= isset($field_errors['Username']) ? 'border-red-500' : 'border-gray-300' ?> border text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
              <?php if (isset($field_errors['Username'])) : ?>
              <p class="text-red-500 text-sm mt-1"><?= htmlspecialchars($field_errors['Username']); ?></p>
              <?php endif; ?>
            </div>

            <!-- Password -->
            <div>
              <label for="password"
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
              <input type="password" name="password" id="password" required placeholder="••••••••"
                class="bg-gray-50 <?= isset($field_errors['Password']) ? 'border-red-500' : 'border-gray-300' ?> border text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
              <?php if (isset($field_errors['Password'])) : ?>
              <p class="text-red-500 text-sm mt-1"><?= htmlspecialchars($field_errors['Password']); ?></p>
              <?php endif; ?>
            </div>

            <!-- Confirm password -->
            <div>
              <label for="confirm-password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Confirm
                password</label>
              <input type="password" name="confirm-password" id="confirm-password" required placeholder="••••••••"
                class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            </div>

            <!-- Submit -->
            <button type="submit"
              class="w-full text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
              Create an account
            </button>

            <p class="text-sm font-light text-gray-500 dark:text-gray-400">
              Already have an account?
              <a href="<?= $baseURL ?>login" class="font-medium text-blue-600 hover:underline">Login here</a>
            </p>
          </form>
        </div>
      </div>
    </div>
  </section>

  <?php include_once($_SERVER['DOCUMENT_ROOT'] . "/H5-MINI/Frontend/templates/footer.php"); ?>
</body>

</html>