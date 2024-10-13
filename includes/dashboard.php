<?php
include_once "dbh.inc.php";
session_start();
$user_id = $_SESSION["usersid"];

if (!isset($_SESSION["usersid"])) {
    header("Location: ../pages/login/login.php?error=notLoggedIn");
    exit();
}

// SQL query to fetch upcoming events
$fetchEventsQuery = "SELECT * FROM events WHERE event_datetime >= CURDATE() AND reg_status = '1' AND admin_approve = '1'";
$eventStmt = mysqli_stmt_init($con);
if (!mysqli_stmt_prepare($eventStmt, $fetchEventsQuery)) {
    echo "SQL error";
    exit();
} else {
    mysqli_stmt_execute($eventStmt);
    $eventResult = mysqli_stmt_get_result($eventStmt);
}
mysqli_stmt_close($eventStmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        [x-cloak] { display: none; }
        html, body { height: 100%; margin: 0; padding: 0; }
    </style>
    <script src="https://unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-gradient-to-br from-black via-black to-gray-800">
    <?php include "header.php" ?>

    <div class="container mx-auto pt-5 pb-8">
        <div class="pt-5 flex flex-col items-center justify-center w-full max-w-full" 
             x-data="{
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
             }" 
             x-init="tabRepositionMarker($refs.tabButtons.firstElementChild);">

            <div class="relative w-full max-w-sm">
                <div x-ref="tabButtons" class="relative inline-grid items-center justify-center w-full h-10 grid-cols-2 p-1 text-gray-900 bg-gray-300 rounded-lg select-none">
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
                                            <?php echo htmlspecialchars(substr($event['event_description'], 0, 400)) . '...'; ?>
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
                            <p class="col-span-full text-center text-gray-500">No upcoming events found.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Tab Content 2 - Applied Events (Example, you can add actual logic here later) -->
                <div :id="$id(tabId + '-content')" x-show="tabContentActive($el)" class="relative" x-cloak>
                    <!-- You can populate the applied events in a similar manner -->
                </div>
            </div>
        </div>
    </div>
</body>
</html>
