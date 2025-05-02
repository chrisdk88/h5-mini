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
                const opt = document.createElement('option');
                opt.value = cat.id;
                opt.dataset.name = cat.category;
                opt.textContent = cat.category;

                categorySelect.appendChild(opt);
            });
        })
        .catch(err => {
            console.error(err);
            alert('Could not load categories.');
        });
}

document.addEventListener('DOMContentLoaded', fetchCategories);