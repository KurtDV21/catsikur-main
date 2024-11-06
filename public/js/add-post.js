let popup = document.getElementById("popup");

function openPopup(event) {
    // Prevent the form from submitting if validation fails
    event.preventDefault();

    // Get form inputs
    const name = document.getElementById("name").value.trim();
    const age = document.getElementById("age").value.trim();
    const location = document.getElementById("location").value.trim();
    const gender = document.getElementById("gender").value.trim();
    const color = document.getElementById("color").value.trim();
    const picture = document.getElementById("picture").files.length;

    // Check if all required fields are filled
    if (name && age && location && gender && color && picture > 0) {
        popup.classList.add("open-popup"); // Open popup if all fields are filled
        setTimeout(() => {
            document.querySelector("form").submit(); // Submit form after popup shows
        }, 1000); // Adjust delay if needed
    } else {
        alert("Please fill out all required fields.");
    }
}

function closePopup(){
    popup.classList.remove("open-popup");
}

function toggleUserDropdown() {
    const dropdown = document.getElementById("userDropdownContent");
    dropdown.classList.toggle("show");
}

// Close the dropdown if clicked outside
window.onclick = function(event) {
    if (!event.target.closest('.user-dropdown')) {
        const dropdown = document.getElementById("userDropdownContent");
        if (dropdown.classList.contains("show")) {
            dropdown.classList.remove("show");
        }
    }
}