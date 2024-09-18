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


// Database Creation
$dbCreate = "CREATE DATABASE IF NOT EXISTS $dbName";

if(!mysqli_query($con, $dbCreate))
{
    die("Error Creating Database: " . mysqli_error($con));
}

// Select the database
mysqli_select_db($con, $dbName);

// Table Creation
// Ensure the data types and unsigned attribute match for both tables
$table_create = "CREATE TABLE IF NOT EXISTS users (
    usersId INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    usersEmail VARCHAR(255) NOT NULL UNIQUE,
    usersPwd VARCHAR(255) NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    role ENUM('admin', 'user') NOT NULL DEFAULT 'user',
    user_type SET('volunteer', 'organizer') NOT NULL DEFAULT 'volunteer'
)";

$table2_create = "CREATE TABLE IF NOT EXISTS user_profiles (
    profile_id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    profile_usersId INT(11) UNSIGNED NOT NULL,
    full_name VARCHAR(255) NOT NULL,
    username VARCHAR(50) NOT NULL,
    identity VARCHAR(20),
    phone VARCHAR(20),
    city VARCHAR(50),
    bio TEXT,
    degree_type VARCHAR(50),
    institution VARCHAR(100),
    field_of_study VARCHAR(100),
    graduation_month VARCHAR(20),
    graduation_year INT,
    links TEXT,
    FOREIGN KEY (profile_usersId) REFERENCES users(usersId) ON DELETE CASCADE
)";

$table3_create = "CREATE TABLE IF NOT EXISTS  user_profiles_vol (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    emergency_name VARCHAR(100) NOT NULL,
    emergency_phone VARCHAR(20) NOT NULL,
    userid INT(11) UNSIGNED NOT NULL,
    FOREIGN KEY (userid) REFERENCES users(usersId) ON DELETE CASCADE
)";


$table4_create = "CREATE TABLE IF NOT EXISTS user_profiles_org (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    organization_name VARCHAR(255) NOT NULL,
    job_title VARCHAR(100) NOT NULL,
    industry VARCHAR(50),
    location VARCHAR(255),
    official_address TEXT,
    official_contact_number VARCHAR(20),
    userid INT(11) UNSIGNED NOT NULL,
    FOREIGN KEY (userid) REFERENCES users(usersId) ON DELETE CASCADE
)";

$table5_create = "CREATE TABLE IF NOT EXISTS Events (
    event_id INT AUTO_INCREMENT PRIMARY KEY,
    organizer_id INT(11) UNSIGNED NOT NULL,
    event_name VARCHAR(255) NOT NULL,
    event_description TEXT,
    event_datetime DATETIME NOT NULL,
    event_location VARCHAR(255) NOT NULL,
    event_thumbnail VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (organizer_id) REFERENCES users(usersId) ON DELETE CASCADE
);";


if(!mysqli_query($con, $table_create)) {

    die("Error Creating Table: " . mysqli_error($con));
}

if(!mysqli_query($con, $table2_create)) {

    die("Error Creating Table: " . mysqli_error($con));
}

if(!mysqli_query($con, $table3_create)) {

    die("Error Creating Table: " . mysqli_error($con));
}

if(!mysqli_query($con, $table4_create)) {

    die("Error Creating Table: " . mysqli_error($con));
}

if(!mysqli_query($con, $table5_create)) {

    die("Error Creating Table: " . mysqli_error($con));
}