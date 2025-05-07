// ---- Words ---- //
let word = "";
let words = [];
let wordList = [];

// ---- Guess variables ---- //
let currentRow = 0;
let currentGuess = "";
let previousGuesses = [];

// ---- Timer variables ---- //
let timeLeft = 100;
let timerInterval = null;

// ---- Streak variables ---- //
let currentStreak = 0;
let highestStreak = 0;


//------ Document ------//

document.addEventListener("keydown", (e) => {
    const key = e.key.toLowerCase();

    if (key === "enter" && currentGuess.length === 5) {
        checkGuess();
    } else if (key === "backspace") {
        currentGuess = currentGuess.slice(0, -1);
        updateBoard();
    } else if (/^[a-z]$/.test(key)) {
        handleKeyPress(key);
    }
});

document.addEventListener("DOMContentLoaded", () => {
  startEndlessGame();
});

//------ Helper ------//

function normalizeWord(word) {
    return word.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
}

// ----- Reset -----//

async function resetGame() {
    await getRandomWord();

    currentGuess = "";
    currentRow = 0;
    previousGuesses = [];
    clearRow();
    clearBoard();
    clearKeyboard();
    stopTimer();
    startTimer();
}

//------ Start ------//
async function startEndlessGame() {
    await loadWords();
    initialiseGame()
}

//------ Load ------//

async function loadWords() {
    try {
        const response = await fetch('https://dles-api.mercantec.tech/api/WordleWords');
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        const data = await response.json();
        words = data.map(entry => entry.word.trim());
        wordList = [...words];
    } catch (error) {
        console.error("Failed to load words:", error);
    }
}

//------ Initialise ------//

async function initialiseGame() {
    await getRandomWord();
    
    createBoard();
    createKeyboard();
    startTimer();
    loadStreaks();
    document.getElementById('game').style.display = 'block';
}

//------ Random word ------//

async function getRandomWord() {
        try {
        // Get the JWT token from localStorage or sessionStorage
        const token = localStorage.getItem("jwt_token") || sessionStorage.getItem("jwt_token");

        // If the token is not available, handle the error (e.g., redirect to wordle)
        if (!token) {
            window.location.href = "/h5-mini/Frontend/wordle";  // Example redirect
            return;
        }

        // Send the JWT token in the request header
        const response = await fetch('https://dles-api.mercantec.tech/api/WordleWords/getRandomWord', {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${token}`  // Attach the token as a Bearer token
            }
        });

        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
        const data = await response.json();
        word = data.word.trim();
    } catch (error) {
        console.error("Failed to fetch random word:", error);
        return;
    }
}

// ------ Points Exp Modal ------//
function showPointsExpModal(points, exp) {
    document.getElementById('points-earned').textContent = points;
    document.getElementById('exp-earned').textContent = exp;
    pointsExpModal.classList.remove('hidden');
}

//------ Create ------//

function createBoard() {
    const board = document.getElementById("board");
    board.innerHTML = "";

    for (let i = 0; i < 6; i++) {
        for (let j = 0; j < 5; j++) {
            const tile = document.createElement("div");
            tile.classList.add("tile");
            tile.id = `tile-${i}-${j}`;
            board.appendChild(tile);
        }
    }
}

function createKeyboard() {
    const keyboard = document.getElementById("keyboard");
    keyboard.innerHTML = "";

    const rows = [
        "qwertyuiop",
        "asdfghjkl",
        "zxcvbnm"
    ];

    rows.forEach((row, rowIndex) => {
        const rowElement = document.createElement("div");
        rowElement.classList.add("keyboard-row");

        row.split("").forEach(key => {
            const keyElement = document.createElement("div");
            keyElement.classList.add("key");
            keyElement.id = `key-${key}`;
            keyElement.textContent = key;
            keyElement.addEventListener("click", () => handleKeyPress(key));
            rowElement.appendChild(keyElement);
        });

        if (rowIndex === 2) {
            const deleteKey = document.createElement("div");
            deleteKey.classList.add("key", "delete");
            deleteKey.id = "key-delete";
            deleteKey.textContent = "Delete";
            deleteKey.addEventListener("click", () => handleKeyPress("delete"));
            rowElement.appendChild(deleteKey);

            const enterKey = document.createElement("div");
            enterKey.classList.add("key", "enter");
            enterKey.id = "key-enter";
            enterKey.textContent = "Enter";
            enterKey.addEventListener("click", () => handleKeyPress("enter"));
            rowElement.appendChild(enterKey);
        }

        keyboard.appendChild(rowElement);
    });
}

//------ Check ------//

function isInWordList(guess) {
    return wordList.includes(guess.toLowerCase());
}

function checkGuess() {
    const guess = currentGuess.toLowerCase();
    const normalizedGuess = normalizeWord(guess);
    const normalizedWord = normalizeWord(word);
    const letterCounts = {};
    const usedKeys = new Set();

    if (!isInWordList(guess)) {
        alert("That isn't a word in the word list. Try again.");
        clearRow();
        return;
    }

    if (previousGuesses.includes(guess)) {
        alert("You've already guessed that word. Try a different one.");
        clearRow();
        return;
    }

    previousGuesses.push(guess);

    // First pass: mark all correct letters
    for (let i = 0; i < 5; i++) {
        if (normalizedGuess[i] === normalizedWord[i]) {
            const tile = document.getElementById(`tile-${currentRow}-${i}`);
            const keyElement = document.getElementById(`key-${guess[i]}`);

            tile.classList.add("correct");
            keyElement.classList.remove("present", "absent");
            keyElement.classList.add("correct");

            if (!letterCounts[normalizedWord[i]]) {
                letterCounts[normalizedWord[i]] = 0;
            }
            letterCounts[normalizedWord[i]]++;
        }
    }

    // Second pass: mark present letters
    for (let i = 0; i < 5; i++) {
        const tile = document.getElementById(`tile-${currentRow}-${i}`);
        const keyElement = document.getElementById(`key-${guess[i]}`);

        if (!tile.classList.contains("correct")) {
            if (normalizedWord.includes(normalizedGuess[i]) && letterCounts[normalizedGuess[i]] > 0) {
                tile.classList.add("present");
                if (!keyElement.classList.contains("correct")) {
                    keyElement.classList.remove("absent");
                    keyElement.classList.add("present");
                }
                letterCounts[normalizedGuess[i]]--;
            } else {
                tile.classList.add("absent");
                if (!usedKeys.has(guess[i])) {
                    keyElement.classList.add("absent");
                }
            }
            usedKeys.add(guess[i]);
        }
    }

    if (normalizedGuess === normalizedWord) {
        alert("Congratulations! You've guessed the word!");
        currentStreak++;
        if (currentStreak > highestStreak) highestStreak = currentStreak;
        saveStreaks();
        updateStreakUI();
        showPointsExpModal();
        resetGame();
        return;
    }

    currentRow++;
    currentGuess = "";

    if (currentRow === 6) {
        alert(`Game Over! The word was ${word}`);
        currentStreak = 0;
        saveStreaks();
        updateStreakUI();
        showPointsExpModal();
        resetGame();
    }
}

function handleKeyPress(key) {
    if (key === "enter") {
        if (currentGuess.length === 5) {
            checkGuess();
        }
    } else if (key === "delete" || key === "backspace") {
        currentGuess = currentGuess.slice(0, -1);
        updateBoard();
    } else if (/^[a-záéíóúüñ]$/.test(key) && currentGuess.length < 5) {
        currentGuess += key;
        updateBoard();
    }
}

//------ Update ------//

function updateBoard() {
    for (let i = 0; i < 5; i++) {
        const tile = document.getElementById(`tile-${currentRow}-${i}`);
        tile.textContent = currentGuess[i] || "";
    }
}

//------ Clear ------//

function clearRow() {
    for (let i = 0; i < 5; i++) {
        const tile = document.getElementById(`tile-${currentRow}-${i}`);
        tile.textContent = "";
        tile.classList.remove("correct", "present", "absent");
    }
    currentGuess = "";
}

function clearBoard() {
    const board = document.getElementById("board");
    for (let i = 0; i < 6; i++) {
        for (let j = 0; j < 5; j++) {
            const tile = document.getElementById(`tile-${i}-${j}`);
            tile.textContent = "";
            tile.classList.remove("correct", "present", "absent");
        }
    }
}

function clearKeyboard() {
    const keys = document.querySelectorAll(".key");
    keys.forEach(key => {
        key.classList.remove("correct", "present", "absent");
    });
}

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

//------ Points -----//

function getGameStats() {
    const timerElement = document.getElementById("timer");
    const timeLeft = timerElement ? parseInt(timerElement.textContent, 10) : 0;
    const guessesTaken = previousGuesses.length;

    return {
        timeLeft,
        guessesTaken
    };
}

function calculatePoints(stats) {
    const { timeLeft, guessesTaken } = stats;
    let points = 100; // Default points at the start

    const timePenalty = Math.floor((60 - timeLeft) / 10) * 10; // 10 points deducted every 10 seconds
    points -= timePenalty;

    const guessPenalty = (guessesTaken - 1) * 20; // 20 points deducted for each wrong guess
    points -= guessPenalty;

    return Math.max(points, 0); // Ensure points don't go below 0
}

// ----- Timer -----//
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

function calculateTimerPoints() {
    if (timeLeft <= 0) return 0;

    // Points drop by 10 every 10 seconds passed
    const secondsUsed = 100 - timeLeft;
    const pointDeduction = Math.floor(secondsUsed / 10) * 10;
    return Math.max(100 - pointDeduction, 0);
}