<header>
    <nav class="navbar">
    <img src="/image/logo1.png" alt="logo" class="logo">

      <div class="hamburger" onclick="toggleMenu()">
        <span></span>
        <span></span>
        <span></span>
      </div>

      <ul class="nav-link">
        <li><a href="/user-homepage">HOME</a></li>
        <li><a href="#ourcat">OUR CATS</a></li>
        <li><a href="#about">ABOUT</a></li>
        <li><a href="#faq">FAQs</a></li>
        <li>
          <div class="user-dropdown">
            <button class="user-dropdown-button" onclick="toggleUserDropdown()">
              <?php echo htmlspecialchars($name); ?>
            </button>
            <div class="user-dropdown-content" id="userDropdownContent">
              <a href="/logout">Logout</a>
            </div>
          </div>
        </li>
      </ul>
    </nav>
  </header>