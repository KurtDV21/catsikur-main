<?php
use App\Core\Database;
use App\Models\User;

require_once __DIR__ . '/../../vendor/autoload.php';

session_start();

$database = new Database();
$dbConnection = $database->connect();
$userModel = new User($dbConnection);

if (!isset($_SESSION['user_id'])) {
  header('Location: /loginto');
  exit;
}

$userId = $_SESSION['user_id'];

// Handle approval and chat creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['approve_inquiry_id'])) {
  $inquiryId = (int) $_POST['approve_inquiry_id'];

  // Fetch inquiry details
  $stmt = $dbConnection->prepare("SELECT user_id, post_id FROM inquiries WHERE id = ?");
  $stmt->bind_param("i", $inquiryId);
  $stmt->execute();
  $inquiryResult = $stmt->get_result();
  $inquiry = $inquiryResult->fetch_assoc();
  $stmt->close();

  if ($inquiry) {
    // Create a new chat for approved inquiry
    $postId = $inquiry['post_id'];
    $inquirerId = $inquiry['user_id'];
    $authorId = $userId;  // Assuming the logged-in user is the author

    // Insert new chat record
    $stmt = $dbConnection->prepare("INSERT INTO chats (post_id, inquirer_id, author_id, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("iii", $postId, $inquirerId, $authorId);
    $stmt->execute();
    $chatId = $stmt->insert_id; // Get the new chat ID
    $stmt->close();

    // Update the inquiry status to "approved"
    $stmt = $dbConnection->prepare("UPDATE inquiries SET status = 'approved' WHERE id = ?");
    $stmt->bind_param("i", $inquiryId);
    $stmt->execute();
    $stmt->close();

    echo "Inquiry approved and chat triggered.";
  }
}

// Fetch all chats (for navigation) including profile_image_path
$stmt = $dbConnection->prepare("SELECT c.id AS chat_id, u.name AS chat_partner, u.profile_image_path FROM chats c
    LEFT JOIN user u ON (c.inquirer_id = u.id AND c.author_id = ?) OR (c.author_id = u.id AND c.inquirer_id = ?)
    WHERE c.inquirer_id = ? OR c.author_id = ?");
$stmt->bind_param("iiii", $userId, $userId, $userId, $userId);
$stmt->execute();
$chatsResult = $stmt->get_result();
$chats = [];
while ($row = $chatsResult->fetch_assoc()) {
  $chats[] = $row;
}
$stmt->close();

// If chat_id is passed, fetch messages for that chat and identify the chat partner's name
$messages = [];
$chatPartnerName = '';
if (isset($_GET['chat_id'])) {
  $chatId = (int) $_GET['chat_id'];
  foreach ($chats as $chat) {
    if ($chat['chat_id'] == $chatId) {
      $chatPartnerName = $chat['chat_partner'];
      break;
    }
  }
  $stmt = $dbConnection->prepare("SELECT m.message, m.created_at, u.name AS sender_name
        FROM chat_messages m
        JOIN user u ON m.sender_id = u.id
        WHERE m.chat_id = ?
        ORDER BY m.created_at ASC");
  $stmt->bind_param("i", $chatId);
  $stmt->execute();
  $result = $stmt->get_result();
  while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
  }
  $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Live Chat</title>
  <link rel="stylesheet" href="/css/chat.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>

<body>
<div class="chat-container">
  <!-- Chat List -->
  <div class="chat-list">
    <div class="chat-list-header">
      <a href="/user-homepage" class="back-btn">Back</a>
      <h2>Chats</h2>
    </div>
    <input type="text" id="chatSearch" placeholder="Search chats..." onkeyup="searchChats()">
    <?php if (empty($chats)): ?>
      <p>No chats available.</p>
    <?php else: ?>
      <div id="firstChat" class="chat-item" data-chat-id="<?php echo $chats[0]['chat_id']; ?>" onclick="selectChat(<?php echo $chats[0]['chat_id']; ?>, '<?php echo htmlspecialchars($chats[0]['chat_partner']); ?>')">
        <img src="<?php echo htmlspecialchars($chats[0]['profile_image_path']); ?>" alt="Chat Partner 1" class="chat-avatar">
        <h3><?php echo htmlspecialchars($chats[0]['chat_partner']); ?></h3>
      </div>
      <?php for ($i = 1; $i < count($chats); $i++): ?>
        <div class="chat-item" data-chat-id="<?php echo $chats[$i]['chat_id']; ?>" onclick="selectChat(<?php echo $chats[$i]['chat_id']; ?>, '<?php echo htmlspecialchars($chats[$i]['chat_partner']); ?>')">
          <img src="<?php echo htmlspecialchars($chats[$i]['profile_image_path']); ?>" alt="Chat Partner <?php echo $i + 1; ?>" class="chat-avatar">
          <h3><?php echo htmlspecialchars($chats[$i]['chat_partner']); ?></h3>
        </div>
      <?php endfor; ?>
    <?php endif; ?>
  </div>
  
  <div class="chat-window-container">
    <!-- Chat Header -->
    <div class="chat-header">
      <h3 id="chatPartnerName">Chat with <?php echo htmlspecialchars($chatPartnerName); ?></h3>
    </div>
    <div id="chatWindow" class="chat-window">
      <?php if (!empty($messages)): ?>
        <?php foreach ($messages as $message): ?>
          <div class="message <?php echo ($message['sender_name'] == $_SESSION['user_name']) ? 'message-sender' : 'message-receiver'; ?>">
            <p><strong><?php echo htmlspecialchars($message['sender_name']); ?>:</strong>
              <?php echo htmlspecialchars($message['message']); ?></p>
            <span class="timestamp"><?php echo date('Y-m-d H:i:s', strtotime($message['created_at'])); ?></span>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
    <div id="typingIndicator" class="typing-indicator" style="display: none;">
      <p>Other user is typing...</p>
    </div>
    <!-- Message Input -->
    <form id="messageForm" onsubmit="sendMessage(event)">
      <textarea id="messageInput" placeholder="Type your message..." required></textarea>
      <button type="submit" class="send-btn">Send</button>
    </form>
  </div>
</div>




  <script>
function selectChat(chatId, chatPartnerName) {
  // Update the chat partner's name
  document.getElementById('chatPartnerName').innerText = 'Chat with ' + chatPartnerName;

  // Fetch messages for the selected chat (you can use AJAX here if needed)
  // Example: fetchMessages(chatId);
}

// Optional: Function to handle fetching and displaying chat messages
// function fetchMessages(chatId) {
//   // Implement AJAX call to fetch messages for the selected chat
//   // Update the chat window with the fetched messages
// }



function searchChats() {
  var input, filter, chatItems, chatItem, chatName, i, txtValue;
  input = document.getElementById('chatSearch');
  filter = input.value.toLowerCase();
  chatItems = document.getElementsByClassName('chat-item');

  for (i = 0; i < chatItems.length; i++) {
    chatItem = chatItems[i];
    chatName = chatItem.getElementsByTagName('h3')[0];
    txtValue = chatName.textContent || chatName.innerText;
    if (txtValue.toLowerCase().indexOf(filter) > -1) {
      chatItem.style.display = "";
    } else {
      chatItem.style.display = "none";
    }
  }
}



let currentChatId = null;
let fetchInterval = null;
let typingTimeout;

$(document).ready(function () {
  const firstChat = $('#firstChat');

  // Automatically load the first chat or the one stored in localStorage
  if (firstChat.length) {
    // Check if there's a saved chat_id in localStorage
    const savedChatId = localStorage.getItem('selectedChatId');
    if (savedChatId) {
      currentChatId = savedChatId;
      loadChat();
      startFetchingMessages();
      highlightSelectedChat($(`.chat-item[data-chat-id="${currentChatId}"]`)); // Highlight the saved chat
    } else {
      currentChatId = firstChat.data('chat-id');
      loadChat();
      startFetchingMessages();
      highlightSelectedChat(firstChat); // Highlight the first chat
    }
  }

  // Attach click event to chat items
  $('.chat-item').on('click', function () {
    currentChatId = $(this).data('chat-id');
    const chatPartnerName = $(this).find('h3').text(); // Get the chat partner's name
    $('#chatPartnerName').text('Chat with ' + chatPartnerName); // Update chat partner's name
    localStorage.setItem('selectedChatId', currentChatId); // Save the selected chat_id to localStorage
    loadChat();
    startFetchingMessages();
    highlightSelectedChat($(this));
  });
});

function checkTypingStatus() {
  $.ajax({
    url: '/typing_status',  // Ensure this path is correct
    method: 'GET',
    data: { check_typing: true },
    success: function (response) {
      console.log(response);  // Add this line to debug the response
      if (response.is_typing) {
        $('#typingIndicator').show(); // Show the typing indicator
      } else {
        $('#typingIndicator').hide(); // Hide the typing indicator
      }
    }
  });
}

// Periodically check for typing status every second
setInterval(checkTypingStatus, 1000);

function highlightSelectedChat(clickedItem) {
  $('.chat-item').removeClass('selected'); // Remove highlight from all chats
  clickedItem.addClass('selected');         // Add highlight to the clicked chat
}

$('#messageInput').on('input', function () {
  clearTimeout(typingTimeout); // Clear the previous timeout to avoid multiple requests
  sendTypingStatus(true); // Send typing status as true

  // Set a timeout to stop sending typing status after 3 seconds of inactivity
  typingTimeout = setTimeout(function () {
    sendTypingStatus(false); // Send typing status as false after 3 seconds of inactivity
  }, 3000); // 3-second delay
});

function sendTypingStatus(isTyping) {
  console.log('Sending typing status:', isTyping);  // Debugging log

  $.ajax({
    url: '/typing_status', // PHP file to update typing status
    method: 'POST',
    data: { typing_status: isTyping ? 'true' : 'false' },
    success: function (response) {
      console.log('Typing status updated');
    }
  });
}

function loadChat() {
  if (!currentChatId) return;

  $.ajax({
    url: '/fetch_messages',
    method: 'GET',
    data: { chat_id: currentChatId },
    dataType: 'json',
    success: function (messages) {
      const chatWindow = $('#chatWindow');
      chatWindow.empty(); // Clear chat window

      if (messages.length === 0) {
        chatWindow.html('<p class="no-messages">No messages yet. Start the conversation!</p>');
      } else {
        messages.forEach(msg => {
          const messageDiv = $('<div class="message"></div>');
          const isSender = msg.sender_name === '<?php echo $_SESSION["user_name"]; ?>';

          messageDiv.addClass(isSender ? 'message-sender' : 'message-receiver');
          messageDiv.html(`
            <p><strong>${msg.sender_name}:</strong> ${msg.message}</p>
            <span class="timestamp">${new Date(msg.created_at).toLocaleString()}</span>
          `);

          chatWindow.append(messageDiv);
        });

        // Scroll to the bottom after appending messages
        chatWindow.scrollTop(chatWindow[0].scrollHeight);

        // Update browser history state
        history.pushState({ chatId: currentChatId }, '', `/chat?chat_id=${currentChatId}`);
      }
    },
    error: function () {
      console.error('Failed to load chat messages.');
    }
  });
}

function sendMessage(event) {
  event.preventDefault();

  const message = $('#messageInput').val().trim();
  if (!message) {
    alert('Message cannot be empty.');
    return;
  }

  $.ajax({
    url: '/send_message',
    method: 'POST',
    contentType: 'application/json',
    data: JSON.stringify({ chat_id: currentChatId, message }),
    dataType: 'json',
    success: function (response) {
      if (response.status === 'success') {
        const chatWindow = $('#chatWindow');
        const messageDiv = $('<div class="message message-sender"></div>');

        // Assuming response contains message and sender_name
        messageDiv.html(`
          <p><strong><?php echo htmlspecialchars($_SESSION['user_name']); ?>:</strong> ${message}</p>
          <span class="timestamp">${new Date().toLocaleString()}</span>
        `);

        chatWindow.append(messageDiv);
        $('#messageInput').val(''); // Clear the input field
        chatWindow.scrollTop(chatWindow[0].scrollHeight); // Scroll to the bottom
      } else {
        alert(response.message || 'Failed to send the message.');
      }
    },
    error: function () {
      console.error('Error sending message.');
    }
  });
}

function fetchAllMessages() {
  if (!currentChatId) return;

  $.ajax({
    url: '/fetch_messages',
    method: 'GET',
    data: { chat_id: currentChatId },
    dataType: 'json',
    success: function (messages) {
      const chatWindow = $('#chatWindow');
      chatWindow.empty(); // Clear chat window

      messages.forEach(msg => {
        const messageDiv = $('<div class="message"></div>');
      const isSender = msg.sender_name === '<?php echo $_SESSION["user_name"]; ?>';

      messageDiv.addClass(isSender ? 'message-sender' : 'message-receiver');
      messageDiv.html(`
        <p><strong>${msg.sender_name}:</strong> ${msg.message}</p>
        <span class="timestamp">${new Date(msg.created_at).toLocaleString()}</span>
      `);

      chatWindow.append(messageDiv);
    });
  },
  error: function () {
    console.error('Failed to fetch messages.');
  }
});
}

function startFetchingMessages() {
if (fetchInterval) clearInterval(fetchInterval);

fetchInterval = setInterval(fetchAllMessages, 3000);
}

window.addEventListener('popstate', function (event) {
if (event.state && event.state.chatId) {
  currentChatId = event.state.chatId;
  loadChat();
  startFetchingMessages();
} else {
  currentChatId = null;
}
});

  </script>

</body>

</html>