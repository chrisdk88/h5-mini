import { gameState } from "../Game/wordle-state.js";

//------ Random word ------//

async function getRandomWord() {
        try {
        // Get the JWT token from localStorage or sessionStorage
        const token = localStorage.getItem("jwt_token") || sessionStorage.getItem("jwt_token");

        // If the token is not available, handle the error (e.g., redirect to wordle)
        if (!token) {
            window.location.href = "/h5-mini/Frontend/wordle";  // Example redirect
            return;
        }

        // Send the JWT token in the request header
        const response = await fetch('https://dles-api.mercantec.tech/api/WordleWords/getRandomWord', {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${token}`  // Attach the token as a Bearer token
            }
        });

        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
        const data = await response.json();
        gameState.word = data.word.trim();
    } catch (error) {
        console.error("Failed to fetch random word:", error);
        return;
    }
}

export { getRandomWord };