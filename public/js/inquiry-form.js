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

function addOption(companyName) {
    const dropdownContent = document.getElementById("dropdownContent");

    const option = document.createElement("div");
    option.className = "dropdown-item";
    option.textContent = companyName;

    option.onclick = function () {
        document.querySelector(".dropbtn").textContent = companyName;
        document.getElementById('companyInput').value = companyName;
        closeMainDropdown(); // Close the dropdown when an option is selected
    };

    dropdownContent.appendChild(option);
}

function showOtherInput(isVisible) {
    const otherInput = document.getElementById('otherResidence');
    otherInput.style.display = isVisible ? 'inline-block' : 'none';
    if (!isVisible) {
        otherInput.value = ''; // Clear value when hidden
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const isOtherSelected = document.getElementById('other').checked;
    showOtherInput(isOtherSelected);
});

document.addEventListener("DOMContentLoaded", () => {
    const savedCompany = '<?php echo htmlspecialchars($_SESSION['lastSelectedCompany'] ?? ''); ?>';

    if (savedCompany) {
        document.querySelector(".dropbtn").textContent = savedCompany;
        document.getElementById('companyInput').value = savedCompany;
    }

    const companyOptions = [
        "Homestay/HouseWife/HouseHusband",
        "Student - High School/Senior Highschool",
        "Student - College",
        "Accountancy, banking and finance",
        "Aerospace",
        "Architecture, creative arts and design",
        "Business, consulting and management",
        "Charity and volunteer work",
        "Education",
        "Electronics, robotics, and mechanics",
        "Energy and Utilities",
        "Engineering, manufacturing and construction",
        "Environment and agriculture",
        "Food, food manufacturing",
        "Healthcare",
        "Hospitality and event management",
        "Information technology & computer",
        "Law",
        "Law enforcement and security",
        "Leisure, entertainment, sports and tourism",
        "Marketing, advertising and PR",
        "Media, news and internet",
        "Mining",
        "Public Services and administration",
        "Recruitment and HR",
        "Retail",
        "Sales and E-commerce",
        "Science and Pharmaceuticals",
        "Social Care",
        "Telecommunication and BPO",
        "Transport and logistics",
    ];

    companyOptions.forEach(option => addOption(option));
});
