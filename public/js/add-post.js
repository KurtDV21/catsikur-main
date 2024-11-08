let popup = document.getElementById("popup");

function openPopup(event) {
    event.preventDefault(); // Prevent form submission

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
    } else {
        alert("Please fill in all fields.");
    }
    return false; // Prevent form submission
}

function closePopup() {
    popup.classList.remove("open-popup"); // Close the popup without submitting
}

function submitForm() {
    const form = document.querySelector("form");
    form.action = "/process-addpost"; // Set the action attribute when user confirms
    form.submit(); // Manually submit the form
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