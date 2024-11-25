<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="/css/chat.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<header>
  <nav class="navbar">
     <img src="logo1.png" alt="logo" class="logo">

    <div class="nav-container">
   
      <!-- Hamburger Icon -->
      <div class="hamburger" onclick="toggleMenu()">
        <span class="line"></span>
        <span class="line"></span>
        <span class="line"></span>
      </div>

      <ul class="nav-link" id="nav-list">
        <li><a href="#home">HOME</a></li>
        <li><a href="#cats">OUR CATS</a></li>
        <li><a href="about.php">ABOUT</a></li>
        <li><a href="about.php">FAQs</a></li>
        <button onclick="visitPage()" class="login-btn">Login</button>
      </ul>
    </div>
  </nav>
</header>

<section>
<div class="chat-section">
    <h1>MESSAGES</h1>
    <div class="chat-container">
        <!-- Chat List -->
        <div class="chat-sidebar">
            <h2>Chats</h2>
            <div class="chat-list">
            <div class="chat-item" data-username="Username1">Username <br> Lorem ipsum dolor sit amet...</div>
            <div class="chat-item" data-username="Username2">Username <br> Lorem ipsum dolor sit amet...</div>
            <div class="chat-item" data-username="Username3">Username <br> Lorem ipsum dolor sit amet...</div>
            <div class="chat-item" data-username="Username4">Username <br> Lorem ipsum dolor sit amet...</div>
            <div class="chat-item" data-username="Username5">Username <br> Lorem ipsum dolor sit amet...</div>
            <div class="chat-item" data-username="Username6">Username <br> Lorem ipsum dolor sit amet...</div>
            <div class="chat-item" data-username="Username7">Username <br> Lorem ipsum dolor sit amet...</div>
            <div class="chat-item" data-username="Username8">Username <br> Lorem ipsum dolor sit amet...</div>
            <div class="chat-item" data-username="Username9">Username <br> Lorem ipsum dolor sit amet...</div>
            <div class="chat-item" data-username="Username10">Username <br> Lorem ipsum dolor sit amet...</div>
            <div class="chat-item" data-username="Username11">Username <br> Lorem ipsum dolor sit amet...</div>
            <div class="chat-item" data-username="Username12">Username <br> Lorem ipsum dolor sit amet...</div>
            <div class="chat-item" data-username="Username13">Username <br> Lorem ipsum dolor sit amet...</div>
            <div class="chat-item" data-username="Username14">Username <br> Lorem ipsum dolor sit amet...</div>
            <div class="chat-item" data-username="Username15">Username <br> Lorem ipsum dolor sit amet...</div>
            </div>
        </div>

        <!-- Chat Window -->
        <div class="chat-window">
            <div class="chat-header">
                <button class="back-btn">←</button>
                <span>USERNAME</span>
            </div>
            <div class="messages">
                <div class="message received">Lorem ipsum dolor sit amet.</div>
                <div class="message sent">Lorem ipsum dolor sit amet.</div>
            </div>
            <div class="message-input">
                <input type="text" placeholder="Type a message...">
                <button class="send-btn">➤</button>
            </div>
        </div>
    </div>
</div>
</section>

    <script src="chat.js"></script>
</body>

<footer class="footer">
        <div class="footer-container">
    <!-- About Section -->
    <div class="footer-about">
        <h3>ABOUT COMPANY</h3>
        <div class="para">
        <p>Lorem ipsum dolor sit amet. Ex officiis molestias et sapiente<br> doloremque et dolores doloribus est animi maiores. Ut fugiat <br> molestiae nam quia earum qui aliquid aliquid ab corrupti officiis. Et<br> temporibus quia 33 incidunt adipisci ea deleniti vero 33<br> reprehenderit repellat.</p>
        </div>
      
       
    </div>
 <!-- Social Media Section -->
    <div class="fb">
      <img src="facebook.png" alt="" class="fb-icon">
    </div>

    <div class="mess">
      <img src="messenger.png" alt="" class="mess-icon">
    </div>

   

</div>
 
<div class="details-container">
<div class="details">
<div class="place">
    <img src="place.png" alt="" class="place-icon">
    <p>ASDWQDASDASDASDASDASDASDASD</p>

  </div>

  <div class="phone">
    <img src="phone.png" alt="" class="phone-icon">
    <p>ASDWQDASDASDASDASDASDASDASD</p>

  </div>

  <div class="email">
    <img src="email.png" alt="" class="email-icon">
    <p>ASDWQDASDASDASDASDASDASDASD</p>

  </div>
  </div>
  </div>


  



    <!-- Left Bottom Text -->
    <div class="footer-left-text">
      <p>Cat Free Adoption & Rescue Philippines</p>
    </div>
  </div>
  
  
</footer>
</html>