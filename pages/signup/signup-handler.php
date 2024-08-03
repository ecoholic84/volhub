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
    $pwdRepeat = htmlspecialchars($_POST['pwdrepeat']);
    $created_at = date('Y-m-d H:i:s');

    require_once 'functions.inc.php';

    /*.......................ERROR HANDLERS.......................*/

    // Function to exit, if no value is inputted by user.
    if (emptyInputSignup($fullname, $username, $email, $pwd, $pwdRepeat) !== false)
    {
        header("Location: signup.php?error=emptyinput");
        exit();
    }

    // Function to check if the username is valid.
    if (!invalidId($username))
    {
        header("Location: signup.php?error=invalidusername");
        exit();
    }
    
    // Function to check if username is taken.
    if (idExists($con, $username, $email) !== false)
    {
        header("Location: signup.php?error=usernameTaken");
        exit("Username is taken. Try another.");
    }

    // Function to validate email id.
    if (invalidEmail($email) !== false)
    {
        header("Location: signup.php?error=invalidemail");
        exit();
    }

    // Function to check if the password is less than 8.
    if (strlen($pwd) < 8)
    {
        header("Location: signup.php?error=passwordtooShort");
        exit("Password should have atleast 8 characters.");
    }

    // Function to check if the password repeat matches.
    if (pwdMatch($pwd, $pwdRepeat) !== false)
    {
        header("Location: signup.php?error=passwordsdontmatch");
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