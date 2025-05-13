function extractFields(character) {
    return {
    name: character.name || null,
    tags: character.tags || [],
    stats: character.stats || null,
    icon: character.icon || null,
    sprite: character.sprite || null
    };
}

function readFile(file) {
    return new Promise((resolve, reject) => {
    const reader = new FileReader();
    reader.onload = e => resolve(JSON.parse(e.target.result));
    reader.onerror = reject;
    reader.readAsText(file);
    });
}

async function processFiles() {
    const file1 = document.getElementById('file1').files[0];
    const file2 = document.getElementById('file2').files[0];

    if (!file1 || !file2) {
    alert("Please select both files.");
    return;
    }

    try {
    const data1 = await readFile(file1);
    const data2 = await readFile(file2);

    const combined = {};

    [data1, data2].forEach(data => {
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

    document.getElementById('output').textContent = JSON.stringify(combined, null, 2);

    } catch (err) {
    alert("Error reading files: " + err.message);
    }
}