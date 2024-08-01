<?php

// Database Connection
$serverName = "localhost";
$userName = "root";
$password = "";
$dbName = "db";

// Establish initial connection to the server
$con = mysqli_connect($serverName, $userName, $password);

if(!$con)
{
    die("Connection Failed: " . mysqli_connect_error() . "<br>Error Code: " . mysqli_connect_errno());
}

// Database Creation
$dbCreate = "CREATE DATABASE IF NOT EXISTS $dbName";

if(mysqli_query($con,$dbCreate))
{
    echo "Database Created Successfully";
}
else
{
    die("Error Creating Database: " . mysqli_error()); //Check and try adding $conn inside mysqli_error().
}

// Table Creation

mysqli_select_db($con, $db); //Will the code work without this?

$sql="CREATE TABLE IF NOT EXISTS users (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(30) NOT NULL,
    pwd VARCHAR(255) NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIME
)";

if(mysqli_query($conn,$sql))
{
    echo "<br>Table Created Successfully";
}
else
{
    echo "Error Creating Table: " . mysqli_error($conn);
}

header("Location: pages/signup/signup.php");