<?php

include_once "../../includes/dbh.inc.php"; 

if ($_SERVER["REQUEST_METHOD"] == "POST")
{

    $username = htmlspecialchars($_POST['username']);
    $pwd = htmlspecialchars($_POST['pwd']);
    $created_at = date('Y-m-d H:i:s');

    require_once '../../includes/functions.inc.php';

    /*.......................ERROR HANDLERS.......................*/

    // Function to exit, if no value is inputted by user.
    if (emptyInputLogin($username, $pwd) !== false)
    {
        header("Location: login.php?error=emptyInput");
        exit();
    }

    loginUser($con, $username, $pwd);






    // $insert = "INSERT INTO users (username, pwd, created_at) VALUES ('$username', '$pwd', '$created_at')";

    // if(mysqli_query($con,$insert))
    // {
    //     echo "New Record Inserted.";
    // }
    // else
    // {
    //     echo "Error: ".$insert.mysqli_error($con);
    // }

    // if (empty($username) || empty($pwd)) {
    //     exit();
    // }    

    // echo $username;
    // echo "<br>";
    // echo $pwd;

    // header("Location: ../profile-creation/profile-creation.php");
    header("Location: login.php");
}
else
{
    header("Location: login.php");
    exit();
}