<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VolunteerConnect</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: white;
            padding: 10px 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .navbar-left {
            display: flex;
            align-items: center;
        }

        .navbar-left .logo {
            display: flex;
            align-items: center;
            font-size: 1.5em;
            font-weight: bold;
            color: #000;
            text-decoration: none;
        }

        .navbar-left .logo img {
            height: 30px;
            margin-right: 10px;
        }

        .navbar-left .nav-links {
            list-style: none;
            display: flex;
            margin-left: 20px;
        }

        .navbar-left .nav-links li {
            margin-left: 20px;
        }

        .navbar-left .nav-links li a {
            text-decoration: none;
            color: #000;
            font-weight: bold;
            padding: 8px 12px;
        }

        .navbar-left .nav-links li a:hover {
            background-color: #f0f0f0;
            border-radius: 5px;
        }

        .navbar-right {
            position: relative;
        }

        .dropdown {
            position: relative;
            display: inline-block;
        }

        .profile-btn {
            background-color: transparent;
            border: none;
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .profile-btn .username {
            margin-right: 10px;
            font-weight: bold;
            color: #000;
        }

        .profile-btn img {
            width: 30px;
            border-radius: 50%;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: #fff;
            min-width: 180px;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.1);
            z-index: 1;
            border-radius: 8px;
            overflow: hidden;
        }

        .dropdown-content a {
            color: #000;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .volunteer-icon {
            display: inline-block;
            width: 1em;
            height: 1em;
            --svg: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath fill='%23000' d='M13.375 21.825q.275.075.638.063t.612-.113L22 19q0-.85-.6-1.425T20 17h-6.85q-.075 0-.175-.012t-.15-.038l-1.475-.525q-.2-.075-.275-.25t-.025-.375q.05-.175.25-.275t.4-.025l1.125.425q.1.05.163.063t.187.012H15.8q.475 0 .837-.325t.363-.85q0-.35-.213-.675t-.562-.45L9.3 11.125q-.175-.05-.35-.088T8.6 11H7v9.025zM1 20q0 .825.588 1.413T3 22t1.413-.587T5 20v-7q0-.825-.587-1.412T3 11t-1.412.588T1 13zm15-7.8q-.375 0-.737-.137t-.663-.413l-2.75-2.7q-.775-.75-1.312-1.662T10 5.3q0-1.375.963-2.337T13.3 2q.8 0 1.5.338t1.2.912q.5-.575 1.2-.913T18.7 2q1.375 0 2.338.963T22 5.3q0 1.075-.525 1.988t-1.3 1.662l-2.775 2.7q-.3.275-.662.413T16 12.2'/%3E%3C/svg%3E");
            background-color: currentColor;
            -webkit-mask-image: var(--svg);
            mask-image: var(--svg);
            -webkit-mask-repeat: no-repeat;
            mask-repeat: no-repeat;
            -webkit-mask-size: 100% 100%;
            mask-size: 100% 100%;
        }
</style>
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="navbar-left">
                <a href="#" class="logo"> 
                    <!-- <img src="logo.png" alt="Logo"> -->
                    <span class="volunteer-icon"></span>
                    VolunteerConnect
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
                        <a href="#">My Dashboard</a>
                        <a href="#">Edit Profile</a>
                        <a href="#">My Events</a>
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
