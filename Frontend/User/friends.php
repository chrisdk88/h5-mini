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
  <title>DLES - Friends</title>
  <script>
  async function searchFriends() {
    const query = document.getElementById('searchInput').value;
    const token = "<?= $_SESSION['user_token'] ?>";
    const container = document.getElementById('searchResults');

    if (query.length < 1) {
      container.innerHTML = '';
      return;
    }

    const response = await fetch("<?= $baseAPI ?>Friends/Search?query=" + encodeURIComponent(query), {
      headers: {
        'Authorization': 'Bearer ' + token
      }
    });

    const results = await response.json();
    container.innerHTML = '';

    results.slice(0, 10).forEach(user => {
      const div = document.createElement('div');
      div.className = "flex justify-between items-center bg-gray-100 dark:bg-gray-800 px-4 py-2 rounded mb-1";
      div.innerHTML = `
        <span class="text-gray-900 dark:text-white">${user.username}</span>
        <button onclick="addFriend(${user.id}, '${user.username}')" class="text-sm text-blue-600 hover:underline">Add Friend</button>
      `;
      container.appendChild(div);
    });
  }

  async function addFriend(user2_id, username) {
    const token = "<?= $_SESSION['user_token'] ?>";

    try {
      const response = await fetch("<?= $baseAPI ?>Friends/sendFriendRequest", {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer ' + token
        },
        body: JSON.stringify({
          user2_id: user2_id
        })
      });

      const text = await response.text();

      if (response.ok) {
        alert("Friend request sent to " + username + "!");
        searchFriends();
      } else {
        console.error("Friend request failed:", text);
        alert("Error sending friend request:\n" + text);
      }

    } catch (error) {
      console.error("Fetch error:", error);
      alert("Could not send friend request.");
    }
  }

  async function toggleRequests() {
    const token = "<?= $_SESSION['user_token'] ?>";
    const userId = <?= $userId ?>;
    const container = document.getElementById('requestList');

    if (container.style.display === 'block') {
      container.style.display = 'none';
      return;
    }

    const res = await fetch(`<?= $baseAPI ?>Friends/requests/${userId}`, {
      headers: {
        'Authorization': 'Bearer ' + token
      }
    });

    const requests = await res.json();
    container.innerHTML = '';
    container.style.display = 'block';

    if (!Array.isArray(requests) || requests.length === 0) {
      container.innerHTML = '<p class="text-sm text-gray-600 dark:text-gray-400">No friend requests.</p>';
      return;
    }

    requests.forEach(request => {
      const div = document.createElement('div');
      div.className = "flex justify-between items-center bg-gray-100 dark:bg-gray-800 px-4 py-2 rounded mb-1";
      div.innerHTML = `
        <span class="text-gray-900 dark:text-white">${request.senderUsername}</span>
        <div class="space-x-2">
          <button onclick="respondToRequest(${request.requestId}, true)" class="text-green-600 hover:underline text-sm">Accept</button>
          <button onclick="respondToRequest(${request.requestId}, false)" class="text-red-600 hover:underline text-sm">Decline</button>
        </div>
      `;
      container.appendChild(div);
    });

  }

  async function respondToRequest(requestId, accept) {
    const token = "<?= $_SESSION['user_token'] ?>";
    const url = accept ?
      `<?= $baseAPI ?>Friends/acceptFriendRequest/${requestId}` :
      `<?= $baseAPI ?>Friends/Decline/${requestId}`;
    const method = accept ? 'PUT' : 'DELETE';

    const fetchOptions = {
      method: method,
      headers: {
        'Authorization': 'Bearer ' + token,
        'Content-Type': 'application/json'
      }
    };

    // Backend expects a boolean body only for PUT (accept)
    if (accept) {
      fetchOptions.body = JSON.stringify(true);
    }

    const response = await fetch(url, fetchOptions);

    if (response.ok) {
      toggleRequests();
    } else {
      alert("Failed to update friend request");
    }
  }

  async function removeFriend(positionId) {
    const token = "<?= $_SESSION['user_token'] ?>";

    if (!confirm("Are you sure you want to remove this friend?")) return;

    const response = await fetch(`<?= $baseAPI ?>Friends/decline/${positionId}`, {
      method: 'DELETE',
      headers: {
        'Authorization': 'Bearer ' + token
      }
    });

    if (response.ok) {
      location.reload(); // Refresh to update the list
    } else {
      alert("Failed to remove friend.");
    }
  }
  </script>
</head>

<body class="<?= $defaultBackgroundColor ?>">
  <?php include_once($_SERVER['DOCUMENT_ROOT'] . "/H5-mini/Frontend/templates/header.php"); ?>

  <section>
    <div class="<?= $defaultCenterAndFixedHeight ?>">
      <h1 class="<?= $sectionHeading ?> mb-6">Friends</h1>

      <!-- Friend Requests -->
      <div class="bg-white dark:bg-gray-900 rounded-lg shadow p-6 mb-6 2xl:w-[500px]">
        <button onclick="toggleRequests()" class="text-sm text-blue-600 hover:underline">Show Friend Requests</button>
        <div id="requestList" class="mt-4 hidden"></div>
      </div>

      <!-- Search Box -->
      <div class="bg-white dark:bg-gray-900 rounded-lg shadow p-6 mb-6 2xl:w-[500px]">
        <label for="searchInput" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Search for
          friends</label>
        <input type="text" id="searchInput" oninput="searchFriends()" placeholder="Enter username..."
          class="w-full p-2 rounded border border-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white">
        <div id="searchResults" class="mt-4"></div>
      </div>

      <!-- Current Friends -->
      <div class="bg-white dark:bg-gray-900 rounded-lg shadow p-6 2xl:w-[500px]">
        <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Your Friends</h2>
        <div class="space-y-2">
          <?php
          $friends_url = $baseAPI . "Friends/getUsersFriends/$userId";
          $ch = curl_init($friends_url);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer " . $_SESSION['user_token']
          ]);
          $friend_response = curl_exec($ch);
          curl_close($ch);

          $friends = json_decode($friend_response, true);
          if (is_array($friends) && count($friends) > 0) {
            foreach ($friends as $friend) {
              echo '
                <div class="flex justify-between items-center bg-gray-100 dark:bg-gray-800 px-4 py-2 rounded">
                  <span class="text-white">' . htmlspecialchars($friend['username']) . '</span>
                  <button onclick="removeFriend(' . $friend['friendshipId'] . ')" class="text-sm text-red-500 hover:underline">Remove</button>
                </div>
              ';
            }
          } else {
            echo '<p class="text-sm text-gray-600 dark:text-gray-400">You have no friends yet.</p>';
          }
          ?>
        </div>
      </div>
    </div>
  </section>

  <?php include_once($_SERVER['DOCUMENT_ROOT'] . "/H5-mini/Frontend/templates/footer.php"); ?>
</body>

</html>