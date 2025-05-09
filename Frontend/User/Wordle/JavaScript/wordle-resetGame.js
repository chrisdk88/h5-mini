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
}