// ---- Leaderboard Variables ---- //
let leaderboardData = null;

// ---- Fetch Leaderboard Data ---- //
document.addEventListener("DOMContentLoaded", () => {
    fetchLeaderboardData();
});

// Fetch leaderboard data with token in Authorization header
async function fetchLeaderboardData() {
    
    try {
    const token = localStorage.getItem("jwt_token") || sessionStorage.getItem("jwt_token");

    if (!token) {
        console.error("No token found in localStorage or sessionStorage.");
        return;
    }

    const response = await fetch("https://dles-api.mercantec.tech/api/Leaderboards", {
        method: "GET",
        headers: {
            "Authorization": `Bearer ${token}`,
            "Content-Type": "application/json"
        }
    })
    .then(res => {
        if (!res.ok) {
            throw new Error(`HTTP error! Status: ${res.status}`);
        }
        return res.json();
    })
    .then(data => {
        leaderboardData = data;
        // Default load: show "daily" leaderboard for SP and filtered for MP
        renderLeaderboard("daily", singleplayerContainer, data.daily);
        renderLeaderboard("daily", multiplayerContainer, data.daily.filter(entry => entry.username.includes("[MP]")));
    })

    }
    
    catch(error) {
        console.error("Error fetching leaderboard:", error);
        return;
    };
}

// ---- Render Leaderboard ---- //
function renderLeaderboard(type, container, entries) {
    container.innerHTML = "";

    if (!entries || entries.length === 0) {
        container.innerHTML = "<p class='text-center text-gray-500'>No data available.</p>";
        return;
    }

    entries.forEach(entry => {
        const div = document.createElement("div");
        div.className = "leaderboard-entry flex justify-between w-full px-4 py-2 border-b border-gray-200";
        div.innerHTML = `
            <span>#${entry.position}</span>
            <span>${entry.username}</span>
            <span>${entry.totalScore} pts</span>
        `;
        container.appendChild(div);
    });
}

// ---- Helper Functions ---- //
function setActiveTab(tabList, activeButton) {
    tabList.forEach(btn => btn.classList.remove("active"));
    activeButton.classList.add("active");
}
