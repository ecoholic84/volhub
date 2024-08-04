<?php

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

// Database Connection
$serverName = "localhost";
$dBUserName = "root";
$dBPassword = "";
$dbName = "db";

// Establish initial connection to the server
$con = mysqli_connect($serverName, $dBUserName, $dBPassword);

if(!$con)
{
    die("Connection Failed: " . mysqli_connect_error() . "<br>Error Code: " . mysqli_connect_errno());
}
else
{
    echo "Connection Success!<br>";
}

// Database Creation
$dbCreate = "CREATE DATABASE IF NOT EXISTS $dbName";

if(mysqli_query($con, $dbCreate))
{
    echo "Database Created Successfully";
}
else
{
    die("Error Creating Database: " . mysqli_error($con));
}

// Select the database
mysqli_select_db($con, $dbName);

// Table Creation
$table_create = "CREATE TABLE IF NOT EXISTS users (
    usersId INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    usersFullname VARCHAR(255) NOT NULL, 
    usersUsername VARCHAR(30) NOT NULL,
    usersEmail VARCHAR(255) NOT NULL, 
    usersPwd VARCHAR(255) NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
)";

if(mysqli_query($con, $table_create))
{
    echo "<br>Table Created Successfully";
}
else
{
    echo "Error Creating Table: " . mysqli_error($con);
}