import { word, wordList, currentGuess, previousGuesses, currentRow, currentStreak, highestStreak, normalizeWord } from "./wordle-state.js";

//------ Check ------//

function isInWordList(guess) {
    return wordList.includes(guess.toLowerCase());
}

function checkGuess() {
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
            alert("Congratulations! You've guessed the word!");
            currentStreak++;
            if (currentStreak > highestStreak) highestStreak = currentStreak;
            saveStreaks();
            updateStreakUI();
            const { totalPoints, totalExp } = calculateFinalScores();
            showPointsExpModal(totalPoints, totalExp);
            sendGameDataToAPI();
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
            showPointsExpModal(0, 0);
            resetGame();
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
            alert("Congratulations! You've guessed the word!");
            currentStreak++;
            if (currentStreak > highestStreak) highestStreak = currentStreak;
            saveStreaks();
            updateStreakUI();
            const { totalPoints, totalExp } = calculateFinalScores();
            showPointsExpModal(totalPoints, totalExp);
            sendGameDataToAPI();
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
            const { totalPoints, totalExp } = calculateFinalScores();
            showPointsExpModal(totalPoints, totalExp);
            sendGameDataToAPI();
            resetGame();
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

export { isInWordList, checkGuess, handleKeyPress };