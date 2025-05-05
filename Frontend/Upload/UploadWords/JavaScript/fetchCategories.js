function fetchCategories() {
    const categorySelect = document.getElementById('categorySelect');

    fetch('https://dles-api.mercantec.tech/api/Categories', {
        headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${userToken}`,
        },
    })
        .then(res => {
            if (!res.ok) throw new Error('Failed to fetch categories');
            return res.json();
        })
        .then(categories => {
            categorySelect.innerHTML =
                '<option value="">-- Choose Category --</option>';

            categories.forEach(cat => {
                console.log('');
                console.log('Category ID:', cat.id, 'Category Name:', cat.category);
                const opt = document.createElement('option');
                opt.value = cat.id; // Set the category ID as the value
                opt.dataset.name = cat.category; // Store the category name
                opt.textContent = cat.category; // Display the category name
                categorySelect.appendChild(opt);
            });
        })
        .catch(err => {
            console.error(err);
            alert('Could not load categories.');
        });
}

document.addEventListener('DOMContentLoaded', fetchCategories);