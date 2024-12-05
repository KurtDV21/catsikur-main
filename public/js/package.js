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

let currentIndex = 0;

function showSlide(index) {
    const slides = document.querySelectorAll('.slide');
    const dots = document.querySelectorAll('.dot');

    // Ensure the index is within range
    if (index >= slides.length) {
        currentIndex = 0;
    } else if (index < 0) {
        currentIndex = slides.length - 1;
    } else {
        currentIndex = index;
    }

    // Move slider to the correct slide
    const slider = document.querySelector('.slider');
    slider.style.transform = `translateX(-${currentIndex * 100}vw)`; // Use template literals

    // Update active dot
    dots.forEach(dot => dot.classList.remove('active'));
    dots[currentIndex].classList.add('active');
}

// Set up automatic slide change every 8 seconds
setInterval(() => {
    showSlide(currentIndex + 1); // Show next slide
}, 8000);

// Initialize the first slide
showSlide(currentIndex);

// Change slide with prev and next buttons
function changeSlide(step) {
    showSlide(currentIndex + step);
}

// Optional: You can add event listeners to the dots if you want to click the dots directly
document.querySelectorAll('.dot').forEach((dot, index) => {
    dot.addEventListener('click', () => showSlide(index));
});
