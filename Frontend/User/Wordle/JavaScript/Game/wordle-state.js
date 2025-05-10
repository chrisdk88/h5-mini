export const gameState = {

  // ---- Words ---- //
word : "",
words : [],
wordList : [],

// ---- Guess variables ---- //
currentRow : 0,
currentGuess : "",
previousGuesses : [],

// ---- Timer variables ---- //
timeLeft : 100,
timerInterval : null,
secondsUsed : 0,

// ---- Streak variables ---- //
currentStreak : 0,
highestStreak : 0
};

export function normalizeWord(word) {
    return word.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
}

export function waitForExpModalToClose() {
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