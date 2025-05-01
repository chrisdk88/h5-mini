function loadTxtFile() {
    const fileSelect = document.getElementById('fileSelect');
    const categorySelect = document.getElementById('categorySelect');
    const fileContent = document.getElementById('fileContent');

    const selectedFile = fileSelect.value;
    const selectedCategoryName = categorySelect.options[categorySelect.selectedIndex].text; // Get the category name

    if (!selectedFile || !selectedCategoryName) {
        alert('Please select a category and a file to load.');
        return;
    }

    fetch(`/H5-mini/Frontend/UploadWords/WordsList/Categories/${selectedCategoryName}/${selectedFile}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to load the file.');
            }
            return response.text();
        })
        .then(content => {
            fileContent.textContent = content;
        })
        .catch(error => {
            console.error(error);
            alert('Error loading the file.');
        });
}