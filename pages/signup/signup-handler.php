<?php

include "../../dhb.inc.php"

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $fullname = $_POST['fullname']; // Add htmlspecialchars()
    $username = $_POST['username']; // Add htmlspecialchars()
    $email = $_POST['email']; // Add htmlspecialchars()
    $pwd = $_POST['pwd']; //Add htmlspecialchars()
    $created_at = $_POST['created_at'];

    $insert = "INSERT INTO users (fullname, username, email, pwd, created_at) VALUES ('$fullname', '$username', '$email', '$pwd', '$created_at')";

    if(mysqli_query($con, $insert))
    {
        echo "New Record Inserted.";
    }
    else
    {
        echo "Error: " . $insert.mysqli_error($con);
    }

    if (empty($username) || empty($pwd))
    {
        exit();
    }    

    echo $username;
    echo "<br>";
    echo $pwd;

    header("Location: signup.php");
}
else
{
    header("Location: signup.php");
    die();
}