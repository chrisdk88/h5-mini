document
    .getElementById('categorySelect')
    .addEventListener('change', function () {
        const categoryId = this.value;
        const categoryName = this.selectedOptions[0]?.dataset.name;

        const fileSelect = document.getElementById('fileSelect');
        fileSelect.innerHTML = '';

        if (!categoryName) return;

        console.log('Selected category ID:', categoryId);
        console.log('Selected category NAME:', categoryName);

        fetch(
            `/H5-mini/Frontend/UploadWords/listFiles.php?category=${encodeURIComponent(
                categoryName
            )}`
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
                    fileSelect.selectedIndex = 0;
                    loadTxtFile();
                }
            })
            .catch(err => {
                console.error(err);
                alert('Could not list files.');
            });
    });