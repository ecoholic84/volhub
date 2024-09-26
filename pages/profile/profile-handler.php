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
    $user_id = $_SESSION['usersid'];
    $userType = $_SESSION['user_type']; // Assuming user_type is stored in session
    $user_type = $_SESSION['user_type'];

    // Check user_type and redirect accordingly
    if (strpos($user_type, 'volunteer') !== false) {
        header("Location: vol-profile-creation.php");
        exit();
    } elseif (strpos($user_type, 'organizer') !== false) {
        header("Location: org-profile-creation.php");
        exit();
    }
    
    // If no redirection occurred, proceed with the rest of the script
    
    // Shared fields
    if (isset($_POST['basic_profile'])) {
        $full_name = htmlspecialchars($_POST['full-name']);
        $username = htmlspecialchars($_POST['username']);
        $identity = htmlspecialchars($_POST['identity']);
        $bio = htmlspecialchars($_POST['bio']);
        $degree_type = htmlspecialchars($_POST['degree-type']);
        $institution = htmlspecialchars($_POST['institution']);
        $field_of_study = htmlspecialchars($_POST['field-of-study']);
        $graduation_month = htmlspecialchars($_POST['graduation-month']);
        $graduation_year = htmlspecialchars($_POST['graduation-year']);
        $phone = htmlspecialchars($_POST['phone']);
        $city = htmlspecialchars($_POST['city']);
        $links = isset($_POST['links']) ? json_encode($_POST['links']) : '';

        // Common error handling for shared fields
        if (!invalidId($username)) {
            header("Location: profile-creation.php?error=invalidUsername");
            exit();
        }
        
        if (idExists($con, $username)) {
            header("Location: profile-creation.php?error=usernameTaken");
            exit();
        }

        // Insert shared profile fields into `user_profiles`
        createSharedProfile($con, $user_id, $full_name, $username, $identity, $bio, $degree_type, $institution, $field_of_study, $graduation_month, $graduation_year, $phone, $city, $links);
    }

} else {
    header("Location: ../login/login.php");
    exit();
}
?>