async function fetchJson(filePath) {
    const response = await fetch(filePath);
    if (!response.ok) throw new Error(`Failed to load ${filePath}`);
    return await response.json();
}

function extractFields(character) {
    return {
    name: character.name || null,
    tags: character.tags || [],
    stats: character.stats || null,
    icon: character.icon || null,
    sprite: character.sprite || null
    };
}

async function compareFiles() {
    try {
        const file1 = await fetchJson("../data/lol-champions1.txt");
        const file2 = await fetchJson("../data/lol-champions2.txt");

        const combined = {};

        [file1, file2].forEach(data => {
            if (Array.isArray(data)) {
                data.forEach(champ => {
                    combined[champ.name] = extractFields(champ);
                });
            } else if (data.name) {
                combined[data.name] = extractFields(data);
            } else if (data.data) {
                for (let key in data.data) {
                    const champ = data.data[key];
                    combined[champ.name] = extractFields(champ);
                }
            }
        });

        document.getElementById("output").textContent = JSON.stringify(combined, null, 2);
    } catch (error) {
        console.error("Error comparing files:", error);
        document.getElementById("output").textContent = "Error: " + error.message;
    }
}