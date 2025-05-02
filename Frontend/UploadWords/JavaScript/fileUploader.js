function uploadToDatabase() {
    const fileSelect = document.getElementById('fileSelect');
    const categorySelect = document.getElementById('categorySelect');
    const fileContent = document.getElementById('fileContent');

    const selectedFile = fileSelect.value;
    const selectedCategoryId = parseInt(categorySelect.value, 10); // Parse the category ID as an integer
    const selectedCategoryName = categorySelect.options[categorySelect.selectedIndex].text; // Get the category name

    // Debugging: Log selected file, category name, and category ID
    console.log('Selected file:', selectedFile);
    console.log('Selected category name:', selectedCategoryName);
    console.log('Selected category ID:', selectedCategoryId);

    if (!selectedFile || isNaN(selectedCategoryId)) {
        alert('Please select a valid category and a file to upload.');
        return;
    }

    const content = fileContent.textContent;

    // Debugging: Log file content
    console.log('File content:', content);

    const words = content
        .split('\n')
        .map(word => word.trim().replace(/^["']|["',]$/g, '')) // Clean up words
        .filter(word => word); // Remove empty lines

    // Debugging: Log cleaned-up words
    console.log('Words to upload:', words);

    if (words.length === 0) {
        alert('No valid words found in the file.');
        return;
    }

    const token = userToken;

    // Debugging: Log token
    console.log('User token:', token);

    const promises = words.map(word => {
        const payload = {
            word: word,
            category_id: selectedCategoryId, // Use the numeric ID
        };

        // Debugging: Log payload for each word
        console.log('Payload:', payload);

        return fetch('https://dles-api.mercantec.tech/api/WordleWords', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`,
            },
            body: JSON.stringify(payload),
        }).then(response => {
            // Debugging: Log response status for each word
            console.log(`Response for word "${word}":`, response.status);

            if (!response.ok) {
                return response.json().then(error => {
                    console.error(`Error uploading word "${word}":`, error);
                    throw new Error(`Failed to upload word "${word}": ${error.message || response.statusText}`);
                });
            }
            return response;
        });
    });

    Promise.all(promises)
        .then(() => {
            alert('All words uploaded successfully.');
            console.log('All words uploaded successfully.');
        })
        .catch(error => {
            console.error('Error uploading words:', error);
            alert('Error uploading words. Check the console for details.');
        });
}