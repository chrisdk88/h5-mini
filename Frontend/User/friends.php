<?php
ob_start();

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

if (!$userId) {
  die("Unable to retrieve user ID from token.");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>DLES - Friends</title>
  <script>
  document.addEventListener("DOMContentLoaded", async () => {
    const token = "<?= $_SESSION['user_token'] ?>";
    const userId = <?= $userId ?>;
    const requestCountEl = document.getElementById('requestCount');

    try {
      const res = await fetch(`<?= $baseAPI ?>Friends/requests/${userId}`, {
        headers: {
          'Authorization': 'Bearer ' + token
        }
      });
      const requests = await res.json();

      if (Array.isArray(requests) && requests.length > 0) {
        requestCountEl.innerText = requests.length;
        requestCountEl.classList.remove('hidden');
      }
    } catch (e) {
      console.error("Could not fetch friend requests count.");
    }
  });

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

    if (results.length === 0) {
      container.innerHTML = '<p class="text-sm text-gray-400">No users found.</p>';
      return;
    }

    results.slice(0, 10).forEach(user => {
      const div = document.createElement('div');
      div.className = "flex justify-between items-center bg-gray-800 px-4 py-2 rounded mb-1";
      div.innerHTML = `
        <span class="text-white">${user.username}</span>
        <button id="add-btn-${user.id}" onclick="addFriend(${user.id}, '${user.username}')" class="text-sm text-blue-500 hover:underline">Add Friend</button>
      `;
      container.appendChild(div);
    });
  }

  async function addFriend(user2_id, username) {
    const token = "<?= $_SESSION['user_token'] ?>";
    const button = document.getElementById(`add-btn-${user2_id}`);

    try {
      const response = await fetch("<?= $baseAPI ?>Friends/sendFriendRequest", {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer ' + token
        },
        body: JSON.stringify({
          user2_id
        })
      });

      if (response.ok) {
        button.innerText = "Pending";
        button.disabled = true;
        button.classList.remove("text-blue-500", "hover:underline");
        button.classList.add("text-gray-400", "cursor-not-allowed");
      } else {
        const text = await response.text();
        document.getElementById('searchResults').innerHTML = `<p class='text-sm text-red-400'>${text}</p>`;
      }
    } catch (error) {
      console.error("Fetch error:", error);
      document.getElementById('searchResults').innerHTML =
        `<p class='text-sm text-red-400'>Could not send friend request.</p>`;
    }
  }

  async function refreshFriendList() {
    const token = "<?= $_SESSION['user_token'] ?>";
    const container = document.querySelector('.space-y-3');

    const response = await fetch("<?= $baseAPI ?>Friends/getUsersFriends/<?= $userId ?>", {
      headers: {
        'Authorization': 'Bearer ' + token
      }
    });

    if (!response.ok) {
      container.innerHTML = '<p class="text-sm text-gray-400">Error loading friends.</p>';
      return;
    }

    const friends = await response.json();
    container.innerHTML = '';

    if (Array.isArray(friends) && friends.length > 0) {
      friends.forEach(friend => {
        const div = document.createElement('div');
        div.className = "flex justify-between items-center bg-gray-800 px-4 py-3 rounded-lg";
        div.innerHTML =
          `
          <span class="text-white font-medium">${friend.username}</span>
          <button onclick="removeFriend(${friend.friendshipId})" class="text-sm text-red-500 hover:underline transition">Remove</button>`;
        container.appendChild(div);
      });
    } else {
      container.innerHTML = '<p class="text-sm text-gray-400">You have no friends yet.</p>';
    }
  }

  async function toggleRequests() {
    const token = "<?= $_SESSION['user_token'] ?>";
    const userId = <?= $userId ?>;
    const container = document.getElementById('requestList');
    const requestCountEl = document.getElementById('requestCount');

    const isVisible = container.style.display === 'block';
    container.style.display = isVisible ? 'none' : 'block';
    if (isVisible) return;

    const res = await fetch(`<?= $baseAPI ?>Friends/requests/${userId}`, {
      headers: {
        'Authorization': 'Bearer ' + token
      }
    });

    const requests = await res.json();
    container.innerHTML = '';

    if (!Array.isArray(requests) || requests.length === 0) {
      container.innerHTML = '<p class="text-sm text-gray-400">No friend requests.</p>';
      requestCountEl.classList.add('hidden');
      return;
    }

    requestCountEl.innerText = requests.length;
    requestCountEl.classList.remove('hidden');

    requests.forEach(request => {
      const div = document.createElement('div');
      div.className = "flex justify-between items-center bg-gray-800 px-4 py-2 rounded mb-1";
      div.innerHTML = `
        <span class="text-white">${request.senderUsername}</span>
        <div class="space-x-2">
          <button onclick="respondToRequest(${request.requestId}, true)" class="text-green-500 hover:underline text-sm">Accept</button>
          <button onclick="respondToRequest(${request.requestId}, false)" class="text-red-500 hover:underline text-sm">Decline</button>
        </div>`;
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
      method,
      headers: {
        'Authorization': 'Bearer ' + token,
        'Content-Type': 'application/json'
      }
    };

    if (accept) fetchOptions.body = JSON.stringify(true);

    const response = await fetch(url, fetchOptions);

    if (response.ok) {
      toggleRequests();
      if (accept) refreshFriendList();
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
      refreshFriendList();
    } else {
      alert("Failed to remove friend.");
    }
  }
  </script>
</head>

<body class="<?= $defaultBackgroundColor ?>">
  <?php include_once($_SERVER['DOCUMENT_ROOT'] . "/H5-mini/Frontend/templates/header.php"); ?>

  <section class="py-10 px-4">
    <div class="<?= $defaultCenterAndFixedHeight ?> space-y-8 max-w-2xl mx-auto">

      <h1 class="<?= $sectionHeading ?> text-center">Friends</h1>

      <div class="bg-gray-900 rounded-xl shadow-md p-6 w-full">
        <button onclick="toggleRequests()" id="friendRequestBtn"
          class="relative text-sm text-blue-500 hover:underline transition">
          Show Friend Requests
          <span id="requestCount" class="ml-2 text-xs bg-red-600 text-white rounded-full px-2 py-0.5 hidden"></span>
        </button>
        <div id="requestList" class="mt-4 hidden"></div>
      </div>

      <div class="bg-gray-900 rounded-xl shadow-md p-6 w-full">
        <label for="searchInput" class="block text-sm font-medium text-gray-300 mb-2">Search for friends</label>
        <input type="text" id="searchInput" oninput="searchFriends()" placeholder="Enter username..."
          class="w-full p-2 rounded-lg border border-gray-600 focus:ring-2 focus:ring-blue-500 bg-gray-800 text-white">
        <div id="searchResults" class="mt-4"></div>
      </div>

      <div class="bg-gray-900 rounded-xl shadow-md p-6 w-full">
        <h2 class="text-lg font-semibold mb-4 text-white">Your Friends</h2>
        <div class="space-y-3">
          <?php
          $friends_url = $baseAPI . "Friends/getUsersFriends/$userId";
          $ch = curl_init($friends_url);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Bearer " . $_SESSION['user_token']]);
          $friend_response = curl_exec($ch);
          curl_close($ch);

          $friends = json_decode($friend_response, true);
          if (is_array($friends) && count($friends) > 0) {
            foreach ($friends as $friend) {
              echo '
                <div class="flex justify-between items-center bg-gray-800 px-4 py-3 rounded-lg">
                  <span class="text-white font-medium">' . htmlspecialchars($friend['username']) . '</span>
                  <button onclick="removeFriend(' . $friend['friendshipId'] . ')" class="text-sm text-red-500 hover:underline transition">Remove</button>
                </div>';
            }
          } else {
            echo '<p class="text-sm text-gray-400">You have no friends yet.</p>';
          }
          ?>
        </div>
      </div>

    </div>
  </section>

  <?php include_once($_SERVER['DOCUMENT_ROOT'] . "/H5-mini/Frontend/templates/footer.php"); ?>
</body>

<?php ob_end_flush(); ?>

</html>