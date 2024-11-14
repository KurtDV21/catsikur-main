// Function to toggle dropdown visibility
function toggleDropdown(dropdownId) {
    const dropdownContent = document.getElementById(dropdownId);
    dropdownContent.classList.toggle("show");

    // Close other dropdowns if they are open
    const dropdowns = document.getElementsByClassName("dropdown-content");
    for (let i = 0; i < dropdowns.length; i++) {
        if (dropdowns[i].id !== dropdownId && dropdowns[i].classList.contains('show')) {
            dropdowns[i].classList.remove('show'); // Close other dropdowns
        }
    }
}

// Close the dropdown if the user clicks outside of it
window.onclick = function(event) {
    if (!event.target.matches('.dropbtn')) {
        const dropdowns = document.getElementsByClassName("dropdown-content");
        for (let i = 0; i < dropdowns.length; i++) {
            if (dropdowns[i].classList.contains('show')) {
                dropdowns[i].classList.remove('show');
            }
        }
    }
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

