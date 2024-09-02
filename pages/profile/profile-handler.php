<?php
include "../../includes/dbh.inc.php";
include "../../includes/functions.inc.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['usersid'];
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
    $emergency_name = htmlspecialchars($_POST['emergency-name']);
    $emergency_phone = htmlspecialchars($_POST['emergency-phone']);
    $links = isset($_POST['links']) ? json_encode($_POST['links']) : '';

    /*.......................ERROR HANDLERS.......................*/

    // Function to check if the username is valid.
    if (!invalidId($username))
    {
        header("Location: profile-creation.php?error=invalidUsername");
        exit();
    }
    
    // function to check if username is taken.
    if (idExists($con, $username)) {
        header("Location: profile-creation.php?error=usernameTaken");
        exit();
    }

    createProfile($con, $user_id, $full_name, $username, $identity, $bio, $degree_type, $institution, $field_of_study, $graduation_month, $graduation_year, $phone, $city, $emergency_name, $emergency_phone, $links);
    }
    else
    {
        // Redirects illegal users to signup page.
        header("Location: ../login/login.php");
    }