<?php
include "../../includes/dbh.inc.php";
include "../../includes/functions.inc.php";
session_start();

if (isset($_SESSION['usersid'])) {
    $user_id = $_SESSION['usersid'];
} else {
    // Handle error or redirect to login
    header("Location: ../login/login.php");
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

        header("Location: org-dashboard.php?profile=created");
        exit();
    }
} else {
    header("Location: ../login/login.php");
    exit();
}
?>