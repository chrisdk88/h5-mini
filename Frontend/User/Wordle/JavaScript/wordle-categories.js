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
let secondsUsed = 0;

// ---- Streak variables ---- //
let currentStreak = 0;
let highestStreak = 0;

// ---- Game Ended ---- //
let gameEnded = false;

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

//------ Helper ------//

function normalizeWord(word) {
    return word.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
}

function waitForExpModalToClose() {
  return new Promise((resolve) => {
    const modal = document.getElementById("points-exp-modal");

    // If modal is already hidden, resolve immediately
    if (modal.classList.contains("hidden")) {
      resolve();
      return;
    }

    // Watch for when the modal is hidden
    const observer = new MutationObserver(() => {
      if (modal.classList.contains("hidden")) {
        observer.disconnect();
        resolve();
      }
    });

    observer.observe(modal, { attributes: true, attributeFilter: ["class"] });
  });
}

//---- Resize ---- //

window.addEventListener("resize", () => {
    if (window.innerWidth >= 768) {
        createKeyboard(); // Recreate keyboard if screen size changes
    } else {
        const keyboard = document.getElementById("keyboard");
        keyboard.innerHTML = ""; // Remove keyboard if screen size is smaller than 768px
    }
});


//------ Start ------//

async function startCategoryGame() {
    await loadWords();
    initialiseGame()
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

//----- Reset -----//

async function resetGame() {
  // Wait until the EXP modal is closed
  await waitForExpModalToClose();

  // Then get a new random word
  await getRandomWord();

  // Then start the game again
  currentGuess = "";
  currentRow = 0;
  previousGuesses = [];
  clearRow();
  clearBoard();
  clearKeyboard();
  stopTimer();
  startTimer();
  gameEnded = false;
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

//------ Points Exp Modal ------//

function showPointsExpModal(totalPoints, totalExp) {
    document.getElementById('points-earned').textContent = totalPoints;
    document.getElementById('exp-earned').textContent = totalExp;
    pointsExpModal.classList.remove('hidden');
}

//------ End Game ------//
function endGame() {
    waitForExpModalToClose().then(() => {
        stopTimer();
        window.location.href = "/h5-mini/Frontend/wordle";
    });
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
    // Check if the screen width is 768px or greater
    if (window.innerWidth >= 768) {
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
    } else {
        console.log("Screen width is less than 768px. Keyboard not shown.");
    }
}

//------ Check ------//

function isInWordList(guess) {
    return wordList.includes(guess.toLowerCase());
}

function checkGuess() {
    if (gameEnded) return;
    // Check if the screen width is less than 768px
    if (window.innerWidth < 768) {
        // Skip keyboard-related actions
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
                tile.classList.add("correct");
            }
        }

        // Second pass: mark present letters
        for (let i = 0; i < 5; i++) {
            const tile = document.getElementById(`tile-${currentRow}-${i}`);
            if (!tile.classList.contains("correct")) {
                if (normalizedWord.includes(normalizedGuess[i]) && letterCounts[normalizedGuess[i]] > 0) {
                    tile.classList.add("present");
                } else {
                    tile.classList.add("absent");
                }
            }
        }

        if (normalizedGuess === normalizedWord) {
            gameEnded = true;
            alert("Congratulations! You've guessed the word!");
            currentStreak++;
            if (currentStreak > highestStreak) highestStreak = currentStreak;
            saveStreaks();
            updateStreakUI();
            const { totalPoints, totalExp } = calculateFinalScores();
            showPointsExpModal(totalPoints, totalExp);
            sendGameDataToAPI(false);
        }

        currentRow++;
        currentGuess = "";

        if (currentRow === 6) {
            gameEnded = true;
            alert(`Game Over! The word was ${word}`);
            currentStreak = 0;
            saveStreaks();
            updateStreakUI();
            const { totalPoints, totalExp } = calculateFinalScores(true);
            showPointsExpModal(totalPoints, totalExp);
            sendGameDataToAPI(true);
        }

    } else {
        // If screen width is 768px or larger, proceed with the regular keyboard-related actions
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
            gameEnded = true;
            alert("Congratulations! You've guessed the word!");
            currentStreak++;
            if (currentStreak > highestStreak) highestStreak = currentStreak;
            saveStreaks();
            updateStreakUI();
            const { totalPoints, totalExp } = calculateFinalScores();
            showPointsExpModal(totalPoints, totalExp);
            sendGameDataToAPI(false);
        }

        currentRow++;
        currentGuess = "";

        if (currentRow === 6) {
            gameEnded = true;
            alert(`Game Over! The word was ${word}`);
            currentStreak = 0;
            saveStreaks();
            updateStreakUI();
            const { totalPoints, totalExp } = calculateFinalScores(true);
            showPointsExpModal(totalPoints, totalExp);
            sendGameDataToAPI(true);
        }
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

//------ Calculate -----// 

function calculateTimerPoints(wasGameLost = false) {
    if (wasGameLost || timeLeft <= 0) {
        secondsUsed = 100; // full time used
        return { points: 0, pointDeduction: 100, timeLeft };
    }

    secondsUsed = 100 - timeLeft;
    const pointDeduction = Math.floor(secondsUsed / 10) * 10;
    const points = Math.max(100 - pointDeduction, 0);

    return { points, pointDeduction, timeLeft };
}

function calculateGuessPoints(wasGameLost = false) {
    if (wasGameLost) {
        return { points: 0, guessPenalty: 120 };
    }

    const guessPenalty = (previousGuesses.length - 1) * 20;
    const points = Math.max(120 - guessPenalty, 0);

    return { points, guessPenalty };
}

function calculateFinalScores(wasGameLost = false) {
    const timer = calculateTimerPoints(wasGameLost);
    const guess = calculateGuessPoints(wasGameLost);

    console.log("Timer points deducted:", timer.pointDeduction);
    console.log("Time left:", timer.timeLeft);
    console.log("Guess points deducted:", guess.guessPenalty);

    const totalPoints = Math.floor((timer.points + guess.points) / 2);
    const totalExp = Math.floor(totalPoints / 2);

    return { totalPoints, totalExp };
}

//----- Send -----//

function sendGameDataToAPI(wasGameLost = false) {
    const userId = localStorage.getItem("user_id");
    if (!userId) {
        console.error('User ID not found in localStorage');
        return;
    }

    const gameSessionId = 0;
    const { totalPoints } = calculateFinalScores(wasGameLost);
    const gameTime = 100 - timeLeft;
    const attempts = currentRow;
    const gameType = "wordle";
    const gameMode = "categories";

    const data = {
        user_id: userId,
        game_type: gameType,
        game_mode: gameMode,
        points: totalPoints,
        is_multiplayer: false,
        game_session_id: gameSessionId,
        game_time: {
            hour: 0,
            minute: 0,
            second: gameTime
        },
        attempts: attempts,
        word: word
    };

    console.log("Game data to be sent:", JSON.stringify(data, null, 2));
    console.log("Authorization header:", `Bearer ${localStorage.getItem("jwt_token")}`);

    fetch("https://dles-api.mercantec.tech/api/Scores/postScore", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${localStorage.getItem("jwt_token")}`
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`API request failed with status: ${response.status}`);
        }
        return response.text();  // Read response as plain text
    })
    .then(text => {
        if (text === "") {
            console.log("No response body from server, but request was successful.");
            return;
        }
        console.log("Response text:", text);
        try {
            const jsonData = JSON.parse(text);  // Try parsing the response manually
            console.log('Data successfully submitted:', jsonData);
        } catch (e) {
            console.error('Failed to parse response as JSON:', e);
        }
    })
    .catch(err => {
        console.error('Request failed:', err);
    });
}