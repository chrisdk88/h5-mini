import { createBoard, createKeyboard } from "./wordle-create.js";
import { startTimer } from "../Ui/wordle-timer.js";
import { loadStreaks } from "../Ui/wordle-streak.js";

//------ Initialise ------//

async function initialiseGame() {
    const { getRandomWord } = await import("../Utils/wordle-getWord.js");

    await getRandomWord();
    
    createBoard();
    createKeyboard();
    startTimer();
    loadStreaks();
    document.getElementById('game').style.display = 'block';
}

export { initialiseGame };