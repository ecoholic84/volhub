<?php
include_once "dbh.inc.php";
session_start();
$user_id = $_SESSION["usersid"];

if (!isset($_SESSION["usersid"])) {
    header("Location: ../pages/login/login.php");
    exit();
}

// Fetch the user's profile data
$sql = "SELECT * FROM Events WHERE organizer_id=? ORDER BY event_datetime DESC";
$stmt = mysqli_stmt_init($con);
if (!mysqli_stmt_prepare($stmt, $sql)) {
    echo "SQL error";
} else {
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $events = mysqli_fetch_all($result, MYSQLI_ASSOC);
    if ($row = mysqli_fetch_assoc($result)) {
        $event_id = $row['event_id'];
        $organizer_id = $row['organizer_id'];
        $event_name = $row['event_name'];
        $event_description = $row['event_description'];
        $event_datetime = $row['event_datetime'];
        $event_location = $row['event_location'];
        $event_thumbnail = $row['event_thumbnail'];
        $created_at = $row['created_at'];
    }    
}
mysqli_stmt_close($stmt);
?>
<?php
// Adjust SQL query to exclude organizer_id
// $sql = "SELECT * FROM Events ORDER BY event_datetime DESC";
// $stmt = mysqli_stmt_init($con);

// if (!mysqli_stmt_prepare($stmt, $sql)) {
//     echo "SQL error";
// } else {
//     mysqli_stmt_execute($stmt);
//     $result = mysqli_stmt_get_result($stmt);
    
//     // Fetch all events in descending order of event_datetime
//     $events = mysqli_fetch_all($result, MYSQLI_ASSOC);

//     // Optionally: process or display events here
//     foreach ($events as $event) {
//         // Access event properties, e.g.:
//         echo "Event ID: " . $event['event_id'] . "<br>";
//         echo "Event Name: " . $event['event_name'] . "<br>";
//         echo "Event Description: " . $event['event_description'] . "<br>";
//         echo "Event Date/Time: " . $event['event_datetime'] . "<br>";
//         echo "Event Location: " . $event['event_location'] . "<br>";
//         echo "Event Thumbnail: " . $event['event_thumbnail'] . "<br>";
//         echo "Created At: " . $event['created_at'] . "<br><br>";
//     }
// }

// mysqli_stmt_close($stmt);
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
            display: none
        }
    </style>
    <script src="https://unpkg.com/alpinejs" defer></script>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <?php include "header.php" ?>

    <div class="pt-5 flex flex-col items-center justify-center w-full max-w-full"
        x-data="{
            tabSelected: 1,
            tabId: $id('tabs'),
            tabButtonClicked(tabButton){
                this.tabSelected = tabButton.id.replace(this.tabId + '-', '');
                this.tabRepositionMarker(tabButton);
            },
            tabRepositionMarker(tabButton){
                this.$refs.tabMarker.style.width=tabButton.offsetWidth + 'px';
                this.$refs.tabMarker.style.height=tabButton.offsetHeight + 'px';
                this.$refs.tabMarker.style.left=tabButton.offsetLeft + 'px';
            },
            tabContentActive(tabContent){
                return this.tabSelected == tabContent.id.replace(this.tabId + '-content-', '');
            }
        }"
        x-init="tabRepositionMarker($refs.tabButtons.firstElementChild);">

        <div class="relative w-full max-w-sm">
            <div x-ref="tabButtons" class="relative inline-grid items-center justify-center w-full h-10 grid-cols-2 p-1 text-gray-500 bg-gray-100 rounded-lg select-none">
                <button :id="$id(tabId)" @click="tabButtonClicked($el);" type="button" class="relative z-20 inline-flex items-center justify-center w-full h-8 px-3 text-sm font-medium transition-all rounded-md cursor-pointer whitespace-nowrap">Account</button>
                <button :id="$id(tabId)" @click="tabButtonClicked($el);" type="button" class="relative z-20 inline-flex items-center justify-center w-full h-8 px-3 text-sm font-medium transition-all rounded-md cursor-pointer whitespace-nowrap">Password</button>
                <div x-ref="tabMarker" class="absolute left-0 z-10 w-1/2 h-full duration-300 ease-out" x-cloak>
                    <div class="w-full h-full bg-white rounded-md shadow-sm"></div>
                </div>
            </div>
        </div>

        <div class="relative w-full max-w-7xl mt-6 content px-4">
            <div :id="$id(tabId + '-content')" x-show="tabContentActive($el)" class="relative">
                <!-- Tab Content 1 - Two-column grid layout -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Event Card -->
                    <?php if (empty($events)): ?>
                        <p class="col-span-full text-center text-gray-500">No events found. Create your first event!</p>
                    <?php else: ?>
                        <?php foreach ($events as $event): ?>
                            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                                <img class="w-full h-48 object-cover" src="<?php echo htmlspecialchars($event['event_thumbnail'] ?: 'https://via.placeholder.com/1200x500'); ?>" alt="Event Image">
                                <div class="px-6 py-4">
                                    <div class="font-bold text-2xl mb-2"><?php echo htmlspecialchars($event['event_name']); ?></div>
                                    <p class="text-gray-700 text-base">
                                        <?php echo htmlspecialchars(substr($event['event_description'], 0, 100)) . '...'; ?>
                                    </p>
                                </div>
                                <div class="px-6 py-4 flex items-center justify-between">
                                    <span class="text-gray-600 text-sm">Date: <?php echo date('F j, Y', strtotime($event['event_datetime'])); ?></span>
                                    <a href="view_event.php?id=<?php echo $event['event_id']; ?>" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                        Learn More
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                
                    <!-- <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                        <img class="w-full h-48 object-cover" src="https://via.placeholder.com/1200x500" alt="Event Image">
                        <div class="px-6 py-4">
                            <div class="font-bold text-2xl mb-2">Event Title</div>
                            <p class="text-gray-700 text-base">
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus lacinia odio vitae vestibulum.
                            </p>
                        </div>
                        <div class="px-6 py-4 flex items-center justify-between">
                            <span class="text-gray-600 text-sm">Date: September 20, 2024</span>
                            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Learn More
                            </button>
                        </div>
                    </div> -->

                    <!-- Additional Content Card -->

                    <!-- <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                        <div class="px-6 py-4">
                            <h2 class="font-bold text-2xl mb-2">Additional Information</h2>
                            <p class="text-gray-700 text-base mb-4">
                                This space can be used for more content, statistics, or any other relevant information you'd like to display alongside the event card.
                            </p>
                            <ul class="list-disc list-inside text-gray-700">
                                <li>Important point 1</li>
                                <li>Key information 2</li>
                                <li>Relevant detail 3</li>
                            </ul>
                        </div>
                    </div>
                </div> -->
                <!-- End Tab Content 1 -->
            </div>

            <div :id="$id(tabId + '-content')" x-show="tabContentActive($el)" class="relative" x-cloak>
                <!-- Tab Content 2 - Password change form -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="border rounded-lg shadow-sm bg-card text-neutral-900">
                        <div class="flex flex-col space-y-1.5 p-6">
                            <h3 class="text-lg font-semibold leading-none tracking-tight">Password</h3>
                            <p class="text-sm text-neutral-500">Change your password here. After saving, you'll be logged out.</p>
                        </div>
                        <div class="p-6 pt-0 space-y-2">
                            <div class="space-y-1"><label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="password">Current Password</label><input type="password" placeholder="Current Password" id="password" class="flex w-full h-10 px-3 py-2 text-sm bg-white border rounded-md peer border-neutral-300 ring-offset-background placeholder:text-neutral-400 focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 disabled:cursor-not-allowed disabled:opacity-50" /></div>
                            <div class="space-y-1"><label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="password_new">New Password</label><input type="password" placeholder="New Password" id="password_new" class="flex w-full h-10 px-3 py-2 text-sm bg-white border rounded-md border-neutral-300 ring-offset-background placeholder:text-neutral-400 focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 disabled:cursor-not-allowed disabled:opacity-50" /></div>
                        </div>
                        <div class="flex items-center p-6 pt-0"><button type="button" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium tracking-wide text-white transition-colors duration-200 rounded-md bg-neutral-950 hover:bg-neutral-900 focus:ring-2 focus:ring-offset-2 focus:ring-neutral-900 focus:shadow-outline focus:outline-none">Save password</button></div>
                    </div>
                    
                    <!-- Additional Content for Password Tab -->
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                        <div class="px-6 py-4">
                            <h2 class="font-bold text-2xl mb-2">Password Security Tips</h2>
                            <ul class="list-disc list-inside text-gray-700">
                                <li>Use a mix of letters, numbers, and symbols</li>
                                <li>Make your password at least 12 characters long</li>
                                <li>Avoid using personal information</li>
                                <li>Use a unique password for each account</li>
                                <li>Consider using a password manager</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- End Tab Content 2 -->
            </div>
        </div>
    </div>
</body>
</html>