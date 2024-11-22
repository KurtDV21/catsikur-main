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

function openModal(imageSrc) {
    const modal = document.getElementById("imageModal");
    const modalImage = document.getElementById("modalImage");
    const navbar = document.querySelector(".navbar");

    // Calculate dynamic margin top
    const viewportHeight = window.innerHeight;
    const marginTop = viewportHeight * 0.1; // Adjust this percentage as needed

    modalImage.style.marginTop = `${marginTop}px`;

    modal.style.display = "block";
    modalImage.src = imageSrc;
    navbar.style.display = "none"; // Hide the navigation bar

    // Close the modal when clicking outside the image
    modal.addEventListener("click", function(event) {
        if (event.target === modal) {
            closeModal();
        }
    });
}

function closeModal() {
    const modal = document.getElementById("imageModal");
    const navbar = document.querySelector(".navbar");

    modal.style.display = "none";
    navbar.style.display = "flex"; // Show the navigation bar
}
