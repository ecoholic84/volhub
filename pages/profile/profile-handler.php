<?php
include "../../includes/dbh.inc.php";
include "../../includes/functions.inc.php";
session_start();

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

    // Volunteer-specific fields
    if (isset($_POST['volunteer_profile'])) {
        $emergency_name = htmlspecialchars($_POST['emergency-name']);
        $emergency_phone = htmlspecialchars($_POST['emergency-phone']);
        
        // Insert into `user_profiles_vol`
        createVolunteerProfile($con, $user_id, $emergency_name, $emergency_phone);

        header("Location: vol-dashboard.php?profile=created");
        exit();
    }

    // Organizer-specific fields
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