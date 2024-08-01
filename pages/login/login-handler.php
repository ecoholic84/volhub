<?php

if ($_SERVER["REQUEST_METHOD"] == "POST")
{

    $username = htmlspecialchars($_POST['username']);
    $pwd = htmlspecialchars($_POST['pwd']);
    $created_at = $_POST['created_at'];
    $sql = "INSERT INTO users (username, pwd, created_at) VALUES ('$username', '$pwd', '$created_at')";

    // if(mysqli_query($conn,$sql))
    // {
    //     echo "New Record Inserted.";
    // }
    // else
    // {
    //     echo "Error: ".$sql.mysqli_error($conn);
    // }

    if (empty($username) || empty($pwd)) {
        exit();
    }    

    // echo $username;
    // echo "<br>";
    // echo $pwd;

    // header("Location: ../profile-creation/profile-creation.php");
    header("Location: login.php");
}
else
{
    header("Location: login.php");
    die();
}