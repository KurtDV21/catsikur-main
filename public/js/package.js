// Function to toggle dropdown visibility
const dropdownButtons = document.querySelectorAll('.dropbtn');

// Function to toggle dropdown content
function toggleDropdown(event) {
    // Close all dropdown contents except the one clicked
    document.querySelectorAll('.dropdown-content').forEach(content => {
        if (content !== event.currentTarget.nextElementSibling) {
            content.classList.remove('show');
        }
    });

    // Toggle the clicked dropdown content
    event.currentTarget.nextElementSibling.classList.toggle('show');
}

// Add click event to each dropdown button
dropdownButtons.forEach(button => {
    button.addEventListener('click', toggleDropdown);
});

// Close dropdown if clicked outside
document.addEventListener('click', function(event) {
    const isClickInside = [...dropdownButtons].some(button => button.contains(event.target));

    if (!isClickInside) {
        document.querySelectorAll('.dropdown-content').forEach(content => {
            content.classList.remove('show');
        });
    }
});

function toggleMenu() {
    const navLinks = document.querySelector('.nav-link');
    navLinks.classList.toggle('active');
}

const btnAdoptMe = document.getElementsByClassName('adopt-btn1')

window.onload = function() {
    // Attach click event listeners to all adopt buttons
    document.querySelectorAll('.adopt-btn1').forEach(button => {
        button.addEventListener('click', function() {
            alert('Redirecting to login page...');
            window.location.href = "/loginto";  // Redirect to login page
        });
    });
}

function toggleUserDropdown() {
    const dropdown = document.getElementById("userDropdownContent");
    dropdown.classList.toggle("show");

    // Update pointer-events based on the show class
    if (dropdown.classList.contains("show")) {
        dropdown.style.pointerEvents = "auto";
    } else {
        dropdown.style.pointerEvents = "none";
    }
}

// Close the dropdown if clicked outside
window.onclick = function(event) {
    if (!event.target.closest('.user-dropdown')) {
        const dropdown = document.getElementById("userDropdownContent");
        if (dropdown.classList.contains("show")) {
            dropdown.classList.remove("show");
            // Disable pointer events when the dropdown is hidden
            dropdown.style.pointerEvents = "none";
        }
    }
}

