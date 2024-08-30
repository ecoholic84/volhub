<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
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
            <h1 class="text-2xl font-semibold tracking-tight text-white mb-2">Login to your account</h1>
            <p class="text-sm text-neutral-400">Enter your credentials to log in to your account</p>
        </div>
        <form action="login-handler.php" method="POST" class="space-y-2">
            <input type="text" name="username" id="username" placeholder="Username or Email"
                class="flex w-full h-10 px-3 py-2 text-sm bg-neutral-900 border rounded-md border-neutral-700 ring-offset-background placeholder:text-neutral-500 focus:border-neutral-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 disabled:cursor-not-allowed disabled:opacity-50 text-white">
            <input type="password" name="pwd" id="pwd" placeholder="Enter your password"
                class="flex w-full h-10 px-3 py-2 text-sm bg-neutral-900 border rounded-md border-neutral-700 ring-offset-background placeholder:text-neutral-500 focus:border-neutral-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 disabled:cursor-not-allowed disabled:opacity-50 text-white">
            <button type="submit"
                class="inline-flex items-center justify-center w-full h-10 px-4 py-2 text-sm font-medium tracking-wide text-black transition-colors duration-200 rounded-md bg-white hover:bg-neutral-100 focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 focus:shadow-outline focus:outline-none">
                Sign In
            </button>

            <!-- Error Handler -->
            <?php
            if (isset($_GET["error"])) {
                $errorMessages = [
                    "emptyInput" => "Fill in all fields!",
                    "wrongLogin" => "Incorrect username/password!",
                    "none" => "Welcome!",
                ];

                $errorKey = $_GET["error"];
                if (array_key_exists($errorKey, $errorMessages)) {
                    echo "<div class='mt-2 text-red-500 text-sm text-center'>{$errorMessages[$errorKey]}</div>";
                }
            }
            ?>

            <div class="relative py-2">
            </div>
        </form>
        <!-- <div class="mt-0 text-center">
            <a href="#" class="text-sm text-neutral-400 hover:text-white">Forgot your password?</a>
        </div> -->
        <div class="mt-0 text-center">
            <p class="text-sm text-neutral-400">
                Don't have an account? <a href="../signup/signup.php" class="text-white hover:underline">Sign up</a>
            </p>
        </div>
    </div>
</body>

</html>