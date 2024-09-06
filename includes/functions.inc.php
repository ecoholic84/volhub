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

function createUser($con, $email, $pwd, $created_at, $role)
{
    $sql = "INSERT INTO users (usersEmail, usersPwd, created_at, role) VALUES (?, ?, ?, ?);";
    $stmt = mysqli_stmt_init($con);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: signup.php?error=stmtfailed");
        exit();
    }

    $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);

    mysqli_stmt_bind_param($stmt, "ssss", $email, $hashedPwd, $created_at, $role);
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

function emptyInputLogin($email, $pwd)
{
    $result;
    if (empty($email) || empty($pwd)) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

function loginUser($con, $email, $pwd)
{
    // Check if the email exists in the users table
    $sql = "SELECT * FROM users WHERE usersEmail = ?;";
    $stmt = mysqli_stmt_init($con);
    
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: login.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        // Verify the password
        $pwdHashed = $row["usersPwd"];
        $checkPwd = password_verify($pwd, $pwdHashed);

        if ($checkPwd === false) {
            header("Location: login.php?error=wrongLogin");
            exit();
        } else {
            // Start the session and set session variables
            session_start();
            $_SESSION["usersid"] = $row["usersId"];
            $_SESSION["users_email"] = $row["usersEmail"];
            $_SESSION["role"] = $row["role"];

            // Redirect to the dashboard
            if ($row["role"] === 'admin') {
                header("Location: /miniProject/includes/admin-dashboard.php");
            } else {
                header("Location: /miniProject/includes/dashboard.php");
            }
            exit();
        }
    } else {
        // If the email doesn't exist
        header("Location: login.php?error=wrongLogin");
        exit();
    }
}


/*.......................PROFILE FUNCTIONS.......................*/

function invalidId($username)
{
    return preg_match("/^[a-zA-Z0-9]*$/", $username);
}

function idExists($con, $username)
{
    $sql = "SELECT * FROM UserProfiles WHERE username = ?;";
    $stmt = mysqli_stmt_init($con);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: profile-creation.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    $exists = mysqli_fetch_assoc($resultData) !== null;

    mysqli_stmt_close($stmt);
    
    return $exists;
}

function createProfile($con, $user_id, $full_name, $username, $identity, $bio, $degree_type, $institution, $field_of_study, $graduation_month, $graduation_year, $phone, $city, $emergency_name, $emergency_phone, $links)
{
    $sql = "INSERT INTO UserProfiles (profile_usersId, full_name, username, identity, bio, degree_type, institution, field_of_study, graduation_month, graduation_year, phone, city, emergency_name, emergency_phone, links) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo "SQL error";
    } else {
        mysqli_stmt_bind_param($stmt, "issssssssssssss", $user_id, $full_name, $username, $identity, $bio, $degree_type, $institution, $field_of_study, $graduation_month, $graduation_year, $phone, $city, $emergency_name, $emergency_phone, $links);
        mysqli_stmt_execute($stmt);
        echo "Profile created successfully!";
    }
    mysqli_stmt_close($stmt);

    // Redirect the user to the profile page or wherever you want
    header("Location: ../../includes/dashboard.php");
    exit();
}
