<?php

namespace App\Components;

class Navbar
{
    private $logo;
    private $name;

    public function __construct($logo, $name)
    {
        $this->logo = $logo;
        $this->name = htmlspecialchars($name);
    }

    public function render()
    {
        echo '
        <header>

            <nav class="navbar">
            <img src="' . $this->logo . '" alt="logo" class="logo">
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
                    <li><label href="#">' . $this->name . '</label></li>
                </ul> 
                </div>
            </nav>
        </header>';
    }
}
