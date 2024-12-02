function toggleUserDropdown() {
    const dropdown = document.getElementById("userDropdownContent");
    dropdown.classList.toggle("show");
}

function toggleRestriction(userId, isRestricted) {
    const action = isRestricted ? 'unrestrict' : 'restrict';
    const confirmationMessage = `Are you sure you want to ${action} this user?`;

    // Show confirmation modal
    showConfirmationModal(confirmationMessage, userId, isRestricted);
}

function showConfirmationModal(message, userId, isRestricted) {
    // Create the modal HTML structure
    const modalHTML = `
    <div id="confirmationModal" style="display: block; position: fixed; z-index: 1; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.4);">
        <div style="background-color: white; margin: 15% auto; padding: 20px; border: 1px solid #888; width: 80%; max-width: 400px; text-align: center;">
            <p>${message}</p>
            <button id="confirmBtn" style="padding: 10px 20px; margin: 10px; cursor: pointer;">Yes</button>
            <button id="cancelBtn" style="padding: 10px 20px; margin: 10px; cursor: pointer;">No</button>
        </div>
    </div>`;

    // Insert the modal into the document body
    document.body.insertAdjacentHTML('beforeend', modalHTML);

    // Set up the event for the confirmation button
    document.getElementById("confirmBtn").onclick = function() {
        // Close the modal
        document.getElementById("confirmationModal").remove();

        // Proceed with the action
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
                // Update the button text based on the new restriction state
                const button = document.querySelector(`button[onclick="toggleRestriction(${userId}, ${isRestricted})"]`);
                const newText = isRestricted ? "Restrict" : "Unrestrict"; // This was the key part to fix
                button.innerText = newText;

                // Show success modal with a message
                showModal(`User has been ${isRestricted ? "unrestricted" : "restricted"}.`);

            } else {
                showModal(`Failed to ${isRestricted ? "unrestrict" : "restrict"} user.`);
            }
        })
        .catch(error => console.error('Error:', error));
    };

    // Set up the event for the cancel button
    document.getElementById("cancelBtn").onclick = function() {
        // Close the modal
        document.getElementById("confirmationModal").remove();
    };
}


function showModal(message) {
    // Create and show result modal HTML
    const modalHTML = `
    <div id="chatDialog" style="display: block; position: fixed; z-index: 1; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.4);">
        <div style="background-color: white; margin: 15% auto; padding: 20px; border: 1px solid #888; width: 80%; max-width: 400px; text-align: center;">
            <p>${message}</p>
            <button onclick="closeModal()" style="padding: 10px 20px; margin: 10px; cursor: pointer;">OK</button>
        </div>
    </div>`;

    // Insert the result modal into the document body
    document.body.insertAdjacentHTML('beforeend', modalHTML);
}

function closeModal() {
    // Close the result modal
    document.getElementById("chatDialog").remove();
    location.reload();
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