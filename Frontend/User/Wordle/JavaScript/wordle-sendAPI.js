//----- Send -----//

function sendGameDataToAPI() {
    const userId = localStorage.getItem("user_id");
    if (!userId) {
        console.error('User ID not found in localStorage');
        return;
    }

    const gameSessionId = 0; // As mentioned, gameSessionId is set to 0
    const gameTime = timeLeft;
    const attempts = currentRow;
    const gameType = "wordle";
    const gameMode = "endless";

    const data = {
        user_id: userId,
        game_type: gameType,
        game_mode: gameMode,
        points: calculateFinalScores().totalPoints,
        is_multiplayer: false,
        game_session_id: gameSessionId,
        game_time: gameTime,
        attempts: attempts,
        word: word
    };

    // Log the data to check its structure
    console.log("Game data to be sent:", JSON.stringify(data, null, 2));

    // You can also log the headers, especially the authorization token
    console.log("Authorization header:", `Bearer ${localStorage.getItem("jwt_token")}`);

    fetch("https://dles-api.mercantec.tech/api/Scores/postScore", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${localStorage.getItem("jwt_token")}`
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            console.error('API Error:', data.error || 'Unknown error');
        } else {
            console.log('Data successfully submitted:', data);
        }
    })
    .catch(err => {
        console.error('Request failed:', err);
    });
}