function uploadToDatabase() {
    const fileSelect = document.getElementById('fileSelect');
    const categorySelect = document.getElementById('categorySelect');

    const selectedFile = fileSelect.value;
    const selectedCategoryId = Number(categorySelect.value);
    const selectedCategoryName = categorySelect.options[categorySelect.selectedIndex]?.dataset.name;

    // Debugging: Log selected file, category name, and category ID
    console.log('');
    console.log('Selected file:', selectedFile);
    console.log('Selected category name:', selectedCategoryName);
    console.log('Selected category ID:', selectedCategoryId);
    console.log('');

    if (!selectedFile || isNaN(selectedCategoryId) || selectedCategoryId <= 0) {
        alert('Please select a valid category and a file to upload.');
        return;
    }

    if (!selectedCategoryName) {
        alert('Please select a valid category.');
        return;
    }

    const path = 
    `/H5-mini/Frontend/Admin/Upload/UploadWords/WordsList/Categories/` + 
    `${encodeURIComponent(selectedCategoryName)}/` +
    `${encodeURIComponent(selectedFile)}`;

    // Fetch the file content dynamically
    fetch(path)
        .then(response => {
            if (!response.ok) {
                throw new Error(`Failed to load file content. Status: ${response.status}`);
            }
            return response.text();
        })
        .then(content => {
            console.log('File content:', content);
            console.log('');

            const words = content
                .split('\n')
                .map(word => word.trim().replace(/^["']|["',]$/g, '')) // Clean up words
                .filter(word => word); // Remove empty lines

            console.log('Words to upload:', words);
            console.log('');

            if (words.length === 0) {
                alert('No valid words found in the file.');
                return;
            }

            const token = userToken;

            const promises = words.map(word => {
                const payload = { word, category_id: selectedCategoryId };

                // Debugging: Log payload for each word
                console.log('Payload:', payload);

                return fetch('https://dles-api.mercantec.tech/api/WordleWords/postWord', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${token}`,
                    },
                    body: JSON.stringify(payload),
                }).then(async response => {
                    const responseText = await response.text();
                    console.log('');
                    console.log(`Response for word "${word}":`, response.status, responseText);

                    if (!response.ok) {
                        throw new Error(`Failed to upload word "${word}": ${responseText}`);
                    }
                    return response;
                });
            });

            return Promise.all(promises);
        })
        .then(() => {
            alert('All words uploaded successfully.');
            console.log('All words uploaded successfully.');
        })
        .catch(error => {
            console.error('Error uploading words:', error);
            alert('Error uploading words. Check the console for details.');
        });
}