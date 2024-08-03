<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://matcha.mizu.sh/matcha.css">
</head>
<body>
    <div class="signup-container">
        <div class="avatar"></div>

        <section class="signup-form">
            <form action="signup-handler.php" method="POST">

                <label for="fullname">Full Name</label>
                <input type="text" id="fullname"name="fullname" placeholder="Full Name" required>
                <br>
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Username" required>
                <br>
                <label for="email">Email Address</label> 
                <input type="text" id="email"name="email" placeholder="Email Address" required>
                <br>
                <label for="pwd">Password</label>
                <input type="password" id="pwd"name="pwd" placeholder="Password" required>
                <br>
                <label for="pwd">Repeat Password</label>
                <input type="password" id="pwdrepeat"name="pwdrepeat" placeholder="Repeat Password" required>
                <br>
                <button type="submit">Create Account</button>
        </form>
    </div>
</body>
</html>
