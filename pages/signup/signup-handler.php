<?php
header('Content-Type: application/json');
include_once "../../includes/dbh.inc.php";
include_once '../../includes/functions.inc.php';

$response = ['success' => false, 'error' => ''];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = htmlspecialchars($_POST['fullname']);
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $pwd = htmlspecialchars($_POST['pwd']);
    $pwdRepeat = htmlspecialchars($_POST['pwdrepeat']);
    $created_at = date('Y-m-d H:i:s');

    if (emptyInputSignup($fullname, $username, $email, $pwd, $pwdRepeat)) {
        $response['error'] = "Fill in all fields!";
    } elseif (!invalidId($username)) {
        $response['error'] = "Choose a proper username!";
    } elseif (idExists($con, $username)) {
        $response['error'] = "Sorry, username is taken. Try again!";
    } elseif (invalidEmail($email)) {
        $response['error'] = "Choose a proper email!";
    } elseif (emailExists($con, $email)) {
        $response['error'] = "Sorry, this email is already registered. Use another email!";
    } elseif (strlen($pwd) < 8) {
        $response['error'] = "Password should be at least 8 characters!";
    } elseif (pwdMatch($pwd, $pwdRepeat)) {
        $response['error'] = "Passwords don't match!";
    } else {
        if (createUser($con, $fullname, $username, $email, $pwd, $created_at)) {
            $response['success'] = true;
        } else {
            $response['error'] = "Something went wrong, try again!";
        }
    }
}

echo json_encode($response);