<?php
include "../../includes/dbh.inc.php";
include "../../includes/functions.inc.php";
session_start();

if (isset($_SESSION['usersid'])) {
    $user_id = $_SESSION['usersid'];
} 
else {
    // Handle error or redirect to login
    header("Location: ../login/login.php?error=notLoggedIn");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['organizer_profile'])) {
        $organization_name = htmlspecialchars($_POST['organization']);
        $job_title = htmlspecialchars($_POST['job-title']);
        $industry = htmlspecialchars($_POST['industry']);
        $location = htmlspecialchars($_POST['location']);
        $official_address = htmlspecialchars($_POST['official-address']);
        $official_contact_number = htmlspecialchars($_POST['official-contact']);

        // Insert into `user_profiles_org`
        createOrganizerProfile($con, $user_id, $organization_name, $job_title, $industry, $location, $official_address, $official_contact_number);

        // Update `users` table
        $sql = "UPDATE users SET organizer = '1' WHERE usersId = ?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        // Update organizer profile completion status
        updateOrgProfileStatus($con, $user_id);

        header("Location: ../../includes/org-dashboard.php?profile=created");
        exit();
    }
}
else {
    header("Location: ../login/login.php?error=unauthorized");
    exit();
}
?>