import { gameState } from "../Game/wordle-state.js";

//------ Update ------//

function updateBoard() {
    for (let i = 0; i < 5; i++) {
        const tile = document.getElementById(`tile-${gameState.currentRow}-${i}`);
        tile.textContent = gameState.currentGuess[i] || "";
    }
}

export { updateBoard };