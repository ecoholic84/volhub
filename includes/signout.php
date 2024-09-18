<?php
session_start(); // Start the session

// Destroy all session data
session_unset(); 
session_destroy(); 

// Redirect the user to the login page
header("Location: /miniProject/index.php");
exit();
?>
