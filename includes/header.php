<?php
// Add this near the top after session_start()
require_once 'functions.inc.php';

$user_id = $_SESSION['usersid'];
$user_type = $_SESSION['user_type'];
$full_name = '';

// Get user's name from database
$query = "SELECT full_name FROM user_profiles WHERE profile_usersId = ?";
$stmt = $con->prepare($query);
if ($stmt) {
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $stmt->bind_result($full_name);
    $stmt->fetch();
    $stmt->close();
}

ob_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VolHub</title>
    <style>
    [x-cloak] {
        display: none
    }
    </style>
    <script src="https://unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-transparent">
    <nav class="w-full border-b border-gray-700" x-data="{ mobileMenuOpen: false }">
        <div class="px-2 sm:px-6 lg:px-8">
            <div class="relative flex h-16 items-center justify-between">
                <!-- Left side (logo and mobile menu button) -->
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <a href="/volhub/includes/dashboard.php" class="flex items-center space-x-2 font-extrabold text-white">
                            <span class="flex items-center justify-center flex-shrink-0 w-8 h-8 text-gray-900 rounded-full bg-gradient-to-br from-white via-gray-200 to-white">
                                <svg class="w-auto h-5 -translate-y-px" viewBox="0 0 69 66" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="m31.2 12.2-3.9 12.3-13.4.5-13.4.5 10.7 7.7L21.8 41l-3.9 12.1c-2.2 6.7-3.8 12.4-3.6 12.5.2.2 5-3 10.6-7.1 5.7-4.1 10.9-7.2 11.5-6.8.6.4 5.3 3.8 10.5 7.5 5.2 3.8 9.6 6.6 9.8 6.4.2-.2-1.4-5.8-3.6-12.5l-3.9-12.2 8.5-6.2c14.7-10.6 14.8-9.6-.4-9.7H44.2L40 12.5C37.7 5.6 35.7 0 35.5 0c-.3 0-2.2 5.5-4.3 12.2Z" fill="currentColor" />
                                </svg>
                            </span>
                            <span>VOLHUB</span>
                        </a>
                    </div>
                    <div class="sm:hidden ml-2">
                        <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="relative inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-gray-700 hover:text-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" aria-controls="mobile-menu" aria-expanded="false">
                            <span class="absolute -inset-0.5"></span>
                            <span class="sr-only">Open main menu</span>
                            <svg class="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Right side (notification and profile) -->
                <div class="flex items-center">
                    <!-- Notification button -->
                    <button type="button" class="relative rounded-full bg-gray-800 p-1 text-gray-400 hover:text-white">
                        <span class="absolute -inset-1.5"></span>
                        <span class="sr-only">View notifications</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                        </svg>
                    </button>

                    <!-- Profile dropdown -->
                    <div class="relative ml-3" x-data="{ open: false }">
                        <div>
                            <button @click="open = !open" type="button" class="relative flex rounded-full bg-gray-800 text-sm" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                <span class="absolute -inset-1.5"></span>
                                <span class="sr-only">Open user menu</span>
                                <div class="h-8 w-8 rounded-full bg-indigo-600 flex items-center justify-center">
                                    <span class="text-white text-sm font-medium">
                                        <?php 
                                        if (!empty($full_name)) {
                                            $initials = strtoupper(substr($full_name, 0, 1));
                                            $name_parts = explode(' ', $full_name);
                                            if (count($name_parts) > 1) {
                                                $initials .= strtoupper(substr($name_parts[count($name_parts)-1], 0, 1));
                                            }
                                            echo htmlspecialchars($initials);
                                        } else {
                                            echo 'U';
                                        }
                                        ?>
                                    </span>
                                </div>
                            </button>
                        </div>

                        <div x-show="open" @click.away="open = false" class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-gray-800 py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1" style="display: none;">
                            <a href="/volhub/pages/profile/profile-edit.php" class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700" role="menuitem" tabindex="-1" id="user-menu-item-0">Your Profile</a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700" role="menuitem" tabindex="-1" id="user-menu-item-1">Settings</a>

                            <?php
                            // Database connection
                            include 'dbh.inc.php';

                            // Query to check if the user has a volunteer profile
                            $volunteer_query = "SELECT volunteer FROM users WHERE usersId = ?";
                            $stmt = $con->prepare($volunteer_query);
                            $stmt->bind_param('i', $user_id);
                            $stmt->execute();
                            $stmt->bind_result($volunteer);
                            $stmt->fetch();
                            $has_volunteer_profile = ($volunteer == 1);
                            $stmt->close();

                            // Query to check if the user has an organizer profile
                            $organizer_query = "SELECT organizer FROM users WHERE usersId = ?";
                            $stmt = $con->prepare($organizer_query);
                            $stmt->bind_param('i', $user_id);
                            $stmt->execute();
                            $stmt->bind_result($organizer);
                            $stmt->fetch();
                            $has_organizer_profile = ($organizer == 1);
                            $stmt->close();

                            // Determine current dashboard
                            $current_dashboard = basename($_SERVER['PHP_SELF']);
                            $is_organizer_dashboard = strpos($current_dashboard, 'org-dashboard.php') !== false;

                            // Set up URLs
                            $volunteer_dashboard_url = "/volhub/includes/dashboard.php";
                            $organizer_dashboard_url = "/volhub/includes/org-dashboard.php";
                            $create_volunteer_profile_url = "/volhub/pages/profile/vol-profile-creation.php";
                            $create_organizer_profile_url = "/volhub/pages/profile/org-profile-creation.php";

                            // Determine appropriate URLs and text
                            if ($is_organizer_dashboard) {
                                $switch_text = "Switch to Volunteer Dashboard";
                                $switch_url = $has_volunteer_profile ? $volunteer_dashboard_url : $create_volunteer_profile_url;
                            } else {
                                $switch_text = "Switch to Organizer Dashboard";
                                $switch_url = $has_organizer_profile ? $organizer_dashboard_url : $create_organizer_profile_url;
                            }

                            if ($has_volunteer_profile || $has_organizer_profile) : ?>
                                <a href="<?php echo htmlspecialchars($switch_url); ?>" class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700" role="menuitem" tabindex="-1" id="user-menu-item-switch">
                                    <?php echo htmlspecialchars($switch_text); ?>
                                </a>
                            <?php endif; ?>

                            <a href="/volhub/includes/signout.php" class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700" role="menuitem" tabindex="-1" id="user-menu-item-2">Sign out</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div x-show="mobileMenuOpen" class="sm:hidden" id="mobile-menu">
            <div class="space-y-1 px-2 pb-3 pt-2">
                <!-- Mobile content here if needed -->
            </div>
        </div>
    </nav>

    <?php
    function showSubheader($show) {
        if ($show === true) {
            include 'subheader.php';
        }
    }
    showSubheader(false);
    ob_end_flush();

    function shouldShowNotification() {
        $current_page = basename($_SERVER['PHP_SELF']);
        $allowed_pages = ['dashboard.php', 'org-dashboard.php', 'event-page.php'];
        return in_array($current_page, $allowed_pages);
    }

    if (isset($_SESSION['usersid']) && shouldShowNotification()) {
        $profileStatus = checkProfileStatus($con, $_SESSION["usersid"]);
        if (!$profileStatus['basic_complete']) {
            echo '
            <div class="bg-indigo-600" x-data="{ show: true }" x-show="show">
                <div class="mx-auto max-w-7xl py-3 px-3 sm:px-6 lg:px-8">
                    <div class="flex flex-wrap items-center justify-between">
                        <div class="flex w-0 flex-1 items-center">
                            <span class="flex rounded-lg bg-indigo-800 p-2">
                                <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                </svg>
                            </span>
                            <p class="ml-3 truncate font-medium text-white">
                                <span class="md:hidden">Complete your profile!</span>
                                <span class="hidden md:inline">Your profile is incomplete. Complete it to get better opportunities!</span>
                            </p>
                        </div>
                        <div class="order-3 mt-2 w-full flex-shrink-0 sm:order-2 sm:mt-0 sm:w-auto">
                            <a href="/volhub/pages/profile/profile-creation.php" class="flex items-center justify-center rounded-md border border-transparent bg-white px-4 py-2 text-sm font-medium text-indigo-600 shadow-sm hover:bg-indigo-50">Complete now</a>
                        </div>
                        <div class="order-2 flex-shrink-0 sm:order-3 sm:ml-3">
                            <button type="button" @click="show = false" class="-mr-1 flex rounded-md p-2 hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-white sm:-mr-2">
                                <span class="sr-only">Dismiss</span>
                                <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>';
        }
    }
    ?>
</body>
</html>