<?php
include_once "dbh.inc.php";
session_start();

// Check if user is logged in
if (!isset($_SESSION["usersid"])) {
    header("Location: miniProject/pages/login/login.php?error=notLoggedIn");
    exit();
}

// Get the event ID from the URL or POST (after form submission)
if (isset($_GET['id'])) {
    $eventId = $_GET['id'];
} else if (isset($_POST['event_id'])) {
    $eventId = $_POST['event_id'];
} else {
    header("Location: whereareyou?error=noEventId");
    exit();
}

// Handle form submission to update event details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newDescription = $_POST['event_description'];
    $newDate = $_POST['event_date'];
    $newLocation = $_POST['event_location'];
    $newOrganizer = $_POST['event_organizer'];

    // Prepare the update query
    $updateQuery = "UPDATE events SET event_description = ?, event_datetime = ?, event_location = ?, organizer_id = ? WHERE event_id = ?";
    $updateStmt = mysqli_prepare($con, $updateQuery);
    mysqli_stmt_bind_param($updateStmt, "sssii", $newDescription, $newDate, $newLocation, $newOrganizer, $eventId);
    
    if (mysqli_stmt_execute($updateStmt)) {
        header("Location: event-tab.php?id=$eventId&success=eventUpdated");
        exit();
    } else {
        echo "Error updating event: " . mysqli_error($con);
    }
}

// Fetch event details from the database
$query = "SELECT * FROM events WHERE event_id = ?";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, "i", $eventId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($event = mysqli_fetch_assoc($result)) {
    // Event found
} else {
    header("Location: whereareyou?error=eventNotFound");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/lucide-static@0.321.0/font/lucide.min.css" rel="stylesheet">
    <script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    dark: '#121212',
                    'dark-light': '#1e1e1e',
                    'dark-lighter': '#2a2a2a',
                }
            }
        }
    }
    </script>
</head>

<body class="min-h-screen bg-dark text-gray-200 flex flex-col">
    <?php include "header.php" ?>

    <main class="flex-grow container mx-auto px-4 py-3">
        <div class="mb-4 overflow-x-auto">
            <div class="flex space-x-2 md:space-x-4">
                <button
                    class="tab-button py-2 px-3 md:px-4 text-sm font-medium text-center whitespace-nowrap text-gray-400 hover:text-gray-200"
                    data-tab="event-details">Event Details</button>
                <button
                    class="tab-button py-2 px-3 md:px-4 text-sm font-medium text-center whitespace-nowrap text-gray-400 hover:text-gray-200"
                    data-tab="applied-users">Applied Users</button>
                <button
                    class="tab-button py-2 px-3 md:px-4 text-sm font-medium text-center whitespace-nowrap text-gray-400 hover:text-gray-200"
                    data-tab="approved-users">Approved Users</button>
                <button
                    class="tab-button py-2 px-3 md:px-4 text-sm font-medium text-center whitespace-nowrap text-gray-400 hover:text-gray-200"
                    data-tab="declined-users">Declined Users</button>
            </div>
        </div>

        <div class="mt-6 bg-dark-light p-6 rounded-lg">
            <div id="event-details" class="tab-content">
                <form id="event-form" method="POST" action="">
                    <input type="hidden" name="event_id" value="<?php echo htmlspecialchars($event['event_id']); ?>">
                    <div class="space-y-6">
                        <div
                            class="aspect-video bg-dark-lighter rounded-lg flex items-center justify-center mx-auto w-full md:w-3/4 lg:w-1/2">
                            <i class="lucide-calendar h-16 w-16 md:h-14 md:w-14 lg:h-12 lg:w-12 text-gray-600"></i>
                        </div>
                        <div class="space-y-4">
                            <h2 class="text-xl font-semibold">About the Event</h2>
                            <textarea id="event-description" name="event_description" class="w-full bg-transparent border-none resize-none"
                                rows="3" disabled><?php echo htmlspecialchars($event['event_description']); ?></textarea>
                        </div>
                        <div class="space-y-4">
                            <h2 class="text-xl font-semibold">Event Details</h2>
                            <div class="space-y-2">
                                <div class="flex items-center space-x-2 text-gray-400">
                                    <i class="lucide-calendar h-5 w-5"></i>
                                    <input id="event-date" name="event_date" type="text"
                                        value="<?php echo htmlspecialchars($event['event_datetime']); ?>" disabled
                                        class="bg-transparent border-none">
                                </div>
                                <div class="flex items-center space-x-2 text-gray-400">
                                    <i class="lucide-map-pin h-5 w-5"></i>
                                    <input id="event-location" name="event_location" type="text"
                                        value="<?php echo htmlspecialchars($event['event_location']); ?>" disabled
                                        class="bg-transparent border-none">
                                </div>
                                <div class="flex items-center space-x-2 text-gray-400">
                                    <i class="lucide-user h-5 w-5"></i>
                                    <input id="event-organizer" name="event_organizer" type="text"
                                        value="<?php echo htmlspecialchars($event['organizer_id']); ?>" disabled
                                        class="bg-transparent border-none">
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <button id="edit-btn"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Edit Event
                            </button>
                            <div id="save-cancel-btns" class="hidden space-x-2 mt-2">
                                <button id="save-btn" type="submit"
                                    class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                    Save
                                </button>
                                <button id="cancel-btn" type="button"
                                    class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div id="applied-users" class="tab-content hidden">
                <h2 class="text-xl font-semibold mb-4">Applied Users</h2>
                <p class="text-gray-400">List of users who have applied for this event will be displayed here.</p>
            </div>

            <div id="approved-users" class="tab-content hidden">
                <h2 class="text-xl font-semibold mb-4">Approved Users</h2>
                <p class="text-gray-400">List of users approved for this event will be displayed here.</p>
            </div>

            <div id="declined-users" class="tab-content hidden">
                <h2 class="text-xl font-semibold mb-4">Declined Users</h2>
                <?php
                $select = "SELECT * FROM user_profiles up 
                        INNER JOIN requests re 
                        ON up.profile_usersId = re.requests_usersId 
                        AND up.profile_usersId = '$_SESSION[usersid]' 
                        WHERE re.request_status = 'rejected'";
                
                $res = mysqli_query($con, $select);

                if(mysqli_num_rows($res) > 0) {
                    while ($row = mysqli_fetch_assoc($res)) {
                ?>
                <div class="mb-4 bg-dark-lighter p-4 rounded-lg">
                    <h3 class="text-lg font-semibold"><?php echo htmlspecialchars($row['username']); ?></h3>
                    <p class="text-gray-400">User ID: <?php echo htmlspecialchars($row['profile_usersId']); ?></p>
                </div>
                <?php
                    }
                } else {
                    echo "<p class='text-gray-400'>No declined users found for this event.</p>";
                }
                ?>
            </div>
            </div>
    </main>

    <footer class="bg-dark-light py-4 mt-8">
        <div class="container mx-auto px-4 text-center text-gray-400">
            <p>&copy; <span id="current-year"></span> Volhub. All rights reserved.</p>
        </div>
    </footer>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // Tab switching functionality
        const tabButtons = document.querySelectorAll('.tab-button');
        const tabContents = document.querySelectorAll('.tab-content');

        tabButtons.forEach(button => {
            button.addEventListener('click', () => {
                const tabId = button.getAttribute('data-tab');

                tabButtons.forEach(btn => {
                    btn.classList.remove('text-blue-500', 'border-b-2', 'border-blue-500');
                    btn.classList.add('text-gray-400', 'hover:text-gray-200');
                });

                tabContents.forEach(content => {
                    content.classList.add('hidden');
                });

                button.classList.add('text-blue-500', 'border-b-2', 'border-blue-500');
                button.classList.remove('text-gray-400', 'hover:text-gray-200');

                const selectedTab = document.getElementById(tabId);
                if (selectedTab) {
                    selectedTab.classList.remove('hidden');
                } else {
                    console.error(`Tab content with id "${tabId}" not found`);
                }
            });
        });

        // Set the first tab as active by default
        if (tabButtons.length > 0 && tabContents.length > 0) {
            tabButtons[0].classList.add('text-blue-500', 'border-b-2', 'border-blue-500');
            tabContents[0].classList.remove('hidden');
        }

        // Edit button functionality
        const editBtn = document.getElementById('edit-btn');
        const saveCancelBtns = document.getElementById('save-cancel-btns');
        const inputs = document.querySelectorAll('input, textarea');

        editBtn.addEventListener('click', (e) => {
            e.preventDefault();
            inputs.forEach(input => input.disabled = false);
            editBtn.classList.add('hidden');
            saveCancelBtns.classList.remove('hidden');
        });

        // Cancel button functionality
        const cancelBtn = document.getElementById('cancel-btn');
        cancelBtn.addEventListener('click', () => {
            inputs.forEach(input => input.disabled = true);
            editBtn.classList.remove('hidden');
            saveCancelBtns.classList.add('hidden');
        });
    });
    </script>
</body>

</html>