<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LetMeVolunteer</title>
    <style>
        body {
            margin: 0;
            font-family: 'Courier New', Courier, monospace;
            background-color: #121212;
            color: #ffffff;
        }
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background-color: #1f1f1f;
        }
        .navbar a {
            color: #ffffff;
            text-decoration: none;
            margin: 0 15px;
        }
        .navbar .logo {
            font-size: 20px;
            font-weight: bold;
            background-color: #4a90e2;
            padding: 10px 20px;
            border-radius: 20px;
        }
        .navbar .menu a {
            font-size: 16px;
        }

        .navbar .actions a.signin {
            font-size: 16px;
            padding: 10px 20px;
            border-radius: 20px;
            background-color: #34a853;
            color: #ffffff;
        }
        .content {
            display: flex;
            justify-content: center;
            align-items: center;
            height: calc(100vh - 80px);
            text-align: center;
        }
        .content h1 {
            font-size: 32px;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div class="logo">
            LetMeVolunteer
        </div>
        <div class="menu">
            <a href="#">Events</a>
            <a href="#">About</a>
            <a href="#">Blog</a>
        </div>
        <div class="actions">
            <a href="#" class="signin">Sign in</a>
        </div>
    </div>
    <div class="content">
        <h1>LetMeVolunteer: Gamifying Volunteering</h1>
    </div>
</body>
</html>
