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

// Query to fetch organizer details
$fetchOrganizerDetailsQuery = "SELECT * FROM user_profiles_org WHERE userid = ?";
$orgStmt = mysqli_stmt_init($con);
if (!mysqli_stmt_prepare($orgStmt, $fetchOrganizerDetailsQuery)) {
    echo "SQL error";
    exit();
}

// Bind the organizer ID parameter to the query and execute
mysqli_stmt_bind_param($orgStmt, "i", $organizerId);
mysqli_stmt_execute($orgStmt);
$orgResult = mysqli_stmt_get_result($orgStmt);

// Check if the organizer exists and fetch the details
if ($organizerDetails = mysqli_fetch_assoc($orgResult)) {
    $orgName = $organizerDetails['organization_name'];
    $jobTitle = $organizerDetails['job_title'];
    $industry = $organizerDetails['industry'];
    $location = $organizerDetails['location'];
    $officialAddress = $organizerDetails['official_address'];
    $officialContact = $organizerDetails['official_contact_number'];
} else {
    die("Error: Organizer not found.");
}

// Handle AJAX registration request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'register') {
    $userId = $_SESSION['usersid'];
    $insertQuery = "INSERT INTO requests (event_id, requests_usersId) VALUES (?, ?)";
    $stmt = mysqli_prepare($con, $insertQuery);
    mysqli_stmt_bind_param($stmt, "ii", $eventId, $userId);
    $result = mysqli_stmt_execute($stmt);
    
    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Registration successful']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Registration failed']);
    }
    exit;
}

mysqli_stmt_close($eventStmt);
mysqli_stmt_close($orgStmt); // Close the organizer details statement
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
    <style>
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .animate-fadeIn {
            animation: fadeIn 0.5s ease-out;
        }
    </style>
</head>
<body class="bg-black text-white min-h-screen flex flex-col"> 
    <?php include "header.php"; ?>

    <header class="bg-black py-6 shadow-lg"> 
        <div class="container mx-auto px-4">
            <h1 class="text-3xl font-bold text-center text-blue-400"><?php echo htmlspecialchars($eventName); ?></h1>
        </div>
    </header>

    <main class="flex-grow container mx-auto px-4 py-12 flex justify-center">
        <div class="max-w-4xl w-full bg-dark-light rounded-lg shadow-xl overflow-hidden"> 
            <div class="md:flex">
                <div class="md:w-1/2 p-8 animate-fadeIn">
                    <div id="event-banner" class="w-full h-64 rounded-lg shadow-lg overflow-hidden mb-6">
                        <div class="w-full h-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                            <div class="text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 mx-auto mb-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <h2 class="text-2xl font-bold mb-2 text-white"><?php echo htmlspecialchars($eventName); ?></h2>
                                <p class="text-gray-200"><?php echo date('F j, Y, h:i A', strtotime($eventDatetime)); ?></p> 
                            </div>
                        </div>
                    </div>
                    <div class="space-y-6">
                        <div>
                            <h2 class="text-xl font-semibold mb-2 text-blue-400">About the Event</h2>
                            <p class="text-gray-300"><?php echo htmlspecialchars($eventDescription); ?></p> 
                        </div>
                        <div>
                            <h2 class="text-xl font-semibold mb-2 text-blue-400">Event Details</h2>
                            <div class="space-y-2">
                                <div class="flex items-center space-x-2 text-gray-300"> 
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                    </svg>
                                    <span>Date: <?php echo date('F j, Y', strtotime($eventDatetime)); ?></span>
                                </div>
                                <div class="flex items-center space-x-2 text-gray-300"> 
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                    </svg>
                                    <span>Time: <?php echo date('h:i A', strtotime($eventDatetime)); ?></span>
                                </div>
                                <div class="flex items-center space-x-2 text-gray-300"> 
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                    </svg>
                                    <span>Location: <?php echo htmlspecialchars($eventLocation); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="md:w-1/2 p-8 bg-dark-light animate-fadeIn" style="animation-delay: 0.2s;"> 
                    <h2 class="text-xl font-semibold mb-4 text-blue-400">Organizer Details</h2>
                    <div class="space-y-4">
                        <div class="flex items-center space-x-2 text-gray-300"> 
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span>Organization: <?php echo htmlspecialchars($orgName); ?></span>
                        </div>
                        <div class="flex items-center space-x-2 text-gray-300"> 
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span>Job Title: <?php echo htmlspecialchars($jobTitle); ?>, <?php echo htmlspecialchars($industry); ?></span>
                        </div>
                        <div class="flex items-center space-x-2 text-gray-300"> 
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                            </svg>
                            <span>Location: <?php echo htmlspecialchars($location); ?></span>
                        </div>
                        <div class="flex items-center space-x-2 text-gray-300"> 
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span>Contact: <?php echo htmlspecialchars($officialContact); ?></span>
                        </div>
                    </div>
                    <!-- Register Button moved inside Organizer Details -->
                    <div class="mt-6 text-center">
                        <button id="register-btn" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300 ease-in-out text-lg transform hover:scale-105">
                            Register for the Event
                        </button>
                        <div id="registration-message" class="mt-4 text-lg"></div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-black py-6 mt-8"> 
        <div class="container mx-auto px-4 text-center text-gray-400">
            <p>© <?php echo date('Y'); ?> Volhub. All rights reserved.</p>
        </div>
    </footer>

    <script>
        // Handle registration
        const registerBtn = document.getElementById('register-btn');
        const registrationMessage = document.getElementById('registration-message');

        registerBtn.addEventListener('click', (e) => {
            e.preventDefault();
            registerBtn.disabled = true;
            registerBtn.textContent = 'Registering...';

            // Send AJAX request to register
            fetch(window.location.href, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=register'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    registerBtn.classList.add('hidden');
                    registrationMessage.textContent = data.message;
                    registrationMessage.classList.add('text-green-400', 'animate-fadeIn');
                } else {
                    registrationMessage.textContent = data.message;
                    registrationMessage.classList.add('text-red-400', 'animate-fadeIn');
                    registerBtn.disabled = false;
                    registerBtn.textContent = 'Register for the Event';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                registrationMessage.textContent = 'An error occurred. Please try again.';
                registrationMessage.classList.add('text-red-400', 'animate-fadeIn');
                registerBtn.disabled = false;
                registerBtn.textContent = 'Register for the Event';
            });
        });
    </script>
</body>
</html>