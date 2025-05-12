import { gameState } from "../Game/wordle-state.js";

//------ Timer ------//

function startTimer() {
    gameState.timeLeft = 100;
    const timeDisplay = document.getElementById("time-left");

    gameState.timerInterval = setInterval(() => {
        if (gameState.timeLeft <= 0) {
            clearInterval(timerInterval);
            timeDisplay.textContent = "00:00";
            return;
        }

        gameState.timeLeft--;
        const minutes = String(Math.floor(gameState.timeLeft / 60)).padStart(2, '0');
        const seconds = String(gameState.timeLeft % 60).padStart(2, '0');
        timeDisplay.textContent = `${minutes}:${seconds}`;
    }, 1000);
}

function stopTimer() {
    clearInterval(gameState.timerInterval);
}

export { startTimer, stopTimer };