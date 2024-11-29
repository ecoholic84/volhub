<?php

include_once 'dbh.inc.php'; // Include the connection function

/*.......................SIGNUP FUNCTIONS.......................*/

function emptyInputSignup($email, $pwd, $pwdRepeat) {
    return empty($email) || empty($pwd) || empty($pwdRepeat);
}

function invalidEmail($email) {
    return !filter_var($email, FILTER_VALIDATE_EMAIL);
}

function pwdMatch($pwd, $pwdRepeat) {
    return $pwd !== $pwdRepeat;
}

function emailExists($email) {
    $conn = getDatabaseConnection();
    $sql = "SELECT * FROM users WHERE usersEmail = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        error_log("emailExists - Prepare failed: " . mysqli_stmt_error($stmt));
        return false;
    }

    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $resultData = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($resultData);
    mysqli_stmt_close($stmt);
    return $row;
}

function createUser($email, $pwd, $role = 'user', $user_type = 'volunteer') {
    $conn = getDatabaseConnection();
    $sql = "INSERT INTO users (usersEmail, usersPwd, role, user_type) VALUES (?, ?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        error_log("createUser - Prepare failed: " . mysqli_stmt_error($stmt) . ", SQL: " . $sql);
        return false; 
    }

    $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);
    mysqli_stmt_bind_param($stmt, "ssss", $email, $hashedPwd, $role, $user_type);

    if (!mysqli_stmt_execute($stmt)) {
        error_log("createUser - Execute failed: " . mysqli_stmt_error($stmt));
        return false;
    }

    $user_id = mysqli_insert_id($conn); // Get the ID immediately after successful insert
    mysqli_stmt_close($stmt);
    session_start();
    $_SESSION["usersid"] = $user_id;
    return true; 
}

/*.......................LOGIN FUNCTIONS.......................*/

function emptyInputLogin($email, $pwd) {
    return empty($email) || empty($pwd);
}


function loginUser($email, $pwd) {  // Removed $con parameter
    $conn = getDatabaseConnection();
    $sql = "SELECT * FROM users WHERE usersEmail = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        error_log("loginUser - Prepare failed: " . mysqli_stmt_error($stmt) . ", SQL: " . $sql);
        return false;
    }

    mysqli_stmt_bind_param($stmt, "s", $email);


    if (!mysqli_stmt_execute($stmt)) {
        error_log("loginUser - Execute failed: " . mysqli_stmt_error($stmt));
        return false;
    }


    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        // Verify the password
        $pwdHashed = $row["usersPwd"];
        $checkPwd = password_verify($pwd, $pwdHashed);


        if ($checkPwd === false) {
             return false; // Indicate incorrect password
        } else {
            session_start();
            $_SESSION["usersid"] = $row["usersId"];
            $_SESSION["users_email"] = $row["usersEmail"];
            $_SESSION["role"] = $row["role"];
            $_SESSION["user_type"] = $row["user_type"];
             return true;
        }
    } else {
        return false; // Indicate user not found        
    }
}





/*.......................PROFILE FUNCTIONS.......................*/

function invalidId($username)
{
    return !preg_match("/^[a-zA-Z0-9]*$/", $username);  // Inverted the logic: returns true if INVALID
}

function idExists($username)
{
	$conn = getDatabaseConnection();
    $sql = "SELECT * FROM user_profiles WHERE username = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        error_log("idExists - Prepare failed: " . mysqli_stmt_error($stmt));
        return false; // or handle error as needed
    }

    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
	$resultData = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($resultData);
	mysqli_stmt_close($stmt);

    return $row ? true : false;  // Returns true if username exists, false otherwise
}

function createSharedProfile($user_id, $full_name, $username, $identity, $bio, $degree_type, $institution, $field_of_study, $graduation_month, $graduation_year, $phone, $city, $links, $profilepicture) {
    $conn = getDatabaseConnection();
    $sql = "INSERT INTO user_profiles (profile_usersId, full_name, username, identity, bio, degree_type, institution, field_of_study, graduation_month, graduation_year, phone, city, links, profile_picture) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_stmt_init($conn);
    
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        error_log("createSharedProfile - Prepare failed: " . mysqli_stmt_error($stmt));
        return false;
    }    
    mysqli_stmt_bind_param($stmt, "isssssssssssss", $user_id, $full_name, $username, $identity, $bio, $degree_type, $institution, $field_of_study, $graduation_month, $graduation_year, $phone, $city, $links, $profilepicture);
    

    if (!mysqli_stmt_execute($stmt)) {
        error_log("createSharedProfile - Execute failed: " . mysqli_stmt_error($stmt) );
        return false;
    }

    mysqli_stmt_close($stmt);
    return true;
}

function createVolunteerProfile($user_id, $emergency_name, $emergency_phone) {  // Removed $con
    $conn = getDatabaseConnection();
    $sql = "INSERT INTO user_profiles_vol (userid, emergency_name, emergency_phone) 
            VALUES (?, ?, ?)";
    $stmt = mysqli_stmt_init($conn); // Use $conn
    
    if (!mysqli_stmt_prepare($stmt, $sql)) {
         error_log("createVolunteerProfile - Prepare failed: " . mysqli_stmt_error($stmt));
        return false;
    }
    
    mysqli_stmt_bind_param($stmt, "iss", $user_id, $emergency_name, $emergency_phone);
    
    if (!mysqli_stmt_execute($stmt)) {
         error_log("createVolunteerProfile - Execute failed: " . mysqli_stmt_error($stmt));
        return false;
    }
    
    mysqli_stmt_close($stmt);
    return true; // Return true on success
}

function createOrganizerProfile($user_id, $organization_name, $job_title, $industry, $location, $official_address, $official_contact_number) { // Removed $con
    $conn = getDatabaseConnection();
    $sql = "INSERT INTO user_profiles_org (userid, organization_name, job_title, industry, location, official_address, official_contact_number) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_stmt_init($conn);  // Use $conn
    
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        error_log("createOrganizerProfile - Prepare failed: " . mysqli_stmt_error($stmt));
        return false;
    }    
    
    mysqli_stmt_bind_param($stmt, "issssss", $user_id, $organization_name, $job_title, $industry, $location, $official_address, $official_contact_number);
    
    if (!mysqli_stmt_execute($stmt)) {
        error_log("createOrganizerProfile - Execute failed: " . mysqli_stmt_error($stmt));
        return false;
    }

    mysqli_stmt_close($stmt);
    return true; // Return true on success

}
?>