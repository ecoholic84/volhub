<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- <link rel="stylesheet" href="login.css"> -->
    <link rel="stylesheet" href="https://matcha.mizu.sh/matcha.css">
</head>
<body>
    <div class="login-container">
        <div class="avatar"></div>

        <section class="login-form">
            <form action="login-handler.php" method="POST">

                <label for="username">Username</label>
                
                <input type="text" id="username" name="username" placeholder="Username" required>
                <br>
                <label for="password">Password</label>
                <input type="password" id="password"name="password" placeholder="Password" required>

                <button type="submit">Sign in</button>
        </form>
        <a href="signup.php" class="create-account">CREATE ACCOUNT</a>
    </div>
</body>
</html>
