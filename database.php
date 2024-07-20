<?php
include "connection.php";

$sql="CREATE DATABASE IF NOT EXISTS db";

if(mysqli_query($conn,$sql))
{
    echo "Database Created Successfully";
}
else
{
    echo  "Error Creating Database: ".mysqli_error(); //Check and try adding $conn inside mysqli_error().
}
?>