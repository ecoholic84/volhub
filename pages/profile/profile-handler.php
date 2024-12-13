<?php
include "../../includes/dbh.inc.php";
include "../../includes/functions.inc.php";
session_start();

if (!isset($_SESSION['usersid'])) {
    // Handle error or redirect to login
    header("Location: ../login/login.php");
    exit();
}

$user_id = $_SESSION['usersid'];
$userType = $_SESSION['user_type']; // Assuming user_type is stored in session

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Process form submission
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

        // Update the basic profile completion status
        $sql = "UPDATE user_profiles SET profile_completed = 1 WHERE profile_usersId = ?";
        $stmt = mysqli_stmt_init($con);
        if (mysqli_stmt_prepare($stmt, $sql)) {
            mysqli_stmt_bind_param($stmt, "i", $user_id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }

        // Redirect based on user type after successful submission
        if (strpos($userType, 'volunteer') !== false) {
            updateVolProfileStatus($con, $user_id);
            header("Location: vol-profile-creation.php?success=1");
            exit();
        } elseif (strpos($userType, 'organizer') !== false) {
            updateOrgProfileStatus($con, $user_id);
            header("Location: org-profile-creation.php?success=1");
            exit();
        }
    }
} else {
    // Display the form
    // You can include your HTML form here or in a separate file
    include "profile-creation-form.php";
}
?>