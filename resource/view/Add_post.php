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
          <li><a href="/user-homepage">HOME</a></li>
          <li><a href="#ourcat">OUR CATS</a></li>
          <li><a href="#">ABOUT</a></li>
          <li><a href="#">FAQs</a></li>
          <li><label><?php echo htmlspecialchars($name); ?></label></li>
        </ul> 
      </div>
    </nav>
  </header>




<div class="form-container">
    <h2>Add Cat Adoption Post</h2>
    <form action="/process-addpost" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($userId); ?>"> <!-- Include the user ID -->
    
        <div class="input-box">
            <label for="name">Cat Name:</label>
            <input type="text" id="name" name="name" required>
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

        <div class="upload-box">
            <label for="picture">Upload Picture:</label>
            <input type="file" id="picture" name="picture" accept="image/*" required>
        </div>

        <button type="submit" class="btn">Submit Post</button>
    </form>
</div>

</body>
</html>