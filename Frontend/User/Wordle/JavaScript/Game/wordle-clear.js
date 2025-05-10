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

export { clearRow, clearBoard, clearKeyboard };