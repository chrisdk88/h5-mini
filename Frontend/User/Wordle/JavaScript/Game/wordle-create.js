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

export { createBoard, createKeyboard };