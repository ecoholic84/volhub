<?php

include_once "../../includes/dbh.inc.php";
include_once '../../includes/functions.inc.php';

if ($_SERVER["REQUEST_METHOD"] == "POST")
{

    $email = htmlspecialchars($_POST['email']);
    $pwd = htmlspecialchars($_POST['pwd']);
    $created_at = date('Y-m-d H:i:s');

    /*.......................ERROR HANDLERS.......................*/

    // Function to exit, if no value is inputted by user.
    if (emptyInputLogin($email, $pwd) !== false)
    {
        header("Location: login.php?error=emptyInput");
        exit();
    }

    loginUser($con, $email, $pwd);

    header("Location: login.php");
}
else
{
    header("Location: login.php");
    exit();
}