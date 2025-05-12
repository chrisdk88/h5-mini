async function startEndlessGame() {
    const { loadWords } = await import("../Utils/wordle-loadWords.js");
    const { initialiseGame } = await import("./wordle-initialiseGame.js");
    
    await loadWords();
    initialiseGame();
}

export { startEndlessGame };