<!DOCTYPE html>
<html>
<head>
    <title>Profile Page</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f2f2f2;
        }

        .landing-page {
            text-align: center;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .button:hover {
            background-color: #0069d9;
        }
    </style>
</head>
<body>
    <div class="landing-page">
        <h1>Welcome to VolunteerConnect</h1>
        <p>Create your Personal Profile</p>
        <a href="profile-about.php" class="button">Create Profile</a>
    </div>
</body>
</html>
