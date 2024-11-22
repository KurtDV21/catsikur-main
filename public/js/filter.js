document.addEventListener('DOMContentLoaded', function() {
    const filterOptions = document.querySelectorAll('.filter-option');
    const catContainer = document.querySelector('.cat-container');

    const filters = {
        color: null,
        gender: null
    };

    filterOptions.forEach(option => {
        option.addEventListener('click', function(e) {
            e.preventDefault();
            const filter = this.getAttribute('data-filter');
            const value = this.getAttribute('data-value');

            if (value === 'default-color') {
                filters.color = null; 
            } else if (value === 'default-gender') {
                filters.gender = null; 
            } else {
                filters[filter] = value; 
            }

            fetch('/ajaxFilterPosts', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(filters),
            })
            .then(response => response.json())
            .then(data => {
                catContainer.innerHTML = '';
                if (data.error) {
                    catContainer.innerHTML = `<p>${data.error}</p>`;
                } else {
                    data.forEach(post => {
                        catContainer.innerHTML += `
                            <div class="cat-wrapper">
                                <a href="/cat-details?post_id=${post.id}">
                                    <div class="cat-card">
                                        <img src="${post.picture}" alt="Cat Image" class="cat-image">
                                        <h2>${post.cat_name}</h2>
                                        <p>Color: ${post.color}</p>
                                        <p>Age: ${post.age} years</p>
                                        <p>Location: ${post.location}</p>
                                    </div>
                                </a>
                                <button class="btn-adopt">Adopt Me</button>
                            </div>`;
                    });
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });
});
