//------ Streak -----//

function saveStreaks() {
  localStorage.setItem("currentStreak", currentStreak);
  localStorage.setItem("highestStreak", highestStreak);
}

function loadStreaks() {
  currentStreak = parseInt(localStorage.getItem("currentStreak") || "0");
  highestStreak = parseInt(localStorage.getItem("highestStreak") || "0");
  updateStreakUI();
}

function updateStreakUI() {
    document.getElementById("current-streak").textContent = currentStreak;
    document.getElementById("highest-streak").textContent = highestStreak;
}

export { saveStreaks, loadStreaks, updateStreakUI };