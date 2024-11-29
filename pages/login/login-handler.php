<?php

include_once "../../includes/dbh.inc.php";
include_once '../../includes/functions.inc.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = htmlspecialchars($_POST['email']);
    $pwd = htmlspecialchars($_POST['pwd']);

    /*.......................ERROR HANDLERS.......................*/

    if (emptyInputLogin($email, $pwd)) {
        header("Location: login.php?error=emptyinput");
        exit();
    }


    if (loginUser($email, $pwd)) { // No $con parameter, check return value
        // Successful login – redirect based on role/user type
        header("Location: ../includes/dashboard.php");  // Change redirect as needed
        exit();
    } else {
        // Login failed
        header("Location: login.php?error=wronglogin"); // More specific error message
        exit();
    }


} else {
    header("Location: login.php");
    exit();
}
?>