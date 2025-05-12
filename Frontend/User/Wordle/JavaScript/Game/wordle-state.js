export const gameState = {
  word: "",
  words: [],
  wordList: [],
  currentRow: 0,
  currentGuess: "",
  previousGuesses: [],
  timeLeft: 100,
  timerInterval: null,
  secondsUsed: 0,
  currentStreak: 0,
  highestStreak: 0
};

function normalizeWord(word) {
  return word.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
}

function waitForExpModalToClose() {
  return new Promise((resolve) => {
    const modal = document.getElementById("points-exp-modal");
    if (modal.classList.contains("hidden")) {
      resolve();
      return;
    }

    const observer = new MutationObserver(() => {
      if (modal.classList.contains("hidden")) {
        observer.disconnect();
        resolve();
      }
    });

    observer.observe(modal, { attributes: true, attributeFilter: ["class"] });
  });
}

export { normalizeWord, waitForExpModalToClose };