document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById("signup");

    form.addEventListener("submit", function(event) {
        event.preventDefault(); // Prevent the default form submission

        const formData = new FormData(form);
        fetch('/process-signup', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                document.getElementById("emailError").textContent = data.error;
            } else if (data.success) {
                alert(data.success);
                window.location.href = data.redirect;
            }
        })
        .catch(error => console.error('Error:', error));
    });
});
