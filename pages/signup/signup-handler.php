<?php

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

include_once "../../includes/dbh.inc.php";

// Posting data from form to variables.
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $fullname = htmlspecialchars($_POST['fullname']);
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $pwd = htmlspecialchars($_POST['pwd']);
    $pwdRepeat = htmlspecialchars($_POST['pwdrepeat']);
    $created_at = date('Y-m-d H:i:s');

    require_once '../../includes/functions.inc.php';

    /*.......................ERROR HANDLERS.......................*/

    // Function to exit, if no value is inputted by user.
    if (emptyInputSignup($fullname, $username, $email, $pwd, $pwdRepeat) !== false)
    {
        header("Location: signup.php?error=emptyInput");
        exit();
    }

    // Function to check if the username is valid.
    if (!invalidId($username))
    {
        header("Location: signup.php?error=invalidUsername");
        exit();
    }
    
    // Function to check if username is taken.
    if (idExists($con, $username) !== false)
    {
        header("Location: signup.php?error=usernameTaken");
        exit();
    }

    // Function to validate email id.
    if (invalidEmail($email) !== false)
    {
        header("Location: signup.php?error=invalidEmail");
        exit();
    }

    // Function to check if email is already registered.
    if (emailExists($con, $email) !== false)
    {
        header("Location: signup.php?error=emailTaken");
        exit();
    }

    // Function to check if the password is less than 8.
    if (strlen($pwd) < 8)
    {
        header("Location: signup.php?error=passwordTooShort");
        exit();
    }

    // Function to check if the password repeat matches.
    if (pwdMatch($pwd, $pwdRepeat) !== false)
    {
        header("Location: signup.php?error=passwordsDontMatch");
        exit();
    }

    /*........................................................*/

    createUser($con, $fullname, $username, $email, $pwd, $created_at);
}
else
{
    // Redirects illegal users to signup page.
    header("Location: signup.php");
}