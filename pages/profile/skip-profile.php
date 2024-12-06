<?php
session_start();
require_once '../../includes/dbh.inc.php';

if (!isset($_SESSION["usersid"])) {
    header("Location: ../login/login.php");
    exit();
}

// No need to update profile_completed since we want it to remain 0 until they actually complete the profile

// Redirect based on user type
if (isset($_SESSION['user_type'])) {
    switch ($_SESSION['user_type']) {
        case 'volunteer':
            header("Location: ../../includes/dashboard.php");
            break;
        case 'organizer':
            header("Location: ../../includes/org-dashboard.php");
            break;
        case 'both':
            header("Location: ../../includes/dashboard.php");
            break;
        default:
            header("Location: ../../includes/dashboard.php");
    }
} else {
    header("Location: ../../includes/dashboard.php");
}
exit();
?> 