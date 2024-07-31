<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = htmlspecialchars($_POST["username"]);
    $password = htmlspecialchars($_POST["password"]);

    // echo $username;
    // echo "<br>";
    // echo $password;

    header("Location: login.php");
}
else {
    header("Location: login.php");
}