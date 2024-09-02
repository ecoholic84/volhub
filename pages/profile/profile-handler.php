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

    $sql = "INSERT INTO UserProfiles (profile_usersId, full_name, username, identity, bio, degree_type, institution, field_of_study, graduation_month, graduation_year, phone, city, emergency_name, emergency_phone, links) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo "SQL error";
    } else {
        mysqli_stmt_bind_param($stmt, "issssssssisssss", $user_id, $full_name, $username, $identity, $bio, $degree_type, $institution, $field_of_study, $graduation_month, $graduation_year, $phone, $city, $emergency_name, $emergency_phone, $links);
        mysqli_stmt_execute($stmt);
        echo "Profile created successfully!";
    }
    mysqli_stmt_close($stmt);
}
    /*.......................ERROR HANDLERS.......................*/

    // Function to check if the username is valid.
    if (!invalidId($username))
    {
        header("Location: profile-creation.php?error=invalidUsername");
        exit();
    }
    
    // Function to check if username is taken.
    // if (idExists($con, $username)) {
    //     header("Location: profile-creation.php?error=usernameTaken");
    //     exit();
    // }

    function idExists($con, $username) {
        // SQL query to check if the username already exists in the database
        $sql = "SELECT * FROM UserProfiles WHERE username = ?;";
        $stmt = mysqli_stmt_init($con);
    
        // Prepare the SQL statement
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            // Handle any errors in statement preparation
            header("Location: profile-creation.php?error=stmtfailed");
            exit();
        }
    
        // Bind the username parameter to the statement
        mysqli_stmt_bind_param($stmt, "s", $username);
    
        // Execute the statement
        mysqli_stmt_execute($stmt);
    
        // Fetch the result from the query
        $resultData = mysqli_stmt_get_result($stmt);
    
        // Check if any row was returned
        if ($row = mysqli_fetch_assoc($resultData)) {
            // Username exists in the database
            return true;
        } else {
            // Username does not exist
            return false;
        }
    
        // Close the statement
        mysqli_stmt_close($stmt);
    }
    