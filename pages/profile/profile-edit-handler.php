<?php
include_once "../../includes/dbh.inc.php";
session_start();

if (!isset($_SESSION["usersid"])) {
    header("Location: ../login/login.php");
    exit();
}

$user_id = $_SESSION["usersid"];

// Get current username
$currentUsernameQuery = "SELECT username FROM user_profiles WHERE profile_usersId = ?";
$currentStmt = mysqli_stmt_init($con);
if (!mysqli_stmt_prepare($currentStmt, $currentUsernameQuery)) {
    header("Location: profile-edit.php?error=sqlerror");
    exit();
}
mysqli_stmt_bind_param($currentStmt, "i", $user_id);
mysqli_stmt_execute($currentStmt);
$currentResult = mysqli_stmt_get_result($currentStmt);
$currentRow = mysqli_fetch_assoc($currentResult);
$currentUsername = $currentRow ? $currentRow['username'] : '';
mysqli_stmt_close($currentStmt);

// Only check for username uniqueness if username is being changed
$newUsername = trim($_POST['username']);
if ($newUsername !== $currentUsername) {
    // Check if username already exists (excluding current user)
    $checkUsername = "SELECT profile_usersId FROM user_profiles WHERE username = ? AND profile_usersId != ?";
    $checkStmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($checkStmt, $checkUsername)) {
        header("Location: profile-edit.php?error=sqlerror");
        exit();
    }
    mysqli_stmt_bind_param($checkStmt, "si", $newUsername, $user_id);
    mysqli_stmt_execute($checkStmt);
    mysqli_stmt_store_result($checkStmt);

    if (mysqli_stmt_num_rows($checkStmt) > 0) {
        // Username already exists
        mysqli_stmt_close($checkStmt);
        header("Location: profile-edit.php?error=usernametaken");
        exit();
    }
    mysqli_stmt_close($checkStmt);
}

// Get user type
$userTypeQuery = "SELECT user_type FROM users WHERE usersId = ?";
$userTypeStmt = mysqli_stmt_init($con);
if (!mysqli_stmt_prepare($userTypeStmt, $userTypeQuery)) {
    header("Location: profile-edit.php?error=sqlerror");
    exit();
}
mysqli_stmt_bind_param($userTypeStmt, "i", $user_id);
mysqli_stmt_execute($userTypeStmt);
$userTypeResult = mysqli_stmt_get_result($userTypeStmt);
$userTypeRow = mysqli_fetch_assoc($userTypeResult);
$user_type = $userTypeRow['user_type'];

// Update basic profile information
$sql = "UPDATE user_profiles SET 
        full_name = ?,
        username = ?,
        identity = ?,
        phone = ?,
        city = ?,
        bio = ?,
        degree_type = ?,
        institution = ?,
        field_of_study = ?,
        graduation_month = ?,
        graduation_year = ?,
        links = ?,
        profile_completed = 1
        WHERE profile_usersId = ?";

$stmt = mysqli_stmt_init($con);
if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("Location: profile-edit.php?error=sqlerror");
    exit();
}

mysqli_stmt_bind_param($stmt, "ssssssssssssi", 
    $_POST['full-name'],
    $newUsername,
    $_POST['identity'],
    $_POST['phone'],
    $_POST['city'],
    $_POST['bio'],
    $_POST['degree-type'],
    $_POST['institution'],
    $_POST['field-of-study'],
    $_POST['graduation-month'],
    $_POST['graduation-year'],
    $_POST['links'],
    $user_id
);

if (!mysqli_stmt_execute($stmt)) {
    // Check if the error is due to duplicate username (in case of race condition)
    if (mysqli_errno($con) === 1062) { // 1062 is MySQL error code for duplicate entry
        header("Location: profile-edit.php?error=usernametaken");
        exit();
    }
    header("Location: profile-edit.php?error=sqlerror");
    exit();
}

// Update volunteer profile if user is volunteer or both
if ($user_type === 'volunteer' || $user_type === 'both') {
    $volSql = "UPDATE user_profiles_vol SET 
               emergency_name = ?,
               emergency_phone = ?,
               vol_profile_completed = 1
               WHERE userid = ?";
    
    $volStmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($volStmt, $volSql)) {
        header("Location: profile-edit.php?error=sqlerror");
        exit();
    }
    
    mysqli_stmt_bind_param($volStmt, "ssi",
        $_POST['emergency-name'],
        $_POST['emergency-phone'],
        $user_id
    );
    
    mysqli_stmt_execute($volStmt);
}

// Update organizer profile if user is organizer or both
if ($user_type === 'organizer' || $user_type === 'both') {
    $orgSql = "UPDATE user_profiles_org SET 
               organization_name = ?,
               job_title = ?,
               industry = ?,
               location = ?,
               official_address = ?,
               official_contact_number = ?,
               org_profile_completed = 1
               WHERE userid = ?";
    
    $orgStmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($orgStmt, $orgSql)) {
        header("Location: profile-edit.php?error=sqlerror");
        exit();
    }
    
    mysqli_stmt_bind_param($orgStmt, "ssssssi",
        $_POST['organization-name'],
        $_POST['job-title'],
        $_POST['industry'],
        $_POST['location'],
        $_POST['official-address'],
        $_POST['official-contact'],
        $user_id
    );
    
    mysqli_stmt_execute($orgStmt);
}

header("Location: profile-edit.php?success=updated");
exit(); 