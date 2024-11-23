function toggleUserDropdown() {
    const dropdown = document.getElementById("userDropdownContent");
    dropdown.classList.toggle("show");
}

    function toggleRestriction(userId, isRestricted) {
        const action = isRestricted ? 'unrestrict' : 'restrict';
        if (confirm(`Are you sure you want to ${action} this user?`)) {
            fetch('/restrict-user', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },  
                body: JSON.stringify({ user_id: userId, is_restricted: !isRestricted })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(`User has been ${action}ed.`);
                    location.reload(); 
                } else {
                    alert(`Failed to ${action} user.`);
                }
            })
            .catch(error => console.error('Error:', error));
        }
    }

window.onclick = function(event) {
    if (!event.target.closest('.user-dropdown')) {
        const dropdown = document.getElementById("userDropdownContent");
        if (dropdown.classList.contains("show")) {
            dropdown.classList.remove("show");
        }
    }
}

$(document).ready(function() {
    $('#myTable').DataTable(); 
});
