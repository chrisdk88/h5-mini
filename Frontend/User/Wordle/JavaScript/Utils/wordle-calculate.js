//------ Calculate -----// 

function calculateTimerPoints() {
    if (timeLeft <= 0) return 0;

    // Points drop by 10 every 10 seconds passed
    const secondsUsed = 100 - timeLeft;
    const pointDeduction = Math.floor(secondsUsed / 10) * 10;
    return Math.max(100 - pointDeduction, 0);
}

function calculateGuessPoints() {
    // Points drop by 20 for each guess after the first
    const guessPenalty = (previousGuesses.length - 1) * 20;
    return Math.max(120 - guessPenalty, 0);
}   

function calculateFinalScores() {
    const timerPoints = calculateTimerPoints();
    const guessPoints = calculateGuessPoints();

    // Combine the two point sources (you can customize the weight if needed)
    const totalPoints = Math.floor((timerPoints + guessPoints) / 2);

    // Example EXP calculation: 1 EXP per 2 points (you can adjust ratio)
    const totalExp = Math.floor(totalPoints / 2);

    return { totalPoints, totalExp };
}

export { calculateTimerPoints, calculateGuessPoints, calculateFinalScores };