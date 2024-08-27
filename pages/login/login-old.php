<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In</title>
    <!-- <link rel="stylesheet" href="../styles/login.css"> -->
    <link rel="stylesheet" href="https://matcha.mizu.sh/matcha.css">

</head>
<body>
    <div class="login-container">
        <div class="avatar"></div>

        <section class="login-form">
            <form action="login-handler.php" method="POST">

                <label for="username">Username/Email</label>
                <input type="text" id="username" name="username" placeholder="Username/Email" required>
                <br>
                <label for="pwd">Password</label>
                <input type="password" id="pwd"name="pwd" placeholder="Password" required>

                <button type="submit">Log in</button>
        </form>
        <a href="signup.php" class="create-account">CREATE ACCOUNT</a>
    </div>

    <?php
    // Error Handler Functions
    if (isset($_GET["error"]))
    {
        if ($_GET["error"] == "emptyInput")
        {
            echo "<p>Fill in all fields!</p>";
        }
        else if ($_GET["error"] == "wrongLogin")
        {
            echo "<p>Incorrect username/password!</p>";
        }
    }
    ?>

</body>
</html>
