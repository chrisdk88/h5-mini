<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

function require_login()
{
  global $baseURL;
  if (!isset($_SESSION['user_token'])) {
    header("Location: " . $baseURL . "login");
    exit;
  }
}

function is_logged_in()
{
  return isset($_SESSION['user_token']);
}

function require_admin()
{
  global $baseURL;

  if (!isset($_SESSION['user_token'])) {
    header("Location: " . $baseURL . "dashboard");
    exit;
  }

  $parts = explode('.', $_SESSION['user_token']);
  if (count($parts) !== 3) {
    header("Location: " . $baseURL . "dashboard");
    exit;
  }

  $payload = $parts[1];
  $payload = str_replace(['-', '_'], ['+', '/'], $payload);
  $payload .= str_repeat('=', (4 - strlen($payload) % 4) % 4);

  $decoded = json_decode(base64_decode($payload), true);

  $role = $decoded['http://schemas.microsoft.com/ws/2008/06/identity/claims/role'] ?? '';

  if (strtolower($role) !== 'admin') {
    header("Location: " . $baseURL . "dashboard");
    exit;
  }
}