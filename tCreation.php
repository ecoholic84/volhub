<?php
include "database.php";

mysqli_select_db($conn,"db"); //Will the code work without this?

$sql="CREATE TABLE IF NOT EXISTS t1
(
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(30) NOT NULL,
email VARCHAR(50) NOT NULL,
phone INT(10) NOT NULL)
";

if(mysqli_query($conn,$sql))
{
    echo "<br>Table Created Successfully";
}
else
{
    echo "Error Creating Table: ".mysqli_error($conn);
}
?>
