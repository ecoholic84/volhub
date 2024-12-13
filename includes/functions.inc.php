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

// Keep these consolidated functions
function checkProfileStatus($con, $user_id) {
    return [
        'basic_complete' => checkBasicProfileStatus($con, $user_id),
        'vol_complete' => checkVolProfileStatus($con, $user_id),
        'org_complete' => checkOrgProfileStatus($con, $user_id)
        ];
}

function checkBasicProfileStatus($con, $user_id) {
    $basicQuery = "SELECT profile_completed FROM user_profiles WHERE profile_usersId = ?";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $basicQuery)) return false;
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $basicProfile = mysqli_fetch_assoc($result);
    return $basicProfile ? (bool)$basicProfile['profile_completed'] : false;
}

function checkVolProfileStatus($con, $user_id) {
    $sql = "SELECT vol_profile_completed FROM user_profiles_vol WHERE userid = ?";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) return false;
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $profile = mysqli_fetch_assoc($result);
    return $profile ? (bool)$profile['vol_profile_completed'] : false;
}

function checkOrgProfileStatus($con, $user_id) {
    $sql = "SELECT org_profile_completed FROM user_profiles_org WHERE userid = ?";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) return false;
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $profile = mysqli_fetch_assoc($result);
    return $profile ? (bool)$profile['org_profile_completed'] : false;
}

// Add these update functions back
function updateBasicProfileStatus($con, $user_id) {
    $sql = "UPDATE user_profiles SET profile_completed = 1 WHERE profile_usersId = ?";
    $stmt = mysqli_stmt_init($con);
    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return true;
    }
    return false;
}

function updateVolProfileStatus($con, $user_id) {
    $sql = "UPDATE user_profiles_vol SET vol_profile_completed = 1 WHERE userid = ?";
    $stmt = mysqli_stmt_init($con);
    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return true;
    }
    return false;
}

function updateOrgProfileStatus($con, $user_id) {
    $sql = "UPDATE user_profiles_org SET org_profile_completed = 1 WHERE userid = ?";
    $stmt = mysqli_stmt_init($con);
    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return true;
    }
    return false;
}