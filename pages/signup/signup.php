<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <style>
        [x-cloak] {
            display: none
        }
    </style>
    <script src="https://unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="flex items-center justify-center min-h-screen bg-gradient-to-br from-gray-900 via-black to-gray-800">
    <div class="w-full max-w-md p-6">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-semibold text-white mb-2">Create an account</h1>
            <p class="text-neutral-400 text-sm">Enter your details below to create your account</p>
        </div>

        <form action="signup-handler.php" method="POST" class="space-y-2">
        <label for="email" class="sr-only">Email</label>
        <input type="email" name="email" id="email" placeholder="name@example.com" required
            class="flex w-full h-10 px-3 py-2 text-sm bg-neutral-900 border rounded-md border-neutral-700 ring-offset-background placeholder:text-neutral-500 focus:border-neutral-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 disabled:cursor-not-allowed disabled:opacity-50 text-white">

        <label for="pwd" class="sr-only">Password</label>
        <input type="password" name="pwd" id="pwd" placeholder="Enter a strong password" required
            class="flex w-full h-10 px-3 py-2 text-sm bg-neutral-900 border rounded-md border-neutral-700 ring-offset-background placeholder:text-neutral-500 focus:border-neutral-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 disabled:cursor-not-allowed disabled:opacity-50 text-white">

        <label for="pwdrepeat" class="sr-only">Repeat Password</label>
        <input type="password" name="pwdrepeat" id="pwdrepeat" placeholder="Repeat the password" required
            class="flex w-full h-10 px-3 py-2 text-sm bg-neutral-900 border rounded-md border-neutral-700 ring-offset-background placeholder:text-neutral-500 focus:border-neutral-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 disabled:cursor-not-allowed disabled:opacity-50 text-white">

        <button type="submit"
            class="inline-flex items-center justify-center w-full h-10 px-4 py-2 text-sm font-medium tracking-wide text-black transition-colors duration-200 rounded-md bg-white focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 focus:shadow-outline focus:outline-none hover:bg-neutral-100">
            Sign Up
        </button>

        <!-- Error Handlers -->
        <?php
        if (isset($_GET["error"])) {
            $errorMessages = [
                "emptyInput" => "Fill in all fields!",
                "emailTaken" => "Sorry, this email is already registered. Use another email!",
                "invalidEmail" => "Choose a proper email!",
                "passwordTooShort" => "Password should be at least 8 characters!",
                "passwordsDontMatch" => "Passwords don't match!",
                "stmtFailed" => "Something went wrong, try again!",
                "none" => "You have signed up!",
            ];

            $errorKey = $_GET["error"];
            if (array_key_exists($errorKey, $errorMessages)) {
                echo "<div class='mt-2 pt-2 text-red-500 text-center text-sm'>{$errorMessages[$errorKey]}</div>";
            }
        }
        ?>
        </form>
        <div class="relative py-6">
            <div class="absolute inset-0 flex items-center">
                <span class="w-full border-t border-neutral-600"></span>
            </div>
        </div>
        <p class="mt-0 text-sm text-center text-neutral-500">Already have an account? <a href="../login/login.php"
                class="relative font-medium text-white group"><span>Login here</span><span
                    class="absolute bottom-0 left-0 w-0 group-hover:w-full ease-out duration-300 h-0.5 bg-white"></span></a>
        </p>
        <p class="text-center text-neutral-500 text-sm">
            By continuing, you agree to our Terms and Policy.
        </p>
    </div>
</body>

</html>