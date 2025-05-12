import { gameState } from "../Game/wordle-state.js";

//------ Load ------//

async function loadWords() {
    try {
        const response = await fetch('https://dles-api.mercantec.tech/api/WordleWords');
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        const data = await response.json();
        gameState.words = data.map(entry => entry.word.trim());
        gameState.wordList = [...gameState.words];
    } catch (error) {
        console.error("Failed to load words:", error);
    }
}

export { loadWords };