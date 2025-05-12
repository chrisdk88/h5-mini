import { gameState } from "../Game/wordle-state.js";

//------ Streak -----//

function saveStreaks() {
  localStorage.setItem("currentStreak", currentStreak);
  localStorage.setItem("highestStreak", highestStreak);
}

function loadStreaks() {
  gameState.currentStreak = parseInt(localStorage.getItem("currentStreak") || "0");
  gameState.highestStreak = parseInt(localStorage.getItem("highestStreak") || "0");
  updateStreakUI();
}

function updateStreakUI() {
    document.getElementById("current-streak").textContent = gameState.currentStreak;
    document.getElementById("highest-streak").textContent = gameState.highestStreak;
}

export { saveStreaks, loadStreaks, updateStreakUI };