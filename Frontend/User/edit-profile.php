<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . "/H5-mini/Frontend/includes/auth.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/H5-mini/Frontend/includes/links.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/H5-mini/Frontend/includes/tailwind-styling.php");

require_login();

// Regex rules - match backend
$validateUsername = '/^[a-zA-Z0-9]{5,15}$/';
$validateEmail = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
$validatePassword = '/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';

// Decode token to get user ID
function decode_jwt_payload($jwt)
{
  $parts = explode('.', $jwt);
  if (count($parts) !== 3) return null;
  $payload = $parts[1];
  $payload = str_replace(['-', '_'], ['+', '/'], $payload);
  $payload .= str_repeat('=', (4 - strlen($payload) % 4) % 4);
  return json_decode(base64_decode($payload), true);
}

$decoded = decode_jwt_payload($_SESSION['user_token']);
$userId = $decoded['http://schemas.xmlsoap.org/ws/2005/05/identity/claims/nameidentifier'] ?? null;

if (!$userId) die("Unable to retrieve user ID from token.");

// Validation errors
$field_errors = [];
$success_message = "";
$error_message = "";

// Get user info
$api_get_url = $baseAPI . "Users/$userId";
$ch = curl_init($api_get_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Bearer " . $_SESSION['user_token']]);
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$email = $username = "";
if ($http_code === 200) {
  $userData = json_decode($response, true);
  $email = $userData['email'] ?? '';
  $username = $userData['username'] ?? '';
}

// Delete image
if (isset($_POST['delete_profile_image'])) {
  $path = $_SERVER['DOCUMENT_ROOT'] . "/H5-mini/Frontend/Profile-images/{$userId}.jpg";
  if (file_exists($path)) {
    unlink($path);
    $success_message = "Profile image deleted.";
  } else {
    $error_message = "No image to delete.";
  }
}

// Update user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['delete_profile_image'])) {
  $email = $_POST['email'];
  $username = $_POST['username'];
  $password = $_POST['password'];
  $confirmPassword = $_POST['confirm_password'];

  // Local validation
  if ($password !== $confirmPassword) {
    $field_errors['Password'] = "Passwords do not match.";
  }

  $profilePic = $_FILES['profilepic'] ?? null;
  if ($profilePic && $profilePic['error'] === UPLOAD_ERR_OK) {
    $type = mime_content_type($profilePic['tmp_name']);
    $ext = strtolower(pathinfo($profilePic['name'], PATHINFO_EXTENSION));
    if (!in_array($type, ['image/jpeg', 'image/png']) || !in_array($ext, ['jpg', 'jpeg', 'png'])) {
      $field_errors['ProfilePic'] = "Only .jpg and .png allowed.";
    } else {
      $target = $_SERVER['DOCUMENT_ROOT'] . "/H5-mini/Frontend/Profile-images/{$userId}.jpg";
      if ($ext === 'png') {
        $img = imagecreatefrompng($profilePic['tmp_name']);
        imagejpeg($img, $target, 90);
        imagedestroy($img);
      } else {
        move_uploaded_file($profilePic['tmp_name'], $target);
      }
    }
  }

  if (empty($field_errors)) {
    $data = [
      "email" => $email,
      "username" => $username
    ];
    if (!empty($password)) $data['password'] = $password;

    $ch = curl_init($baseAPI . "Users/edit/$userId");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
      "Content-Type: application/json",
      "Authorization: Bearer " . $_SESSION['user_token']
    ]);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code === 200 || $http_code === 204) {
      header("Location: " . $_SERVER['PHP_SELF'] . "?updated=1");
      exit;
    } else {
      $result = json_decode($response, true);
      $error_message = $result['message'] ?? "Failed to update. Code: $http_code";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>DLES - Edit Profile</title>
  <script>
  document.addEventListener("DOMContentLoaded", () => {
    const emailInput = document.getElementById("email");
    const usernameInput = document.getElementById("username");
    const passwordInput = document.getElementById("password");
    const confirmPasswordInput = document.getElementById("confirm_password");

    const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    const usernameRegex = /^[a-zA-Z0-9]{5,15}$/;
    const passwordRegex = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

    function validateInput(input, regex) {
      input.classList.remove("border-red-500", "border-green-500");
      if (input.value === "") return;
      if (regex.test(input.value)) {
        input.classList.add("border-green-500");
      } else {
        input.classList.add("border-red-500");
      }
    }

    function validatePasswordsMatch() {
      confirmPasswordInput.classList.remove("border-red-500", "border-green-500");
      if (confirmPasswordInput.value === "") return;
      if (passwordInput.value === confirmPasswordInput.value) {
        confirmPasswordInput.classList.add("border-green-500");
      } else {
        confirmPasswordInput.classList.add("border-red-500");
      }
    }

    emailInput.addEventListener("input", () => validateInput(emailInput, emailRegex));
    usernameInput.addEventListener("input", () => validateInput(usernameInput, usernameRegex));
    passwordInput.addEventListener("input", () => validateInput(passwordInput, passwordRegex));
    confirmPasswordInput.addEventListener("input", validatePasswordsMatch);
    passwordInput.addEventListener("input", validatePasswordsMatch);
  });
  </script>
</head>

<body class="<?= $defaultBackgroundColor ?>">

  <?php include_once($_SERVER['DOCUMENT_ROOT'] . "/H5-mini/Frontend/templates/header.php"); ?>

  <section class="<?= $defaultCenterAndFixedHeight ?>">
    <h2 class="<?= $sectionHeading ?>">Edit Profile</h2>
    <div class="<?= $sectionBox ?>">
      <p class="<?= $sectionParagraph ?>">Update your account details. Leave password fields empty to keep your current
        password.</p>

      <?php if (!empty($success_message)) : ?>
      <p class="text-green-500 text-center text-sm mb-4"><?= htmlspecialchars($success_message) ?></p>
      <?php endif; ?>

      <?php if (!empty($error_message)) : ?>
      <p class="text-red-500 text-center text-sm mb-4"><?= htmlspecialchars($error_message) ?></p>
      <?php endif; ?>

      <form method="POST" enctype="multipart/form-data" class="space-y-6">
        <!-- Email -->
        <div>
          <label for="email" class="<?= $formLabel ?>">Email</label>
          <input type="email" name="email" id="email" value="<?= htmlspecialchars($email) ?>"
            class="<?= $formInput ?> <?= isset($field_errors['Email']) ? 'border-red-500' : 'border-green-500' ?>"
            required>
          <?php if (isset($field_errors['Email'])): ?>
          <p class="text-red-500 text-sm mt-1"><?= htmlspecialchars($field_errors['Email']) ?></p>
          <?php endif; ?>
        </div>

        <!-- Username -->
        <div>
          <label for="username" class="<?= $formLabel ?>">Username</label>
          <input type="text" name="username" id="username" value="<?= htmlspecialchars($username) ?>"
            class="<?= $formInput ?> <?= isset($field_errors['Username']) ? 'border-red-500' : 'border-green-500' ?>"
            required>
          <?php if (isset($field_errors['Username'])): ?>
          <p class="text-red-500 text-sm mt-1"><?= htmlspecialchars($field_errors['Username']) ?></p>
          <?php endif; ?>
        </div>

        <!-- Password -->
        <div>
          <label for="password" class="<?= $formLabel ?>">New Password (optional)</label>
          <input type="password" name="password" id="password"
            class="<?= $formInput ?> <?= isset($field_errors['Password']) ? 'border-red-500' : 'border-gray-300' ?>">
          <?php if (isset($field_errors['Password'])): ?>
          <p class="text-red-500 text-sm mt-1"><?= htmlspecialchars($field_errors['Password']) ?></p>
          <?php else: ?>
          <p class="text-sm mt-1 <?= isset($field_errors['Password']) ? 'text-red-500' : 'text-gray-400' ?>">
            <?= isset($field_errors['Password'])
                ? htmlspecialchars($field_errors['Password'])
                : 'Must contain min. 8 chars, symbol, number, and letter' ?>
          </p>
          <?php endif; ?>
        </div>

        <!-- Confirm Password -->
        <div>
          <label for="confirm_password" class="<?= $formLabel ?>">Confirm New Password</label>
          <input type="password" name="confirm_password" id="confirm_password" class="<?= $formInput ?>">
        </div>

        <!-- Profile Image Upload -->
        <div>
          <label for="profilepic" class="<?= $formLabel ?>">Upload Profile Image (optional)</label>
          <input type="file" name="profilepic" id="profilepic" accept=".jpg,.jpeg,.png"
            class="<?= $formInput ?> <?= isset($field_errors['ProfilePic']) ? 'border-red-500' : 'border-gray-300' ?>">
          <?php if (isset($field_errors['ProfilePic'])): ?>
          <p class="text-red-500 text-sm mt-1"><?= htmlspecialchars($field_errors['ProfilePic']) ?></p>
          <?php endif; ?>
        </div>

        <!-- Profile Picture Preview -->
        <?php
        $profileImagePath = "/H5-mini/Frontend/Profile-images/{$userId}.jpg";
        $absolutePath = $_SERVER['DOCUMENT_ROOT'] . $profileImagePath;
        $hasImage = file_exists($absolutePath);
        ?>
        <div class="flex justify-center">
          <img src="<?= $hasImage ? $profileImagePath : $baseURL . 'Profile-images/person.png' ?>"
            class="w-24 h-24 rounded-full object-cover border border-gray-300 shadow">
        </div>

        <!-- Delete profile image -->
        <?php if ($hasImage): ?>
        <div class="flex justify-center">
          <button type="submit" name="delete_profile_image"
            class="px-4 py-1 text-sm bg-red-600 hover:bg-red-700 text-white rounded">Delete Profile Image</button>
        </div>
        <?php endif; ?>

        <!-- Submit -->
        <div class="flex flex-col items-center gap-2">
          <button type="submit" name="update_profile" class="<?= $formButton ?>">Save Changes</button>
        </div>
      </form>

    </div>
  </section>

  <?php include_once($_SERVER['DOCUMENT_ROOT'] . "/H5-mini/Frontend/templates/footer.php"); ?>
</body>

</html>