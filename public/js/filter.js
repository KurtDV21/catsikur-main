document.querySelectorAll('.filter-option').forEach(option => {
    option.addEventListener('click', (e) => {
        e.preventDefault();
        const filter = e.target.dataset.filter;
        const value = e.target.dataset.value;

        const filters = { [filter]: value };

        fetch('/ajaxFilterPosts', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(filters),
        })
            .then(response => response.json())
            .then(data => {
                const catContainer = document.querySelector('.cat-container');
                catContainer.innerHTML = ''; // Clear current posts

                if (data.error) {
                    catContainer.innerHTML = `<p>${data.error}</p>`;
                } else {
                    data.forEach(post => {
                        catContainer.innerHTML += `

                            <link rel="stylesheet" href="/css/user.css">
                            <div class="cat-wrapper">
                                <a href="/cat-details?post_id=${post.id}">
                                    <div class="cat-card">
                                        <img src="${post.picture}" alt="Cat Image">
                                        <h2>${post.cat_name}</h2>
                                        <p>Age: ${post.age} years</p>
                                        <p>Location: ${post.location}</p>
                                    </div>
                                </a>
                            </div>`;
                    });
                }
            })
            .catch(error => console.error('Error:', error));
    });
});
