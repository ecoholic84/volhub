<?php include_once "dbh.inc.php";
session_start();
// exit(json_encode($_SESSION));
$user_id = $_SESSION["usersid"];

// if (!isset($_SESSION["usersid"])) {
//     header("Location: ../pages/login/login.php");
//     exit();
// }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organizer Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
    </style>
    <!-- Include the Alpine library on your page -->
    <script src="https://unpkg.com/alpinejs" defer></script>
</head>

<body class="h-screen bg-gray-900 text-neutral-100 flex flex-col">
<?php include "header.php" ?>

    <!-- Navbar -->
    <header class="bg-gray-800 py-4 px-8 flex justify-between items-center">
        <h1 class="text-xl font-bold">Organizer Dashboard</h1>
        <div class="px-4 py-2 rounded-md">
        <?php include_once 'event-creation.php'; ?>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow flex flex-col justify-center items-center">
        <!-- Welcome Message -->
        <div class="text-center mb-10">
            <h2 class="text-3xl font-bold mb-2">Welcome, Organizer!</h2>
            <p class="text-lg text-gray-300">"Connecting with the right people leads to amazing events."</p>
        </div>

        <!-- Your Listed Events Section -->
        <section class="w-full max-w-4xl mb-12">
            <h3 class="text-2xl font-semibold mb-4">Your Listed Events</h3>
            <div class="bg-gray-800 p-4 rounded-lg">
                <!-- Example Event -->
                <div class="border-b border-gray-700 py-4">
                    <h4 class="text-xl font-semibold">Event Title 1</h4>
                    <p class="text-sm text-gray-400">Event Date: 2024-09-25 | Location: Online</p>
                </div>
                <div class="border-b border-gray-700 py-4">
                    <h4 class="text-xl font-semibold">Event Title 2</h4>
                    <p class="text-sm text-gray-400">Event Date: 2024-10-05 | Location: Seminar Hall</p>
                </div>
            </div>
        </section>

        <!-- Volunteer Submissions Tab -->
        <section class="w-full max-w-4xl">
            <h3 class="text-2xl font-semibold mb-4">Volunteer Submissions</h3>
            <div class="bg-gray-800 p-4 rounded-lg">
                <!-- Example Submission -->
                <div class="border-b border-gray-700 py-4">
                    <h4 class="text-xl font-semibold">Volunteer 1</h4>
                    <p class="text-sm text-gray-400">Submission Date: 2024-09-20</p>
                </div>
                <div class="border-b border-gray-700 py-4">
                    <h4 class="text-xl font-semibold">Volunteer 2</h4>
                    <p class="text-sm text-gray-400">Submission Date: 2024-09-22</p>
                </div>
            </div>
        </section>
    </main>
</body>

</html>
