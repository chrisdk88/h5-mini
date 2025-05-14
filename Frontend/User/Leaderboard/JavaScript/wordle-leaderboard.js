function renderLeaderboard(containerId, players) {
    const container = document.getElementById(containerId);
    container.innerHTML = players.map((player, i) => `
        <div class="leaderboard-entry">
            <span>${i + 1}. ${player.name}</span>
            <span>${player.points} Pts</span>
        </div>
    `).join('');
}