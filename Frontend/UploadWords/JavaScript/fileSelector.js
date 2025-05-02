document.getElementById('categorySelect').addEventListener('change', function () {
    const categoryId = this.value; // Use the category ID as the value
    const categoryName = this.options[this.selectedIndex].text; // Get the category name
    const fileSelect = document.getElementById('fileSelect');
    fileSelect.innerHTML = '';

    if (!categoryId) return;

    console.log('Selected category ID:', categoryId); // Debugging: Log the selected category ID
    console.log('Selected category:', categoryName); // Debugging: Log the selected category name

    fetch(`/H5-mini/Frontend/UploadWords/listFiles.php?category=${encodeURIComponent(categoryId)}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to fetch files');
            }
            return response.json();
        })
        .then(files => {
            console.log('Files received:', files); // Debugging: Log the files received
            files.forEach(file => {
                const opt = document.createElement('option');
                opt.value = file;
                opt.textContent = file;
                fileSelect.appendChild(opt);
            });
        })
        .catch(err => {
            console.error('Error fetching files:', err);
            alert('Could not list files.');
        });
});