<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Cat Adoption Post</title>
    <link rel="stylesheet" href="/css/add_post.css"> <!-- Add your CSS file link if needed -->
    <style>
        
    </style>
</head>
<body>
<header>
  <nav class="navbar">
    <img src="/image/logo1.png" alt="logo" class="logo">

    <div class="nav-container">
      <div class="hamburger" onclick="toggleMenu(this)">
          <div class="bar"></div>
          <div class="bar"></div>
          <div class="bar"></div>
      </div>

      <ul class="nav-link">
        <li><a href="#home">HOME</a></li>
        <li><a href="#ourcat">OUR CATS</a></li>
        <li><a href="">ABOUT</a></li>
        <li><a href="">FAQs</a></li>
        <li><button class="login-btn" onclick="location.href='/loginto'">Login</button></li>     </ul>
    </div>
  </nav>
</header>



<div class="form-container">
    <h2>Add Cat Adoption Post</h2>
    <form action="/process-addpost" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($userId); ?>"> <!-- Include the user ID -->
    
        <div class="input-box">
            <label for="name">Cat Name:</label>
            <input type="text" id="name" name="name"  required>
        </div>
        
        <div class="input-box">
            <label for="age">Age:</label>
            <input type="text" id="age" name="age" required>
        </div>

        <div class="input-box">
            <label for="location">Location:</label>
            <input type="text" id="location" name="location" required>
        </div>

        <div class="input-box">
            <label for="gender">Gender:</label>
            <input type="text" id="gender" name="gender" required>
        </div>

        <div class="input-box">
            <label for="color">Color:</label>
            <input type="text" id="color" name="color" required>
        </div>

        <div class="upload-box ">
            <label for="picture">Upload Picture:</label>
            <input type="file" id="picture" name="picture" accept="image/*" required>
        </div>

        <button type="submit" class="btn" onclick="openPopup()">Submit Post</button>
        <div class="popup" id="popup">
            <img src="/image/check.png" alt="">
            <h2>Thank You!</h2>
            <p>Your post has been successfully submitted, Thanks!</p>
            <button class="popbtn" onclick="closePopup()">OK</button>
        </div>
    </form>
</div>

<script>
    let popup = document.getElementById("popup");

    function openPopup(){
        popup.classList.add("open-popup")
    }

    function closePopup(){
        popup.classList.remove("open-popup")
    }
</script>

</body>
</html>