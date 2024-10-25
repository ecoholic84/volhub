<?php
include_once "dbh.inc.php";
session_start();
$user_id = $_SESSION["usersid"];

if (!isset($_SESSION["usersid"])) {
    header("Location: ../pages/login/login.php?error=notLoggedIn");
    exit();
}

// SQL query to fetch upcoming events (excluding those the user has applied for)
$fetchEventsQuery = "SELECT * FROM events 
                    WHERE event_datetime >= CURDATE() AND reg_status = '1' AND admin_approve = '1'
                    AND event_id NOT IN (SELECT event_id FROM requests WHERE requests_usersId = ? AND request_status = 'pending')"; 
$eventStmt = mysqli_stmt_init($con);
if (!mysqli_stmt_prepare($eventStmt, $fetchEventsQuery)) {
    echo "SQL error";
    exit();
} else {
    mysqli_stmt_bind_param($eventStmt, "i", $user_id);
    mysqli_stmt_execute($eventStmt);
    $eventResult = mysqli_stmt_get_result($eventStmt);
}
mysqli_stmt_close($eventStmt);

// SQL query to fetch applied events for the logged-in user
$fetchAppliedEventsQuery = "SELECT e.*, r.request_status FROM events e 
                            INNER JOIN requests r ON e.event_id = r.event_id 
                            WHERE r.requests_usersId = ? AND e.event_datetime >= CURDATE()";
$appliedEventStmt = mysqli_stmt_init($con);
if (!mysqli_stmt_prepare($appliedEventStmt, $fetchAppliedEventsQuery)) {
    echo "SQL error";
    exit();
} else {
    mysqli_stmt_bind_param($appliedEventStmt, "i", $user_id);
    mysqli_stmt_execute($appliedEventStmt);
    $appliedEventResult = mysqli_stmt_get_result($appliedEventStmt);
}
mysqli_stmt_close($appliedEventStmt);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
    [x-cloak] {
        display: none;
    }

    html,
    body {
        height: 100%;
        margin: 0;
        padding: 0;
    }
    </style>
    <script src="https://unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-gradient-to-br from-black via-black to-gray-800">
    <?php include "header.php" ?>

    <div class="container mx-auto pt-5 pb-8">

        <!-- Welcome Section -->
        <!-- <div class="bg-gray-200 rounded-lg shadow-lg p-6 mb-6">
            <h2 class="text-3xl font-bold mb-4 text-gray-800">Welcome to Your Dashboard,
                <?php echo isset($_SESSION["usersName"]) ? htmlspecialchars($_SESSION["usersName"]) : "User"; ?>!
            </h2>
            <p class="text-gray-700">
                Explore upcoming events, manage your applications, and connect with other volunteers and organizers.
            </p>
        </div> -->

        <!-- Search and Sort Section -->
        <div class="flex items-center justify-between mb-6">
            <div class="relative">
                <input type="text" placeholder="Search events..."
                    class="pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                        <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>
            <div>
                <select class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">Sort By</option>
                    <option value="date">Date</option>
                    <option value="name">Name</option>
                    <!-- Add more sorting options as needed -->
                </select>
            </div>
        </div>

        <!-- Tabs and Event Cards Section -->
        <div class="pt-5 flex flex-col items-center justify-center w-full max-w-full" x-data="{
                tabSelected: 1,
                tabId: $id('tabs'),
                tabButtonClicked(tabButton) {
                    this.tabSelected = tabButton.id.replace(this.tabId + '-', '');
                    this.tabRepositionMarker(tabButton);
                },
                tabRepositionMarker(tabButton) {
                    this.$refs.tabMarker.style.width = tabButton.offsetWidth + 'px';
                    this.$refs.tabMarker.style.height = tabButton.offsetHeight + 'px';
                    this.$refs.tabMarker.style.left = tabButton.offsetLeft + 'px';
                },
                tabContentActive(tabContent) {
                    return this.tabSelected == tabContent.id.replace(this.tabId + '-content-', '');
                }
             }" x-init="tabRepositionMarker($refs.tabButtons.firstElementChild);">

            <div class="relative w-full max-w-sm">
                <div x-ref="tabButtons"
                    class="relative inline-grid items-center justify-center w-full h-10 grid-cols-2 p-1 text-gray-900 bg-gray-300 rounded-lg select-none">
                    <button :id="$id(tabId)" @click="tabButtonClicked($el);" type="button"
                        class="relative z-20 inline-flex items-center justify-center w-full h-8 px-3 text-sm font-medium transition-all rounded-md cursor-pointer whitespace-nowrap">
                        Upcoming Events
                    </button>
                    <button :id="$id(tabId)" @click="tabButtonClicked($el);" type="button"
                        class="relative z-20 inline-flex items-center justify-center w-full h-8 px-3 text-sm font-medium transition-all rounded-md cursor-pointer whitespace-nowrap">
                        Applied Events
                    </button>
                    <div x-ref="tabMarker" class="absolute left-0 z-10 w-1/2 h-full duration-300 ease-out" x-cloak>
                        <div class="w-full h-full bg-white rounded-md shadow-sm"></div>
                    </div>
                </div>
            </div>

            <div class="relative w-full max-w-7xl mt-6 content px-4">
                <!-- Tab Content 1 - Upcoming Events -->
                <div :id="$id(tabId + '-content')" x-show="tabContentActive($el)" class="relative">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <?php if (mysqli_num_rows($eventResult) > 0): ?>
                        <?php while ($event = mysqli_fetch_assoc($eventResult)): ?>
                        <div class="bg-gray-200 rounded-lg shadow-lg overflow-hidden flex flex-col">
                            <div class="px-6 py-4 flex-grow">
                                <div class="font-bold text-2xl mb-2">
                                    <?php echo htmlspecialchars($event['event_name']); ?>
                                </div>
                                <p class="text-gray-700 text-base">
                                    <?php 
                                    $description = htmlspecialchars($event['event_description']);
                                    $short_description = substr($description, 0, 400);
                                    if (strlen($description) > 400) {
                                        $short_description .= '...';
                                    }
                                    echo $short_description; 
                                    ?>
                                </p>
                            </div>
                            <div class="px-6 py-4 mt-auto flex items-center justify-between">
                                <span class="text-gray-600 text-sm">Date:
                                    <?php echo date('F j, Y', strtotime($event['event_datetime'])); ?>
                                </span>
                                <a href="event-page.php?id=<?php echo $event['event_id']; ?>"
                                    class="bg-indigo-500 hover:bg-indigo-600 text-white font-bold py-2 px-4 rounded">
                                    Learn More
                                </a>
                            </div>
                        </div>
                        <?php endwhile; ?>
                        <?php else: ?>
                        <div class="col-span-full bg-gray-200 rounded-lg shadow-lg overflow-hidden flex flex-col p-6">
                            <p class="text-center text-gray-500">No upcoming events found.</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Tab Content 2 - Applied Events -->
                <div :id="$id(tabId + '-content')" x-show="tabContentActive($el)" class="relative" x-cloak>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <?php if (mysqli_num_rows($appliedEventResult) > 0): ?>
                        <?php while ($event = mysqli_fetch_assoc($appliedEventResult)): ?>
                        <div class="bg-gray-200 rounded-lg shadow-lg overflow-hidden flex flex-col relative">
                            <div class="px-6 py-4 flex-grow">
                                <div class="font-bold text-2xl mb-2">
                                    <?php echo htmlspecialchars($event['event_name']); ?>
                                </div>
                                <p class="text-gray-700 text-base">
                                    <?php 
                                    $description = htmlspecialchars($event['event_description']);
                                    $short_description = substr($description, 0, 400);
                                    if (strlen($description) > 400) {
                                        $short_description .= '...';
                                    }
                                    echo $short_description; 
                                    ?>
                                </p>
                            </div>
                            <div class="px-6 py-4 mt-auto flex items-center justify-between">
                                <span class="text-gray-600 text-sm">Date:
                                    <?php echo date('F j, Y', strtotime($event['event_datetime'])); ?>
                                </span>
                                <a href="event-page.php?id=<?php echo $event['event_id']; ?>"
                                    class="bg-indigo-500 hover:bg-indigo-600 text-white font-bold py-2 px-4 rounded">
                                    View Details
                                </a>
                            </div>

                            <!-- Application Status Badge -->
                            <div class="absolute top-4 right-4 px-2 py-1 rounded-full text-xs font-bold 
                                         <?php 
                                         switch ($event['request_status']) {
                                             case 'pending': echo 'bg-yellow-300 text-gray-800'; break; // Awaiting Review
                                             case 'approved': echo 'bg-green-400 text-white'; break; // Approved
                                             case 'rejected': echo 'bg-red-400 text-white'; break; // Rejected
                                         } 
                                         ?>">
                                <?php 
                                switch ($event['request_status']) {
                                    case 'pending': echo 'Awaiting Review'; break;
                                    case 'approved': echo 'Approved'; break;
                                    case 'rejected': echo 'Rejected'; break;
                                } 
                                ?>
                            </div>
                        </div>
                        <?php endwhile; ?>
                        <?php else: ?>
                        <div class="col-span-full bg-gray-200 rounded-lg shadow-lg overflow-hidden flex flex-col p-6">
                            <p class="text-center text-gray-500">You have not applied for any upcoming events.</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>