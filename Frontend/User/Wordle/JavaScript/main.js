import { gameState, normalizeWord, waitForExpModalToClose } from './Game/wordle-state.js';

// --- Game Logic ---

import { isInWordList, checkGuess, handleKeyPress } from "./Game/wordle-check.js";
import { clearBoard, clearKeyboard, clearRow } from "./Game/wordle-clear.js";
import { createBoard, createKeyboard } from "./Game/wordle-create.js";
import { startEndlessGame } from "./Game/wordle-startEndless.js";
import { resetGame } from "./Game/wordle-resetGame.js";

// --- Utilities ---

import { calculateTimerPoints, calculateGuessPoints, calculateFinalScores } from "./Utils/wordle-calculate.js";
import { sendGameDataToAPI } from "./Utils/wordle-sendAPI.js";
import { getRandomWord } from "./Utils/wordle-getWord.js";
import { loadWords} from "./Utils/wordle-loadWords.js";

// --- UI / State Systems --- //

import { saveStreaks, loadStreaks, updateStreakUI } from "./Ui/wordle-streak.js";
import { showPointsExpModal } from "./Ui/wordle-pointsExp.js";
import { startTimer, stopTimer } from "./Ui/wordle-timer.js";
import { updateBoard } from "./Ui/wordle-update.js";


// --- Event Listeners --- //

document.addEventListener("keydown", (e) => {
    const key = e.key.toLowerCase();

    if (key === "enter" && gameState.currentGuess.length === 5) {
        checkGuess();
    } else if (key === "backspace") {
        gameState.currentGuess = gameState.currentGuess.slice(0, -1);
        updateBoard();
    } else if (/^[a-z]$/.test(key)) {
        handleKeyPress(key);
    }
});

document.addEventListener("DOMContentLoaded", () => {
  startEndlessGame();
});

window.addEventListener("resize", () => {
    if (window.innerWidth >= 768) {
        createKeyboard(); // Recreate keyboard if screen size changes
    } else {
        const keyboard = document.getElementById("keyboard");
        keyboard.innerHTML = ""; // Remove keyboard if screen size is smaller than 768px
    }
});