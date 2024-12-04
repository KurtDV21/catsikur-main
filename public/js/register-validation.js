document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById("signup");

    form.addEventListener("submit", function(event) {
        event.preventDefault(); // Prevent the default form submission

        const formData = new FormData(form);
        fetch('/process-signup', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text()) // Fetch as text first
        .then(text => {
            console.log("Raw response:", text);
            return JSON.parse(text); // Parse JSON
        })
        .then(data => {
            console.log("Data processed", data); // Log the processed data
            if (data.error) {
                console.log("Error: ", data.error);
                document.getElementById("emailError").textContent = data.error;
            } else if (data.success) {
                alert(data.success); // Optional alert
                console.log("Redirecting to: ", data.redirect);
                window.location.href = data.redirect; // Redirect to the new URL
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert("An error occurred. Please try again.");
        });
    });
});
