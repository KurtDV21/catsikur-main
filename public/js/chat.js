// Create a WebSocket connection to the server
const socket = new WebSocket('ws://localhost:8080/chat');


// When the WebSocket connection is open
socket.addEventListener('open', function (event) {
    console.log('Connected to WebSocket server');
});

// When a message is received from the WebSocket server
socket.addEventListener('message', function (event) {
    const chatWindow = document.getElementById('chatWindow');
    const msg = JSON.parse(event.data); // Assuming the server sends a JSON string

    // Display the new message in the chat window
    const messageDiv = document.createElement('div');
    messageDiv.classList.add('message');
    messageDiv.innerHTML = `<p><strong>${msg.sender_name}:</strong> ${msg.message}</p><small>${new Date(msg.created_at).toLocaleString()}</small>`;
    chatWindow.appendChild(messageDiv);
});

// Send a new message when the form is submitted
function sendMessage(event) {
    event.preventDefault();

    if (!currentChatId) {
        alert('Please select a chat to send a message.');
        return;
    }

    const message = document.getElementById('messageInput').value;

    // Create the message object
    const messageData = {
        chat_id: currentChatId,
        message: message
    };

    // Send the message to the WebSocket server
    socket.send(JSON.stringify(messageData));

    // Optionally, you can append the message immediately without waiting for a response
    const chatWindow = document.getElementById('chatWindow');
    const messageDiv = document.createElement('div');
    messageDiv.classList.add('message');
    messageDiv.innerHTML = `<p><strong>You:</strong> ${message}</p><small>${new Date().toLocaleString()}</small>`;
    chatWindow.appendChild(messageDiv);

    // Clear the input field
    document.getElementById('messageInput').value = '';
}
