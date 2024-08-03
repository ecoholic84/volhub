<?php

function emptyInputSignup($fullname, $username, $email, $pwd, $pwdRepeat)
{
    $result;
    if (empty($fullname) || empty($username) || empty($email) || empty($pwd) || empty($pwdRepeat))
    {
        $result = true;
    }
    else
    {
        $result = false;
    }
    return $result;
}

function invalidId($username)
{
    return preg_match("/^[a-zA-Z0-9]*$/", $username);
}

function invalidEmail($email)
{
    $result;
    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
    {
        $result = true;
    }
    else
    {
        $result = false;
    }
    return $result;
}

function pwdMatch($pwd, $pwdRepeat)
{
    $result;
    if ($pwd !== $pwdRepeat)
    {
        $result = true;
    }
    else
    {
        $result = false;
    }
    return $result;
}

function idExists($con, $username, $email)
{
    $sql = "SELECT * FROM users WHERE username = ? OR email = ?;";
    $stmt = mysqli_stmt_init($con);

    if (!mysqli_stmt_prepare($stmt, $sql))
    {
        header("Location: signup.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ss", $username, $email);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData))
    {
        return $row;
    }
    else
    {
        $result = false;
        return $result;
    }
    mysqli_stmt_close($stmt);
}

function createUser($con, $fullname, $username, $email, $pwd, $created_at)
{
    $sql = "INSERT INTO users (fullname, username, email, pwd, created_at) VALUES (?, ?, ?, ?, ?);";
    $stmt = mysqli_stmt_init($con);

    if (!mysqli_stmt_prepare($stmt, $sql))
    {
        header("Location: signup.php?error=stmtfailed");
        exit();
    }
    
    $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);

    mysqli_stmt_bind_param($stmt, "sssss", $fullname, $username, $email, $hashedPwd, $created_at);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("Location: ../profile-creation.php");
    exit();
}