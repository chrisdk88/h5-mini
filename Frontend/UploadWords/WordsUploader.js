const fs = require('fs');
const path = require('path');
const fetch = require('node-fetch'); // Install this package using `npm install node-fetch`

const baseDir = path.join(__dirname, 'WordsList', 'Categories');
const apiEndpoint = 'https://dles-api.mercantec.tech/api/WordleWords'; // Replace with your actual API endpoint

const uploadWords = async () => {
    try {
        const categories = fs.readdirSync(baseDir);

        for (const category of categories) {
            const categoryDir = path.join(baseDir, category);
            const filePath = path.join(categoryDir, `${category}.txt`);

            if (fs.existsSync(filePath)) {
                const fileContent = fs.readFileSync(filePath, 'utf-8');
                const wordsArray = fileContent.split('\n').map(word => word.trim()).filter(word => word);

                const payload = {
                    category,
                    words: wordsArray,
                };

                const response = await fetch(apiEndpoint, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(payload),
                });

                if (response.ok) {
                    console.log(`Words for category "${category}" uploaded successfully.`);
                } else {
                    console.error(`Failed to upload words for category "${category}".`);
                }
            } else {
                console.warn(`File not found for category "${category}". Skipping...`);
            }
        }
    } catch (error) {
        console.error('Error uploading words:', error);
    }
};

uploadWords();
