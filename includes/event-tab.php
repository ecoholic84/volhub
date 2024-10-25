<?php
include_once "dbh.inc.php";
session_start();

// Check if user is logged in
if (!isset($_SESSION["usersid"])) {
    header("Location: miniProject/pages/login/login.php?error=notLoggedIn");
    exit();
}

// Get the event ID
$eventId = $_GET['id'] ?? $_POST['event_id'] ?? null;
if (!$eventId) {
    header("Location: index.php?error=noEventId");
    exit();
}

// Handle event updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'update_event') {
        $newDescription = $_POST['event_description'];
        $newDate = $_POST['event_date'];
        $newLocation = $_POST['event_location'];
        $newOrganizer = $_POST['event_organizer'];

        $updateQuery = "UPDATE events SET event_description = ?, event_datetime = ?, event_location = ?, organizer_id = ? WHERE event_id = ?";
        $updateStmt = mysqli_prepare($con, $updateQuery);
        mysqli_stmt_bind_param($updateStmt, "sssii", $newDescription, $newDate, $newLocation, $newOrganizer, $eventId);
        
        if (mysqli_stmt_execute($updateStmt)) {
            header("Location: event-tab.php?id=$eventId&success=updated");
        } else {
            header("Location: event-tab.php?id=$eventId&error=updateFailed");
        }
        exit();
    }
}

// Handle approve action
if (isset($_GET['approve_id']) && isset($_GET['id'])) {
    $userId = $_GET['approve_id'];
    $eventId = $_GET['id'];
    
    $updateQuery = "UPDATE requests SET request_status = 'approved' 
                   WHERE requests_usersId = ? AND event_id = ?";
    $stmt = mysqli_prepare($con, $updateQuery);
    mysqli_stmt_bind_param($stmt, "ii", $userId, $eventId);
    
    if (mysqli_stmt_execute($stmt)) {
        // Optionally fetch user email for notification
        $userQuery = "SELECT usersEmail FROM users WHERE usersId = ?";
        $userStmt = mysqli_prepare($con, $userQuery);
        mysqli_stmt_bind_param($userStmt, "i", $userId);
        mysqli_stmt_execute($userStmt);
        $result = mysqli_stmt_get_result($userStmt);
        $userData = mysqli_fetch_assoc($result);
        
        // You can add email notification here
        // sendEmail($userData['usersEmail'], 'Your request has been approved');
        
        header("Location: event-tab.php?id=$eventId&tab=applied-users&success=approved");
        exit();
    } else {
        header("Location: event-tab.php?id=$eventId&tab=applied-users&error=approvalFailed");
        exit();
    }
}

// Handle reject action
if (isset($_GET['reject_id']) && isset($_GET['id'])) {
    $userId = $_GET['reject_id'];
    $eventId = $_GET['id'];
    
    $updateQuery = "UPDATE requests SET request_status = 'rejected' 
                   WHERE requests_usersId = ? AND event_id = ?";
    $stmt = mysqli_prepare($con, $updateQuery);
    mysqli_stmt_bind_param($stmt, "ii", $userId, $eventId);
    
    if (mysqli_stmt_execute($stmt)) {
        // Optionally fetch user email for notification
        $userQuery = "SELECT usersEmail FROM users WHERE usersId = ?";
        $userStmt = mysqli_prepare($con, $userQuery);
        mysqli_stmt_bind_param($userStmt, "i", $userId);
        mysqli_stmt_execute($userStmt);
        $result = mysqli_stmt_get_result($userStmt);
        $userData = mysqli_fetch_assoc($result);
        
        // You can add email notification here
        // sendEmail($userData['usersEmail'], 'Your request has been rejected');
        
        header("Location: event-tab.php?id=$eventId&tab=applied-users&success=rejected");
        exit();
    } else {
        header("Location: event-tab.php?id=$eventId&tab=applied-users&error=rejectionFailed");
        exit();
    }
}

// Status change and email notification
if (isset($_POST['update_status']) && isset($_POST['user_id']) && isset($_POST['new_status'])) {
    $userId = $_POST['user_id'];
    $eventId = $_POST['event_id'];
    $newStatus = $_POST['new_status'];
    
    $updateQuery = "UPDATE requests SET request_status = ? 
                   WHERE requests_usersId = ? AND event_id = ?";
    $stmt = mysqli_prepare($con, $updateQuery);
    mysqli_stmt_bind_param($stmt, "sii", $newStatus, $userId, $eventId);
    
    if (mysqli_stmt_execute($stmt)) {
        // Fetch user email and event details for notification
        $userQuery = "SELECT u.usersEmail, p.full_name, e.event_description 
                     FROM users u 
                     LEFT JOIN user_profiles p ON u.usersId = p.profile_usersId
                     LEFT JOIN events e ON e.event_id = ?
                     WHERE u.usersId = ?";
        $userStmt = mysqli_prepare($con, $userQuery);
        mysqli_stmt_bind_param($userStmt, "ii", $eventId, $userId);
        mysqli_stmt_execute($userStmt);
        $result = mysqli_stmt_get_result($userStmt);
        $userData = mysqli_fetch_assoc($result);
        
        // Send email notification
        $to = $userData['usersEmail'];
        $subject = "Event Application Status Update";
        
        if ($newStatus === 'approved') {
            $message = "Dear " . ($userData['full_name'] ?? 'Volunteer') . ",\n\n"
                    . "Congratulations! Your application for the event '" . ($userData['event_description'] ?? 'the event') . "' has been approved.\n"
                    . "We look forward to your participation.\n\n"
                    . "Best regards,\nVolhub Team";
        } else {
            $message = "Dear " . ($userData['full_name'] ?? 'Volunteer') . ",\n\n"
                    . "We regret to inform you that your application for the event '" . ($userData['event_description'] ?? 'the event') . "' "
                    . "has been declined.\n\n"
                    . "Best regards,\nVolhub Team";
        }
        
        $headers = "From: noreply@volhub.com";
        
        mail($to, $subject, $message, $headers);
        
        header("Location: event-tab.php?id=$eventId&tab=" . ($newStatus === 'approved' ? 'approved-users' : 'declined-users') . "&success=statusUpdated");
        exit();
    } else {
        header("Location: event-tab.php?id=$eventId&tab=" . ($newStatus === 'approved' ? 'approved-users' : 'declined-users') . "&error=updateFailed");
        exit();
    }
}

// Fetch event details
$query = "SELECT * FROM events WHERE event_id = ?";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, "i", $eventId);
mysqli_stmt_execute($stmt);
$event = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$event) {
    header("Location: index.php?error=eventNotFound");
    exit();
}

// Fetch users based on status
function fetchUsersByStatus($con, $eventId, $status) {
    $query = "SELECT 
                u.usersId,
                u.usersEmail,
                u.user_type,
                p.full_name,
                p.username,
                p.phone,
                p.city,
                p.institution,
                p.field_of_study,
                upo.organization_name,
                upo.job_title,
                upo.industry,
                upv.emergency_name,
                upv.emergency_phone,
                r.submission_date,
                r.request_status
              FROM users u 
              INNER JOIN requests r ON u.usersId = r.requests_usersId 
              LEFT JOIN user_profiles p ON u.usersId = p.profile_usersId
              LEFT JOIN user_profiles_org upo ON u.usersId = upo.userid
              LEFT JOIN user_profiles_vol upv ON u.usersId = upv.userid
              WHERE r.event_id = ? AND r.request_status = ?
              ORDER BY r.submission_date DESC";
    
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "is", $eventId, $status);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_get_result($stmt);
}
$appliedUsers = fetchUsersByStatus($con, $eventId, 'pending');
$approvedUsers = fetchUsersByStatus($con, $eventId, 'approved');
$declinedUsers = fetchUsersByStatus($con, $eventId, 'rejected');

// Get active tab from URL parameter
$activeTab = $_GET['tab'] ?? 'event-details';
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
                <?php
                $tabs = [
                    'event-details' => 'Event Details',
                    'applied-users' => 'Applied Users',
                    'approved-users' => 'Approved Users',
                    'declined-users' => 'Declined Users'
                ];

                foreach ($tabs as $id => $name) {
                    $activeClass = ($activeTab === $id) ? 'text-blue-500 border-b-2 border-blue-500' : 'text-gray-400 hover:text-gray-200';
                    echo "<a href='?id=$eventId&tab=$id' class='tab-button py-2 px-3 md:px-4 text-sm font-medium text-center whitespace-nowrap $activeClass'>$name</a>";
                }
                ?>
            </div>
        </div>

        <div class="mt-6 bg-dark-light p-6 rounded-lg">
            <?php if ($activeTab === 'event-details'): ?>
            <form method="POST" action="">
                <input type="hidden" name="action" value="update_event">
                <input type="hidden" name="event_id" value="<?php echo htmlspecialchars($event['event_id']); ?>">
                <div class="space-y-6">
                    <div
                        class="aspect-video bg-dark-lighter rounded-lg flex items-center justify-center mx-auto w-full md:w-3/4 lg:w-1/2">
                        <i class="lucide-calendar h-16 w-16 md:h-14 md:w-14 lg:h-12 lg:w-12 text-gray-600"></i>
                    </div>
                    <div class="space-y-4">
                        <h2 class="text-xl font-semibold">About the Event</h2>
                        <textarea name="event_description"
                            class="w-full bg-transparent border border-gray-700 rounded p-2"
                            rows="3"><?php echo htmlspecialchars($event['event_description']); ?></textarea>
                    </div>
                    <div class="space-y-4">
                        <h2 class="text-xl font-semibold">Event Details</h2>
                        <div class="space-y-2">
                            <div class="flex items-center space-x-2 text-gray-400">
                                <i class="lucide-calendar h-5 w-5"></i>
                                <input name="event_date" type="datetime-local"
                                    value="<?php echo htmlspecialchars($event['event_datetime']); ?>"
                                    class="bg-transparent border border-gray-700 rounded p-2">
                            </div>
                            <div class="flex items-center space-x-2 text-gray-400">
                                <i class="lucide-map-pin h-5 w-5"></i>
                                <input name="event_location" type="text"
                                    value="<?php echo htmlspecialchars($event['event_location']); ?>"
                                    class="bg-transparent border border-gray-700 rounded p-2">
                            </div>
                            <div class="flex items-center space-x-2 text-gray-400">
                                <i class="lucide-user h-5 w-5"></i>
                                <input name="event_organizer" type="text"
                                    value="<?php echo htmlspecialchars($event['organizer_id']); ?>"
                                    class="bg-transparent border border-gray-700 rounded p-2">
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Update Event
                        </button>
                    </div>
                </div>
            </form>
            <?php elseif ($activeTab === 'applied-users'): ?>
            <h2 class="text-xl font-semibold mb-4">Applied Users</h2>
            <div class="space-y-4">
                <?php while ($user = mysqli_fetch_assoc($appliedUsers)): ?>
                <div class="bg-dark-lighter p-4 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div class="flex-grow">
                            <h3 class="text-lg font-semibold">
                                <?php echo htmlspecialchars($user['full_name'] ?? 'N/A'); ?>
                                <?php if ($user['username']): ?>
                                <span
                                    class="text-sm text-gray-400">(@<?php echo htmlspecialchars($user['username']); ?>)</span>
                                <?php endif; ?>
                            </h3>
                            <p class="text-gray-400"><?php echo htmlspecialchars($user['usersEmail']); ?></p>

                            <?php if ($user['user_type'] === 'volunteer' || $user['user_type'] === 'both'): ?>
                            <div class="mt-2 text-sm">
                                <p>Institution: <?php echo htmlspecialchars($user['institution'] ?? 'N/A'); ?></p>
                                <p>Field of Study: <?php echo htmlspecialchars($user['field_of_study'] ?? 'N/A'); ?></p>
                                <p>Emergency Contact: <?php echo htmlspecialchars($user['emergency_name'] ?? 'N/A'); ?>
                                    (<?php echo htmlspecialchars($user['emergency_phone'] ?? 'N/A'); ?>)</p>
                            </div>
                            <?php endif; ?>

                            <?php if ($user['user_type'] === 'organizer' || $user['user_type'] === 'both'): ?>
                            <div class="mt-2 text-sm">
                                <p>Organization: <?php echo htmlspecialchars($user['organization_name'] ?? 'N/A'); ?>
                                </p>
                                <p>Position: <?php echo htmlspecialchars($user['job_title'] ?? 'N/A'); ?></p>
                                <p>Industry: <?php echo htmlspecialchars($user['industry'] ?? 'N/A'); ?></p>
                            </div>
                            <?php endif; ?>

                            <p class="text-sm text-gray-500 mt-2">Applied on:
                                <?php echo date('Y-m-d H:i:s', strtotime($user['submission_date'])); ?></p>
                        </div>
                        <div class="space-x-2 ml-4">
                            <form method="POST" action="" class="inline">
                                <!-- <input type="hidden" name="action" value="approve_user">
                                        <input type="hidden" name="user_id" value="<?php echo $user['usersId']; ?>">
                                        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                                            Approve
                                        </button>
                                    </form>
                                    <form method="POST" action="" class="inline">
                                        <input type="hidden" name="action" value="reject_user">
                                        <input type="hidden" name="user_id" value="<?php echo $user['usersId']; ?>">
                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded">
                                            Reject
                                        </button> -->

                                <div class="space-x-2 ml-4">
                                    <a href="event-tab.php?id=<?php echo $eventId; ?>&approve_id=<?php echo $user['usersId']; ?>&tab=applied-users"
                                        class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                                        Approve
                                    </a>
                                    <a href="event-tab.php?id=<?php echo $eventId; ?>&reject_id=<?php echo $user['usersId']; ?>&tab=applied-users"
                                        class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded">
                                        Reject
                                    </a>
                                </div>
                            </form>


                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
            <?php elseif ($activeTab === 'approved-users'): ?>
<h2 class="text-xl font-semibold mb-4">Approved Users</h2>
<div class="space-y-4">
    <?php while ($user = mysqli_fetch_assoc($approvedUsers)): ?>
    <div class="bg-dark-lighter p-4 rounded-lg">
        <div class="flex justify-between items-start">
            <div class="flex-grow">
                <h3 class="text-lg font-semibold">
                    <?php echo htmlspecialchars($user['full_name'] ?? 'N/A'); ?>
                    <?php if ($user['username']): ?>
                    <span class="text-sm text-gray-400">(@<?php echo htmlspecialchars($user['username']); ?>)</span>
                    <?php endif; ?>
                </h3>
                <p class="text-gray-400"><?php echo htmlspecialchars($user['usersEmail']); ?></p>

                <?php if ($user['user_type'] === 'volunteer' || $user['user_type'] === 'both'): ?>
                <div class="mt-2 text-sm">
                    <p>Institution: <?php echo htmlspecialchars($user['institution'] ?? 'N/A'); ?></p>
                    <p>Field of Study: <?php echo htmlspecialchars($user['field_of_study'] ?? 'N/A'); ?></p>
                    <p>Phone: <?php echo htmlspecialchars($user['phone'] ?? 'N/A'); ?></p>
                    <p>Emergency Contact: <?php echo htmlspecialchars($user['emergency_name'] ?? 'N/A'); ?>
                        (<?php echo htmlspecialchars($user['emergency_phone'] ?? 'N/A'); ?>)</p>
                    <p>City: <?php echo htmlspecialchars($user['city'] ?? 'N/A'); ?></p>
                </div>
                <?php endif; ?>

                <?php if ($user['user_type'] === 'organizer' || $user['user_type'] === 'both'): ?>
                <div class="mt-2 text-sm">
                    <p>Organization: <?php echo htmlspecialchars($user['organization_name'] ?? 'N/A'); ?></p>
                    <p>Position: <?php echo htmlspecialchars($user['job_title'] ?? 'N/A'); ?></p>
                    <p>Industry: <?php echo htmlspecialchars($user['industry'] ?? 'N/A'); ?></p>
                </div>
                <?php endif; ?>

                <p class="text-sm text-gray-500 mt-2">Approved on:
                    <?php echo date('Y-m-d H:i:s', strtotime($user['submission_date'])); ?></p>
            </div>
            
            <div class="ml-4">
                <form method="POST" action="" class="inline-block">
                    <input type="hidden" name="user_id" value="<?php echo $user['usersId']; ?>">
                    <input type="hidden" name="event_id" value="<?php echo $eventId; ?>">
                    <input type="hidden" name="new_status" value="rejected">
                    <button type="submit" name="update_status" 
                            class="bg-red-500 hover:bg-red-600 text-white text-sm font-bold py-2 px-4 rounded transition duration-200"
                            onclick="return confirm('Are you sure you want to reject this user? They will be notified via email.')">
                        Change to Rejected
                    </button>
                </form>
            </div>
        </div>
    </div>
    <?php endwhile; ?>
</div>

<?php elseif ($activeTab === 'declined-users'): ?>
<h2 class="text-xl font-semibold mb-4">Declined Users</h2>
<div class="space-y-4">
    <?php while ($user = mysqli_fetch_assoc($declinedUsers)): ?>
    <div class="bg-dark-lighter p-4 rounded-lg">
        <div class="flex justify-between items-start">
            <div class="flex-grow">
                <h3 class="text-lg font-semibold">
                    <?php echo htmlspecialchars($user['full_name'] ?? 'N/A'); ?>
                    <?php if ($user['username']): ?>
                    <span class="text-sm text-gray-400">(@<?php echo htmlspecialchars($user['username']); ?>)</span>
                    <?php endif; ?>
                </h3>
                <p class="text-gray-400"><?php echo htmlspecialchars($user['usersEmail']); ?></p>

                <?php if ($user['user_type'] === 'volunteer' || $user['user_type'] === 'both'): ?>
                <div class="mt-2 text-sm">
                    <p>Institution: <?php echo htmlspecialchars($user['institution'] ?? 'N/A'); ?></p>
                    <p>Field of Study: <?php echo htmlspecialchars($user['field_of_study'] ?? 'N/A'); ?></p>
                    <p>Phone: <?php echo htmlspecialchars($user['phone'] ?? 'N/A'); ?></p>
                    <p>Emergency Contact: <?php echo htmlspecialchars($user['emergency_name'] ?? 'N/A'); ?>
                        (<?php echo htmlspecialchars($user['emergency_phone'] ?? 'N/A'); ?>)</p>
                    <p>City: <?php echo htmlspecialchars($user['city'] ?? 'N/A'); ?></p>
                </div>
                <?php endif; ?>

                <?php if ($user['user_type'] === 'organizer' || $user['user_type'] === 'both'): ?>
                <div class="mt-2 text-sm">
                    <p>Organization: <?php echo htmlspecialchars($user['organization_name'] ?? 'N/A'); ?></p>
                    <p>Position: <?php echo htmlspecialchars($user['job_title'] ?? 'N/A'); ?></p>
                    <p>Industry: <?php echo htmlspecialchars($user['industry'] ?? 'N/A'); ?></p>
                </div>
                <?php endif; ?>

                <p class="text-sm text-gray-500 mt-2">Declined on:
                    <?php echo date('Y-m-d H:i:s', strtotime($user['submission_date'])); ?></p>
            </div>
            
            <div class="ml-4">
                <form method="POST" action="" class="inline-block">
                    <input type="hidden" name="user_id" value="<?php echo $user['usersId']; ?>">
                    <input type="hidden" name="event_id" value="<?php echo $eventId; ?>">
                    <input type="hidden" name="new_status" value="approved">
                    <button type="submit" name="update_status" 
                            class="bg-green-500 hover:bg-green-600 text-white text-sm font-bold py-2 px-4 rounded transition duration-200"
                            onclick="return confirm('Are you sure you want to approve this user? They will be notified via email.')">
                        Change to Approved
                    </button>
                </form>
            </div>
        </div>
    </div>
    <?php endwhile; ?>
</div>
<?php endif; ?>
        </div>
    </main>

    <footer class="bg-dark-light py-4 mt-8">
        <div class="container mx-auto px-4 text-center text-gray-400">
            <p>&copy; <?php echo date('Y'); ?> Volhub. All rights reserved.</p>
        </div>
    </footer>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add any additional JavaScript functionality here if needed
    });
    </script>
</body>

</html>