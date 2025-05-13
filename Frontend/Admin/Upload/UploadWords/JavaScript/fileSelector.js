document
    .getElementById('categorySelect')
    .addEventListener('change', function () {
        const category_id = this.value;
        const categoryName = this.selectedOptions[0]?.dataset.name;

        const fileSelect = document.getElementById('fileSelect');
        fileSelect.innerHTML = '';

        if (!categoryName) return;

        console.log('');
        console.log('Selected category name:', categoryName);
        console.log('Selected category ID:', category_id);

        fetch(
            `/H5-mini/Frontend/Admin/Upload/UploadWords/listFiles.php?category=${encodeURIComponent(categoryName)}`
        )
            .then(res => {
                if (!res.ok) throw new Error('Failed to fetch files');
                return res.json();
            })
            .then(files => {
                files.forEach(f => {
                    const opt = document.createElement('option');
                    opt.value = f;
                    opt.textContent = f;
                    fileSelect.appendChild(opt);
                });

                if (files.length === 1) {
                    fileSelect.selectedIndex = 0; // Automatically select the file
                    console.log('');
                    console.log('Single File selected but not loaded.');
                }
            })
            .catch(err => {
                console.error(err);
                alert('Could not list files.');
            });
    });