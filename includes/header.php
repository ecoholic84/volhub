<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navbar with Dropdown</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="navbar-left">
                <a href="#" class="logo"> 
                    <img src="logo.png" alt="Devfolio Logo">
                    Devfolio
                </a>
                <ul class="nav-links">
                    <li><a href="#">Home</a></li>
                    <li><a href="#">Blog</a></li>
                </ul>
            </div>
            
            <div class="navbar-right">
                <div class="dropdown">
                    <button class="profile-btn">
                        <span class="username">ecoholic</span>
                        <img src="profile-icon.png" alt="Profile Icon">
                    </button>
                    <div class="dropdown-content">
                        <a href="#">My Devfolio</a>
                        <a href="#">Edit Profile</a>
                        <a href="#">My Hackathons</a>
                        <a href="#">My Projects</a>
                        <a href="#">My Badges</a>
                        <a href="#">My Claims</a>
                        <a href="#">Organizer Dashboard</a>
                        <a href="#">Account Settings</a>
                        <a href="#">Log Out</a>
                    </div>
                </div>
            </div>
        </nav>
    </header>
</body>
</html>
