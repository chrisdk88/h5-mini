<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/H5-mini/Frontend/includes/auth.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/H5-mini/Frontend/includes/links.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/H5-mini/Frontend/includes/tailwind-styling.php");

// Redirect if not logged in
require_login();

// Decode token to get user ID
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

// Variables
$success_message = "";
$error_message = "";
$field_errors = [];
$email = "";
$username = "";

// GET user data
$api_get_url = $baseAPI . "Users/$userId";

$ch = curl_init($api_get_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer " . $_SESSION['user_token']
]);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($http_code === 200) {
    $userData = json_decode($response, true);
    $email = $userData['email'] ?? '';
    $username = $userData['username'] ?? '';
} else {
    $error_message = "Unable to fetch user data. Code: $http_code";
}

// Handle profile image deletion
if (isset($_POST['delete_profile_image'])) {
    $imagePath = $_SERVER['DOCUMENT_ROOT'] . "/H5-mini/Frontend/Profile-images/{$userId}.jpg";
    if (file_exists($imagePath)) {
        unlink($imagePath);
        $success_message = "Profile image deleted.";
    } else {
        $error_message = "No profile image to delete.";
    }
}

// Handle profile update
if ($_SERVER["REQUEST_METHOD"] === "POST" && !isset($_POST['delete_profile_image'])) {
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    $profilePic = $_FILES['profilepic'] ?? null;

    if (!empty($password) && $password !== $confirmPassword) {
        $field_errors['Password'] = "Passwords do not match.";
    }

    // Handle file upload
    if ($profilePic && $profilePic['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/png'];
        $allowed_exts = ['jpg', 'jpeg', 'png'];

        $file_type = mime_content_type($profilePic['tmp_name']);
        $file_ext = strtolower(pathinfo($profilePic['name'], PATHINFO_EXTENSION));

        if (!in_array($file_type, $allowed_types) || !in_array($file_ext, $allowed_exts)) {
            $field_errors['ProfilePic'] = "Only .jpg and .png images are allowed.";
        } else {
            $targetDir = $_SERVER['DOCUMENT_ROOT'] . "/H5-mini/Frontend/Profile-images/";
            $targetPath = $targetDir . $userId . ".jpg";

            if ($file_ext === 'png') {
                $image = imagecreatefrompng($profilePic['tmp_name']);
                imagejpeg($image, $targetPath, 90);
                imagedestroy($image);
            } else {
                move_uploaded_file($profilePic['tmp_name'], $targetPath);
            }
        }
    }

    // If no errors, send to API
    if (empty($field_errors)) {
        $payload = [
            "email" => $email,
            "username" => $username,
            "password" => $password,
        ];

        $api_put_url = $baseAPI . "Users/edit/$userId";

        $ch = curl_init($api_put_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Authorization: Bearer " . $_SESSION['user_token']
        ]);

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($ch);
        curl_close($ch);

        if ($http_code === 200 || $http_code === 204) {
          header("Location: " . $_SERVER['PHP_SELF'] . "?updated=1");
          exit;
        } else {
            $result = json_decode($response, true);
            if (isset($result['errors']) && is_array($result['errors'])) {
                $field_errors = $result['errors'];
            } else {
                $error_message = $curl_error ?: ($result['message'] ?? "Failed to update profile. Code: $http_code");
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sentinel - Edit Profile</title>
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
            class="<?= $formInput ?> <?= isset($field_errors['Email']) ? 'border-red-500' : '' ?>" required>
          <?php if (isset($field_errors['Email'])) : ?>
          <p class="text-red-500 text-sm mt-1"><?= htmlspecialchars($field_errors['Email']); ?></p>
          <?php endif; ?>
        </div>

        <!-- Username -->
        <div>
          <label for="username" class="<?= $formLabel ?>">Username</label>
          <input type="text" name="username" id="username" value="<?= htmlspecialchars($username) ?>"
            class="<?= $formInput ?> <?= isset($field_errors['Username']) ? 'border-red-500' : '' ?>" required>
          <?php if (isset($field_errors['Username'])) : ?>
          <p class="text-red-500 text-sm mt-1"><?= htmlspecialchars($field_errors['Username']); ?></p>
          <?php endif; ?>
        </div>

        <!-- Password -->
        <div>
          <label for="password" class="<?= $formLabel ?>">New Password (optional)</label>
          <input type="password" name="password" id="password"
            class="<?= $formInput ?> <?= isset($field_errors['Password']) ? 'border-red-500' : '' ?>">
          <?php if (isset($field_errors['Password'])) : ?>
          <p class="text-red-500 text-sm mt-1"><?= htmlspecialchars($field_errors['Password']); ?></p>
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
            class="<?= $formInput ?> <?= isset($field_errors['ProfilePic']) ? 'border-red-500' : '' ?>">
          <?php if (isset($field_errors['ProfilePic'])) : ?>
          <p class="text-red-500 text-sm mt-1"><?= htmlspecialchars($field_errors['ProfilePic']); ?></p>
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