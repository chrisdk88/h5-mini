function fetchCategories() {
    const categorySelect = document.getElementById('categorySelect');

    fetch('https://dles-api.mercantec.tech/api/Categories', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${userToken}`, // Include the token here
        },
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to fetch categories');
            }
            return response.json();
        })
        .then(categories => {
            // Debugging: Log the API response
            console.log('Categories fetched from API:', categories);

            // Clear existing options
            categorySelect.innerHTML = '<option value="">Select a category</option>';

            // Populate the dropdown with categories
            categories.forEach(category => {
                const option = document.createElement('option');

                // Debugging: Log the category object
                console.log('Category object:', category);

                option.value = category.id; // Use the category ID as the value
                option.textContent = category.category; // Display the category name
                categorySelect.appendChild(option);

                // Debugging: Log each option
                console.log(`Added option: value=${option.value}, text=${option.textContent}`);
            });
        })
        .catch(error => {
            console.error('Error fetching categories:', error);
            alert('Failed to load categories. Please try again later.');
        });
}