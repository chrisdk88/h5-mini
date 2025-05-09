//------ Timer ------//

function startTimer() {
    timeLeft = 100; // Reset to full time
    const timeDisplay = document.getElementById("time-left");

    timerInterval = setInterval(() => {
        if (timeLeft <= 0) {
            clearInterval(timerInterval);
            timeDisplay.textContent = "00:00";
            return;
        }

        timeLeft--;
        const minutes = String(Math.floor(timeLeft / 60)).padStart(2, '0');
        const seconds = String(timeLeft % 60).padStart(2, '0');
        timeDisplay.textContent = `${minutes}:${seconds}`;
    }, 1000);
}

function stopTimer() {
    clearInterval(timerInterval);
}