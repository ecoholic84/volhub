<?php
session_start();
include_once "dbh.inc.php";

// Check if user is logged in as admin
if (!isset($_SESSION["usersid"]) || $_SESSION["role"] !== 'admin') {
    header("Location: /volhub/pages/login/login.php");
    exit();
}

// Handle event approval/rejection
if (isset($_POST['approve_event']) || isset($_POST['reject_event'])) {
    $eventId = $_POST['event_id'];
    $status = isset($_POST['approve_event']) ? 1 : 0;

    $sql = "UPDATE events SET admin_approve = ? WHERE event_id = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $status, $eventId);

    if (mysqli_stmt_execute($stmt)) {
        $message = "Event " . ($status ? 'approved' : 'rejected') . " successfully!";
    } else {
        $message = "Error updating event status: " . mysqli_error($con);
    }
    mysqli_stmt_close($stmt);
}

// Fetch all events
$sql_events = "SELECT e.*, u.usersEmail as organizer_email, up.full_name as organizer_name, upo.organization_name 
               FROM events e
               LEFT JOIN users u ON e.organizer_id = u.usersId
               LEFT JOIN user_profiles up ON u.usersId = up.profile_usersId
               LEFT JOIN user_profiles_org upo ON u.usersId = upo.userid
               ORDER BY e.event_id DESC";
$result_events = mysqli_query($con, $sql_events);

// Fetch user statistics
$totalUsers = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as count FROM users"))['count'];
$totalEvents = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as count FROM events"))['count'];
$totalVolunteers = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as count FROM users WHERE volunteer = 1"))['count'];
$totalOrganizers = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as count FROM users WHERE organizer = 1"))['count'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'custom-gray': '#1a1a1a',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-black text-gray-200">
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-6 text-white">Admin Dashboard</h1>
        <?php if(isset($message)): ?>
            <div id="notification" class="fixed top-4 right-4 bg-green-500 text-white p-4 rounded mb-4 flex justify-between items-center">
                <span><?php echo $message; ?></span>
                <button onclick="closeNotification()" class="ml-4 text-white">&times;</button>
            </div>
        <?php endif; ?>

        <!-- User Statistics -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-blue-600 rounded-lg p-6">
                <h3 class="text-white font-semibold mb-2">Total Users</h3>
                <p class="text-3xl font-bold text-white"><?php echo $totalUsers; ?></p>
            </div>
            <div class="bg-green-600 rounded-lg p-6">
                <h3 class="text-white font-semibold mb-2">Total Events</h3>
                <p class="text-3xl font-bold text-white"><?php echo $totalEvents; ?></p>
            </div>
            <div class="bg-yellow-600 rounded-lg p-6">
                <h3 class="text-white font-semibold mb-2">Total Volunteers</h3>
                <p class="text-3xl font-bold text-white"><?php echo $totalVolunteers; ?></p>
            </div>
            <div class="bg-purple-600 rounded-lg p-6">
                <h3 class="text-white font-semibold mb-2">Total Organizers</h3>
                <p class="text-3xl font-bold text-white"><?php echo $totalOrganizers; ?></p>
            </div>
        </div>

        <!-- Events List -->
        <h2 class="text-2xl font-semibold mb-4 text-white">Events</h2>
        
        <!-- Search and Sort -->
        <div class="mb-4 flex flex-col sm:flex-row gap-4">
            <input type="text" id="searchInput" placeholder="Search events..." class="bg-custom-gray text-white rounded-lg p-2 flex-grow">
            <select id="sortSelect" class="bg-custom-gray text-white rounded-lg p-2">
                <option value="name">Sort by Name</option>
                <option value="date">Sort by Date</option>
                <option value="status">Sort by Status</option>
            </select>
        </div>

        <div id="eventsList" class="space-y-4">
            <?php while ($event = mysqli_fetch_assoc($result_events)): ?>
                <div class="bg-custom-gray rounded-lg p-4 event-card" data-event-name="<?php echo strtolower($event['event_name']); ?>" data-event-date="<?php echo $event['event_datetime']; ?>" data-event-status="<?php echo $event['admin_approve']; ?>">
                    <div class="flex justify-between items-center cursor-pointer event-header" data-event-id="<?php echo $event['event_id']; ?>">
                        <h3 class="text-lg font-semibold text-white"><?php echo $event['event_name']; ?></h3>
                        <div class="flex items-center">
                            <?php
                            if($event['admin_approve'] == 1){
                                echo '<span class="w-3 h-3 bg-green-500 rounded-full mr-2"></span>';
                            } elseif($event['admin_approve'] == 0 && $event['reg_status'] == 1) {
                                echo '<span class="w-3 h-3 bg-yellow-500 rounded-full mr-2"></span>';
                            } else {
                                echo '<span class="w-3 h-3 bg-red-500 rounded-full mr-2"></span>';
                            }
                            ?>
                            <svg class="w-6 h-6 text-white transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                    <div class="event-details hidden mt-4" id="event-<?php echo $event['event_id']; ?>">
                        <p class="mb-2"><strong class="text-white">Description:</strong> <?php echo $event['event_description']; ?></p>
                        <p class="mb-2"><strong class="text-white">Date/Time:</strong> <?php echo $event['event_datetime']; ?></p>
                        <p class="mb-2"><strong class="text-white">Location:</strong> <?php echo $event['event_location']; ?></p>
                        <p class="mb-2"><strong class="text-white">Organizer:</strong> <?php echo $event['organizer_name']; ?> (<?php echo $event['organization_name']; ?>)</p>
                        <p class="mb-2"><strong class="text-white">Organizer Email:</strong> <?php echo $event['organizer_email']; ?></p>
                        <p class="mb-4"><strong class="text-white">Status:</strong>
                            <?php
                                if($event['admin_approve'] == 1){
                                    echo '<span class="text-green-500">Approved</span>';
                                } else if($event['admin_approve'] == 0 && $event['reg_status'] == 1) {
                                    echo '<span class="text-yellow-500">Pending</span>';
                                } else {
                                    echo '<span class="text-red-500">Rejected</span>';
                                }
                            ?>
                        </p>
                        <form method="post" class="space-x-2">
                            <input type="hidden" name="event_id" value="<?php echo $event['event_id']; ?>">
                            <?php if($event['admin_approve'] == 0): ?>
                                <button type="submit" name="approve_event" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition duration-300">Approve</button>
                            <?php endif; ?>
                            <?php if($event['admin_approve'] == 1): ?>
                                <button type="submit" name="reject_event" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded transition duration-300">Decline</button>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    const eventHeaders = document.querySelectorAll('.event-header');
    const searchInput = document.getElementById('searchInput');
    const sortSelect = document.getElementById('sortSelect');
    const eventsList = document.getElementById('eventsList');

    // Toggle event details
    eventHeaders.forEach(header => {
        header.addEventListener('click', function() {
            const eventId = this.getAttribute('data-event-id');
            const details = document.getElementById(`event-${eventId}`);
            const arrow = this.querySelector('svg');
            details.classList.toggle('hidden');
            arrow.classList.toggle('rotate-180');
        });
    });

    // Search functionality
    searchInput.addEventListener('input', filterEvents);

    // Sort functionality
    sortSelect.addEventListener('change', sortEvents);

    function filterEvents() {
        const searchTerm = searchInput.value.toLowerCase();
        const events = document.querySelectorAll('.event-card');

        events.forEach(event => {
            const eventName = event.getAttribute('data-event-name');
            if (eventName.includes(searchTerm)) {
                event.style.display = '';
            } else {
                event.style.display = 'none';
            }
        });
    }

    function sortEvents() {
        const sortBy = sortSelect.value;
        const events = Array.from(document.querySelectorAll('.event-card'));

        events.sort((a, b) => {
            switch (sortBy) {
                case 'name':
                    return a.getAttribute('data-event-name').localeCompare(b.getAttribute('data-event-name'));
                case 'date':
                    return new Date(a.getAttribute('data-event-date')) - new Date(b.getAttribute('data-event-date'));
                case 'status':
                    return b.getAttribute('data-event-status') - a.getAttribute('data-event-status');
                case 'pending':
                    return sortByStatus(a, b, '0', '1');
                case 'approved':
                    return sortByStatus(a, b, '1', '1');
                case 'rejected':
                    return sortByStatus(a, b, '0', '0');
                default:
                    return 0;
            }
        });

        events.forEach(event => eventsList.appendChild(event));
    }

    function sortByStatus(a, b, adminApprove, regStatus) {
        const aMatch = a.getAttribute('data-event-status') === adminApprove && a.getAttribute('data-event-reg-status') === regStatus;
        const bMatch = b.getAttribute('data-event-status') === adminApprove && b.getAttribute('data-event-reg-status') === regStatus;
        
        if (aMatch && !bMatch) return -1;
        if (!aMatch && bMatch) return 1;
        return 0;
    }

    // Notification handling
    const notification = document.getElementById('notification');
    if (notification) {
        setTimeout(() => {
            closeNotification();
        }, 2000);
    }
});

function closeNotification() {
    const notification = document.getElementById('notification');
    if (notification) {
        notification.style.display = 'none';
    }
}
</script>
</body>
</html>

