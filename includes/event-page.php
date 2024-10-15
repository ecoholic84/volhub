<?php
include_once "dbh.inc.php";
session_start();

// Check if user is logged in
if (!isset($_SESSION["usersid"])) {
    header("Location: miniProject/pages/login/login.php?error=notLoggedIn");
    exit();
}

// Get the event ID from the URL
if (isset($_GET['id'])) {
    $eventId = $_GET['id'];
} else {
    // Handle the error if no event ID is provided
    header("Location: dashboard.php?error=noEventId");
    exit();
}

// Query to fetch the event details
$fetchEventDetailsQuery = "SELECT * FROM events WHERE event_id = ?";
$eventStmt = mysqli_stmt_init($con);
if (!mysqli_stmt_prepare($eventStmt, $fetchEventDetailsQuery)) {
    echo "SQL error";
    exit();
}

// Bind the event ID parameter to the query and execute
mysqli_stmt_bind_param($eventStmt, "i", $eventId);
mysqli_stmt_execute($eventStmt);
$eventResult = mysqli_stmt_get_result($eventStmt);

// Check if the event exists and fetch the details
if ($eventDetails = mysqli_fetch_assoc($eventResult)) {
    $eventId = $eventDetails['event_id'];
    $organizerId = $eventDetails['organizer_id'];
    $eventName = $eventDetails['event_name'];
    $eventDescription = $eventDetails['event_description'];
    $eventDatetime = $eventDetails['event_datetime'];
    $eventLocation = $eventDetails['event_location'];
    $eventThumbnail = $eventDetails['event_thumbnail'];
    $createdAt = $eventDetails['created_at'];
    $regStatus = $eventDetails['reg_status'];
    $adminApprove = $eventDetails['admin_approve'];
} else {
    die("Error: Event not found.");
}

if(isset($_POST['register-btn']))
{
    $insetQuery = "INSERT INTO requests (event_id, requests_usersId) VALUES ($eventId, $_SESSION[usersid])";
    $resul=mysqli_query($con, $insetQuery);
    if($resul)
    { 
        ?>
        <script>
            const registerBtn = document.getElementById('register-btn');
            const registrationMessage = document.getElementById('registration-message');

            registerBtn.disabled = true;
            registerBtn.textContent = 'Registering...';

            setTimeout(() => {
                registerBtn.classList.add('hidden');
                registrationMessage.textContent = \"Thank you for registering! We'll be in touch soon with more details.\";
                registrationMessage.classList.add('text-green-400');
            }, 1500);
        </script>
        <?php
    }
}


mysqli_stmt_close($eventStmt);
mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($eventName); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'dark': '#1a1a1a',
                        'dark-light': '#2a2a2a',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">
<?php include "header.php"; ?>

    <header class="bg-gray-800 py-4">
        <div class="container mx-auto px-4">
            <h1 class="text-2xl font-bold"><?php echo htmlspecialchars($eventName); ?></h1>
        </div>
    </header>

    <main class="flex-grow container mx-auto px-4 py-8 flex justify-center">
        <div class="max-w-4xl w-full">
            <div class="md:col-span-3 space-y-6">
                <div id="event-banner" class="w-full h-64 rounded-lg shadow-lg overflow-hidden">
                    <div class="w-full h-full bg-gray-700 flex items-center justify-center">
                        <div class="text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 mx-auto mb-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <h2 class="text-2xl font-bold mb-2"><?php echo htmlspecialchars($eventName); ?></h2>
                            <p class="text-gray-300"><?php echo date('F j, Y, h:i A', strtotime($eventDatetime)); ?></p>
                        </div>
                    </div>
                </div>
                <div class="space-y-4">
                    <h2 class="text-xl font-semibold text-center">About the Event</h2>
                    <p class="text-gray-300 text-center"><?php echo htmlspecialchars($eventDescription); ?></p>
                </div>
                <div class="space-y-4">
                    <h2 class="text-xl font-semibold text-center">Event Details</h2>
                    <div class="space-y-2">
                        <div class="flex items-center space-x-2 text-gray-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                            </svg>
                            <span><?php echo date('F j, Y, h:i A', strtotime($eventDatetime)); ?></span>
                        </div>
                        <div class="flex items-center space-x-2 text-gray-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                            </svg>
                            <span><?php echo htmlspecialchars($eventLocation); ?></span>
                        </div>
                        <div class="flex items-center space-x-2 text-gray-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                            </svg>
                            <span>Organizer ID: <?php echo htmlspecialchars($organizerId); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <div class="container mx-auto px-4 py-8 text-center">
    <form method="POST">
    <button id="register-btn" name="register-btn" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300 ease-in-out text-lg">
        Register for the Event
    </button>
</form>

        <div id="registration-message" class="mt-4 text-lg"></div>
    </div>

    <footer class="bg-gray-800 py-4 mt-8">
        <div class="container mx-auto px-4 text-center text-gray-400">
            <p>&copy; Volhub. All rights reserved.</p>
        </div>
    </footer>

    <!-- <script>
        // Handle registration
        const registerBtn = document.getElementById('register-btn');
        const registrationMessage = document.getElementById('registration-message');

        registerBtn.addEventListener('click', (e) => {
            e.preventDefault();
            registerBtn.disabled = true;
            registerBtn.textContent = 'Registering...';

            // Simulated registration process
            setTimeout(() => {
                registerBtn.classList.add('hidden');
                registrationMessage.textContent = "Thank you for registering! We'll be in touch soon with more details.";
                registrationMessage.classList.add('text-green-400');
            }, 1500);
        });
    </script> -->
</body>
</html>
