function loadTxtFile() {
    const fileSelect = document.getElementById('fileSelect');
    const categorySelect = document.getElementById('categorySelect');
    const fileContent = document.getElementById('fileContent');

    const selectedFile = fileSelect.value;
    const selectedCategoryName = categorySelect.selectedOptions[0]?.dataset.name;

    if (!selectedFile || !selectedCategoryName) {
        alert('Please choose both category and file.');
        return;
    }

    const path =
        `/H5-mini/Frontend/Admin/Upload/UploadWords/WordsList/Categories/` +
        `${encodeURIComponent(selectedCategoryName)}/` +
        `${encodeURIComponent(selectedFile)}`;

    fetch(path)
        .then(res => {
            if (!res.ok) throw new Error('Failed to load the file.');
            return res.text();
        })
        .then(text => {
            fileContent.textContent = text;
        })
        .catch(err => {
            console.error(err);
            alert('Error loading the file.');
        });
}