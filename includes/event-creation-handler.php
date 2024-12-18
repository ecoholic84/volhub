<?php
include "dbh.inc.php";
include "functions.inc.php";
session_start();

if (isset($_SESSION['usersid'])) {
    $organizer_id = $_SESSION['usersid'];
} else {
    // Handle error or redirect to login
    header("Location: ../login/login.php?error=notLoggedIn");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input fields
    $event_name = mysqli_real_escape_string($con, $_POST['event_name']);
    $event_description = mysqli_real_escape_string($con, $_POST['event_description']);
    $event_datetime = mysqli_real_escape_string($con, $_POST['event_datetime']);
    $event_location = mysqli_real_escape_string($con, $_POST['event_location']);
    
    // Handle file upload (thumbnail)
    $thumbnail = "";
    if (isset($_FILES['event_thumbnail']) && $_FILES['event_thumbnail']['error'] == UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['event_thumbnail']['tmp_name'];
        $file_name = basename($_FILES['event_thumbnail']['name']);
        $target_dir = "../uploads/";
        $target_file = $target_dir . $file_name;
        
        // Move uploaded file to target directory
        if (move_uploaded_file($file_tmp, $target_file)) {
            $thumbnail = $target_file;
        } else {
            die("Error uploading file.");
        }
    }

    // Check if organizer_id exists in the users table
    $check_user_sql = "SELECT * FROM users WHERE usersId = '$organizer_id'";
    $check_user_result = mysqli_query($con, $check_user_sql);
    if (mysqli_num_rows($check_user_result) == 0) {
        die("Error: Organizer ID does not exist.");
    }

    // SQL query to insert the event data into the database
    $sql = "INSERT INTO events (organizer_id, event_name, event_description, event_datetime, event_location, event_thumbnail) 
            VALUES ('$organizer_id', '$event_name', '$event_description', '$event_datetime', '$event_location', '$thumbnail')";

    if (mysqli_query($con, $sql)) {
        echo "Event created successfully!";
    } else {
        echo "Error: " . mysqli_error($con);
    }

    // Close the connection
    mysqli_close($con);
} else {
    // If the request method is not POST, redirect to the form
    header("Location: event-creation-form.php");
    exit();
}
?>
