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
        if (isset($_POST['volunteer_profile'])) {
            $emergency_name = htmlspecialchars($_POST['emergency-name']);
            $emergency_phone = htmlspecialchars($_POST['emergency-phone']);
            
            createVolunteerProfile($con, $user_id, $emergency_name, $emergency_phone);
    
            header("Location: vol-dashboard.php?profile=created");
            exit();
        }
} else {
    header("Location: ../login/login.php");
    exit();
}
?>