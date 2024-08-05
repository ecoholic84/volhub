<?php

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

include_once "../../dbh.inc.php";

// Posting data from form to variables.
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $fullname = htmlspecialchars($_POST['fullname']);
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $pwd = htmlspecialchars($_POST['pwd']);
    $created_at = date('Y-m-d H:i:s');

    // Error handler to exit, if no value is inputted by user.
    if (empty($fullname) || empty($username) || empty($email) || empty($pwd))
    {
        exit("Please fill all fields.");
    }

    // Adding insert script to variable.
    $insert = "INSERT INTO users (fullname, username, email, pwd, created_at) VALUES ('$fullname', '$username', '$email', '$pwd', '$created_at')";

    // Running query to insert values to table.
    if(mysqli_query($con, $insert))
    {
        echo "New Record Inserted.";
    }
    else
    {
        echo "Error: " . $insert . mysqli_error($con);
    }

    // Redirects user to sign up page after running code.
    header("Location: ../profile-creation.php");
}
else
{
    // Redirects illegal users to signup page.
    header("Location: signup.php");
}