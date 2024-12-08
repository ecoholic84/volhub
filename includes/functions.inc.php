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

function createUser($con, $email, $pwd, $created_at, $role, $user_type, $volunteer, $organizer)
{
    $sql = "INSERT INTO users (usersEmail, usersPwd, created_at, role, user_type, volunteer, organizer) VALUES (?, ?, ?, ?, ?, ?, ?);";
    $stmt = mysqli_stmt_init($con);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: signup.php?error=stmtfailed");
        exit();
    }

    $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);

    mysqli_stmt_bind_param($stmt, "sssssii", $email, $hashedPwd, $created_at, $role, $user_type, $volunteer, $organizer);
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
            $_SESSION["user_type"] = $row["user_type"];

            // Redirect to the dashboard
            if ($row["role"] === 'admin') {
                header("Location: /volhub/includes/admin-dashboard.php");
            } else {
                // Assuming we already have the user's information in $row
                // and it includes a 'user_type' field that can be 'volunteer', 'organizer', or 'both'
                
                switch($row['user_type']) {
                    case 'both':
                    case 'volunteer':
                        // If user is both or volunteer, redirect to volunteer dashboard
                        header("Location: /volhub/includes/dashboard.php");
                        break;
                    
                    case 'organizer':
                        // If user is organizer only, redirect to organizer dashboard
                        header("Location: /volhub/includes/org-dashboard.php");
                        break;
                    
                    default:
                        // If user_type is not set or is something else, 
                        // redirect to create volunteer profile
                        header("Location: /volhub/pages/profile/vol-profile-creation.php");
                }
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
    $sql = "SELECT * FROM user_profiles WHERE username = ?;";
    $stmt = mysqli_stmt_init($con);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: vol-profile-creation.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    $exists = mysqli_fetch_assoc($resultData) !== null;

    mysqli_stmt_close($stmt);
    
    return $exists;
}

function createSharedProfile($con, $user_id, $full_name, $username, $identity, $bio, $degree_type, $institution, $field_of_study, $graduation_month, $graduation_year, $phone, $city, $links) {
    $sql = "INSERT INTO user_profiles (profile_usersId, full_name, username, identity, bio, degree_type, institution, field_of_study, graduation_month, graduation_year, phone, city, links) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_stmt_init($con);
    
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        return false;
    }
    
    mysqli_stmt_bind_param($stmt, "issssssssssss", $user_id, $full_name, $username, $identity, $bio, $degree_type, $institution, $field_of_study, $graduation_month, $graduation_year, $phone, $city, $links);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    
    return $result;
}

function createVolunteerProfile($con, $user_id, $emergency_name, $emergency_phone) {
    $sql = "INSERT INTO user_profiles_vol (userid, emergency_name, emergency_phone) 
            VALUES (?, ?, ?)";
    $stmt = mysqli_stmt_init($con);
    
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        return false;
    }
    
    mysqli_stmt_bind_param($stmt, "iss", $user_id, $emergency_name, $emergency_phone);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    
    return $result;
}

function createOrganizerProfile($con, $user_id, $organization_name, $job_title, $industry, $location, $official_address, $official_contact_number) {
    $sql = "INSERT INTO user_profiles_org (userid, organization_name, job_title, industry, location, official_address, official_contact_number) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_stmt_init($con);
    
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        error_log("SQL Prepare Failed: " . mysqli_stmt_error($stmt));
        return false;
    }    
    
    mysqli_stmt_bind_param($stmt, "issssss", $user_id, $organization_name, $job_title, $industry, $location, $official_address, $official_contact_number);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    
    return $result;
}

/**
 * Check if user needs profile completion reminder
 * @param mysqli $con Database connection
 * @param int $userId User ID
 * @return bool True if profile needs completion
 */
function needsProfileCompletion($con, $userId) {
    $sql = "SELECT profile_completed FROM users WHERE usersId = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($row = mysqli_fetch_assoc($result)) {
        return $row['profile_completed'] == 0;
    }
    return false;
}

// Function to update volunteer profile completion status
function updateVolProfileStatus($con, $userId) {
    $sql = "UPDATE user_profiles_vol SET vol_profile_completed = 1 WHERE userid = ?";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../pages/profile/profile-creation.php?error=stmtfailed");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

// Function to update organizer profile completion status
function updateOrgProfileStatus($con, $userId) {
    $sql = "UPDATE user_profiles_org SET org_profile_completed = 1 WHERE userid = ?";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../pages/profile/profile-creation.php?error=stmtfailed");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

// Function to check volunteer profile completion status
function isVolProfileComplete($con, $userId) {
    $sql = "SELECT vol_profile_completed FROM user_profiles_vol WHERE userid = ?";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        return false;
    }
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($row = mysqli_fetch_assoc($result)) {
        return (bool)$row['vol_profile_completed'];
    }
    return false;
}

// Function to check organizer profile completion status
function isOrgProfileComplete($con, $userId) {
    $sql = "SELECT org_profile_completed FROM user_profiles_org WHERE userid = ?";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        return false;
    }
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($row = mysqli_fetch_assoc($result)) {
        return (bool)$row['org_profile_completed'];
    }
    return false;
}