<?php

/*.......................SIGNUP FUNCTIONS.......................*/

function emptyInputSignup($email, $pwd, $pwdRepeat)
{
    $result;
    if (empty($email) || empty($pwd) || empty($pwdRepeat)) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

function invalidEmail($email)
{
    $result;
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

function pwdMatch($pwd, $pwdRepeat)
{
    $result;
    if ($pwd !== $pwdRepeat) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

function emailExists($con, $email)
{
    $sql = "SELECT * FROM users WHERE usersEmail = ?;";
    $stmt = mysqli_stmt_init($con);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: signup.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row;
    } else {
        $result = false;
        return $result;
    }
    mysqli_stmt_close($stmt);
}

function createUser($con, $email, $pwd, $created_at)
{
    $sql = "INSERT INTO users (usersEmail, usersPwd, created_at) VALUES (?, ?, ?);";
    $stmt = mysqli_stmt_init($con);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: signup.php?error=stmtfailed");
        exit();
    }

    $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);

    mysqli_stmt_bind_param($stmt, "sss", $email, $hashedPwd, $created_at);
    mysqli_stmt_execute($stmt);
    // Get the last inserted user's ID
    $user_id = mysqli_insert_id($con);
    mysqli_stmt_close($stmt);
    // Start the session and set session variables

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION["usersid"] = $user_id;

    // Redirect the user to the profile page or wherever you want
    header("Location: ../profile/profile-index.php");
    exit();
}

/*.......................LOGIN FUNCTIONS.......................*/

function emptyInputLogin($username, $pwd)
{
    $result;
    if (empty($username) || empty($pwd)) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

function loginUser($con, $username, $pwd)
{
    $idExists = idExists($con, $username, $username);

    if ($idExists == false) {
        header("Location: login.php?error=wrongLogin");
        exit();
    }

    $pwdHashed = $idExists["usersPwd"];
    $checkPwd = password_verify($pwd, $pwdHashed);

    if ($checkPwd == false) {
        header("Location: login.php?error=wrongLogin");
        exit();
    } else if ($checkPwd == true) {
        session_start();
        $_SESSION["users_id"] = $idExists["usersId"];
        $_SESSION["users_username"] = $idExists["usersUsername"];

        header("Location: dashboard.php");
        exit();
    }
}

/*.......................PROFILE FUNCTIONS.......................*/

function invalidId($username)
{
    return preg_match("/^[a-zA-Z0-9]*$/", $username);
}

// function idExists($con, $username)
// {
//     $sql = "SELECT * FROM UserProfiles WHERE username = ?;";
//     $stmt = mysqli_stmt_init($con);

//     if (!mysqli_stmt_prepare($stmt, $sql)) {
//         header("Location: profile-creation.php?error=stmtfailed");
//         exit();
//     }

//     mysqli_stmt_bind_param($stmt, "s", $username);
//     mysqli_stmt_execute($stmt);

//     $resultData = mysqli_stmt_get_result($stmt);

//     $exists = mysqli_fetch_assoc($resultData) !== null;

//     mysqli_stmt_close($stmt);
    
//     return $exists;
// }
