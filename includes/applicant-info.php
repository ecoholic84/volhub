<?php
include_once "dbh.inc.php";
session_start();

// Check if user is logged in
if (!isset($_SESSION["usersid"])) {
    header("Location: /miniProject/pages/login/login.php?error=notLoggedIn");
    exit();
}

// Get the user ID from the URL parameter
$userId = $_GET['userId'] ?? null;
if (!$userId) {
    header("Location: index.php?error=noUserId");
    exit();
}

// Fetch user data
$query = "SELECT 
            u.usersId,
            u.usersEmail,
            u.user_type,
            p.full_name,
            p.username,
            p.phone,
            p.city,
            p.bio,
            p.institution,
            p.degree_type,
            p.field_of_study,
            p.graduation_month,
            p.graduation_year,
            p.links,
            upo.organization_name,
            upo.job_title,
            upo.industry,
            upv.emergency_name,
            upv.emergency_phone
          FROM users u 
          LEFT JOIN user_profiles p ON u.usersId = p.profile_usersId
          LEFT JOIN user_profiles_org upo ON u.usersId = upo.userid
          LEFT JOIN user_profiles_vol upv ON u.usersId = upv.userid
          WHERE u.usersId = ?";

$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, "i", $userId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    header("Location: index.php?error=userNotFound");
    exit();
}

// Handle approve and reject actions
if (isset($_POST['action']) && isset($_POST['event_id'])) {
    $eventId = $_POST['event_id'];
    $action = $_POST['action'];
    $newStatus = ($action === 'approve') ? 'approved' : 'rejected';

    $updateQuery = "UPDATE requests SET request_status = ? 
                    WHERE requests_usersId = ? AND event_id = ?";
    $updateStmt = mysqli_prepare($con, $updateQuery);
    mysqli_stmt_bind_param($updateStmt, "sii", $newStatus, $userId, $eventId);
    
    if (mysqli_stmt_execute($updateStmt)) {
        // Fetch event details for notification
        $eventQuery = "SELECT event_description FROM events WHERE event_id = ?";
        $eventStmt = mysqli_prepare($con, $eventQuery);
        mysqli_stmt_bind_param($eventStmt, "i", $eventId);
        mysqli_stmt_execute($eventStmt);
        $eventResult = mysqli_stmt_get_result($eventStmt);
        $eventData = mysqli_fetch_assoc($eventResult);
        
        // Send email notification
        $to = $user['usersEmail'];
        $subject = "Event Application Status Update";
        
        if ($newStatus === 'approved') {
            $message = "Dear " . ($user['full_name'] ?? 'Volunteer') . ",\n\n"
                    . "Congratulations! Your application for the event '" . ($eventData['event_description'] ?? 'the event') . "' has been approved.\n"
                    . "We look forward to your participation.\n\n"
                    . "Best regards,\nVolhub Team";
        } else {
            $message = "Dear " . ($user['full_name'] ?? 'Volunteer') . ",\n\n"
                    . "We regret to inform you that your application for the event '" . ($eventData['event_description'] ?? 'the event') . "' "
                    . "has been declined.\n\n"
                    . "Best regards,\nVolhub Team";
        }
        
        $headers = "From: noreply@volhub.com";
        
        mail($to, $subject, $message, $headers);
        
        header("Location: applicant-info.php?userId=$userId&success=statusUpdated");
        exit();
    } else {
        header("Location: applicant-info.php?userId=$userId&error=updateFailed");
        exit();
    }
}

// Fetch events the user has applied to
$eventsQuery = "SELECT e.event_id, e.event_description, r.request_status, r.submission_date
                FROM events e
                INNER JOIN requests r ON e.event_id = r.event_id
                WHERE r.requests_usersId = ?
                ORDER BY r.submission_date DESC";
$eventsStmt = mysqli_prepare($con, $eventsQuery);
mysqli_stmt_bind_param($eventsStmt, "i", $userId);
mysqli_stmt_execute($eventsStmt);
$eventsResult = mysqli_stmt_get_result($eventsStmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applicant Information</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
<body class="min-h-screen bg-gradient-to-br from-black via-black to-gray-800">
    <?php include "header.php" ?>
    <div class="max-w-3xl mx-auto bg-dark-light shadow sm:rounded-lg mt-6">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-base font-semibold leading-7 text-gray-100">Applicant Information</h3>
            <p class="mt-1 max-w-2xl text-sm leading-6 text-gray-400">Personal details and application.</p>
        </div>
        <div class="border-t border-gray-700">
            <dl class="divide-y divide-gray-700">
                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium leading-6 text-gray-100">Full name</dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-300 sm:col-span-2 sm:mt-0"><?php echo htmlspecialchars($user['full_name']); ?></dd>
                </div>
                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium leading-6 text-gray-100">Username</dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-300 sm:col-span-2 sm:mt-0">@<?php echo htmlspecialchars($user['username']); ?></dd>
                </div>
                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium leading-6 text-gray-100">User Type</dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-300 sm:col-span-2 sm:mt-0"><?php echo htmlspecialchars($user['user_type']); ?></dd>
                </div>
                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium leading-6 text-gray-100">Phone</dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-300 sm:col-span-2 sm:mt-0"><?php echo htmlspecialchars($user['phone']); ?></dd>
                </div>
                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium leading-6 text-gray-100">City</dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-300 sm:col-span-2 sm:mt-0"><?php echo htmlspecialchars($user['city']); ?></dd>
                </div>
                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium leading-6 text-gray-100">About</dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-300 sm:col-span-2 sm:mt-0"><?php echo nl2br(htmlspecialchars($user['bio'])); ?></dd>
                </div>
                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium leading-6 text-gray-100">Education</dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-300 sm:col-span-2 sm:mt-0">
                        <p><strong>Degree:</strong> <?php echo htmlspecialchars($user['degree_type']); ?></p>
                        <p><strong>Field of Study:</strong> <?php echo htmlspecialchars($user['field_of_study']); ?></p>
                        <p><strong>Institution:</strong> <?php echo htmlspecialchars($user['institution']); ?></p>
                        <p><strong>Graduation:</strong> <?php echo htmlspecialchars($user['graduation_month'] . ' ' . $user['graduation_year']); ?></p>
                    </dd>
                </div>
                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium leading-6 text-gray-100">Links</dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-300 sm:col-span-2 sm:mt-0">
                        <ul role="list" class="divide-y divide-gray-700 rounded-md border border-gray-600">
                            <?php
                            $links = explode(',', $user['links']);
                            foreach ($links as $link) {
                                $link = trim($link);
                                $domain = parse_url($link, PHP_URL_HOST);
                                echo '<li class="flex items-center justify-between py-4 pl-4 pr-5 text-sm leading-6">
                                    <div class="flex w-0 flex-1 items-center">
                                        <svg class="h-5 w-5 flex-shrink-0 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M15.621 4.379a3 3 0 0 0-4.242 0l-7 7a3 3 0 0 0 4.241 4.243h.001l.497-.5a.75.75 0 0 1 1.064 1.057l-.498.501-.002.002a4.5 4.5 0 0 1-6.364-6.364l7-7a4.5 4.5 0 0 1 6.368 6.36l-3.455 3.553A2.625 2.625 0 1 1 9.52 9.52l3.45-3.451a.75.75 0 1 1 1.061 1.06l-3.45 3.451a1.125 1.125 0 0 0 1.587 1.595l3.454-3.553a3 3 0 0 0 0-4.242Z" clip-rule="evenodd" />
                                        </svg>
                                        <div class="ml-4 flex min-w-0 flex-1 gap-2">
                                            <span class="truncate font-medium">' . htmlspecialchars($domain) . '</span>
                                        </div>
                                    </div>
                                    <div class="ml-4 flex-shrink-0">
                                        <a href="' . htmlspecialchars($link) . '" class="font-medium text-indigo-400 hover:text-indigo-300" target="_blank">Visit</a>
                                    </div>
                                </li>';
                            }
                            ?>
                        </ul>
                    </dd>
                </div>
                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium leading-6 text-gray-100">Emergency Contact</dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-300 sm:col-span-2 sm:mt-0">
                        <p><strong>Name:</strong> <?php echo htmlspecialchars($user['emergency_name']); ?></p>
                        <p><strong>Phone:</strong> <?php echo htmlspecialchars($user['emergency_phone']); ?></p>
                    </dd>
                </div>
            </dl>
        </div>
    </div>

    <div class="max-w-3xl mx-auto mt-8">
        <h2 class="text-xl font-semibold mb-4 text-gray-100">Applied Events</h2>
        <?php while ($event = mysqli_fetch_assoc($eventsResult)): ?>
            <div class="bg-dark-light shadow sm:rounded-lg mb-4 p-4">
                <h3 class="text-lg font-medium text-gray-100"><?php echo htmlspecialchars($event['event_description']); ?></h3>
                <p class="text-sm text-gray-400">Status: <?php echo htmlspecialchars($event['request_status']); ?></p>
                <p class="text-sm text-gray-400">Applied on: <?php echo date('Y-m-d H:i:s', strtotime($event['submission_date'])); ?></p>
                
                <?php if ($event['request_status'] === 'pending'): ?>
                    <div class="mt-4 flex space-x-4">
                        <form method="POST" action="">
                            <input type="hidden" name="action" value="approve">
                            <input type="hidden" name="event_id" value="<?php echo $event['event_id']; ?>">
                            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition duration-300">Approve</button>
                        </form>
                        <form method="POST" action="">
                            <input type="hidden" name="action" value="reject">
                            <input type="hidden" name="event_id" value="<?php echo $event['event_id']; ?>">
                            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded  hover:bg-red-700 transition duration-300">Reject</button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html>