<?php
include_once "../../includes/dbh.inc.php";
session_start();

if (!isset($_SESSION["usersid"])) {
    header("Location: ../login/login.php");
    exit();
}

$user_id = $_SESSION["usersid"];

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
$sql = "INSERT INTO user_profiles (profile_usersId, full_name, username, identity, phone, city, bio, degree_type, 
        institution, field_of_study, graduation_month, graduation_year, links, profile_completed) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1) 
        ON DUPLICATE KEY UPDATE 
        full_name = VALUES(full_name),
        username = VALUES(username),
        identity = VALUES(identity),
        phone = VALUES(phone),
        city = VALUES(city),
        bio = VALUES(bio),
        degree_type = VALUES(degree_type),
        institution = VALUES(institution),
        field_of_study = VALUES(field_of_study),
        graduation_month = VALUES(graduation_month),
        graduation_year = VALUES(graduation_year),
        links = VALUES(links),
        profile_completed = 1";

$stmt = mysqli_stmt_init($con);
if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("Location: profile-edit.php?error=sqlerror");
    exit();
}

mysqli_stmt_bind_param($stmt, "issssssssssis", 
    $user_id,
    $_POST['full-name'],
    $_POST['username'],
    $_POST['identity'],
    $_POST['phone'],
    $_POST['city'],
    $_POST['bio'],
    $_POST['degree-type'],
    $_POST['institution'],
    $_POST['field-of-study'],
    $_POST['graduation-month'],
    $_POST['graduation-year'],
    $_POST['links']
);

mysqli_stmt_execute($stmt);

// Update volunteer profile if user is volunteer or both
if ($user_type === 'volunteer' || $user_type === 'both') {
    $volSql = "INSERT INTO user_profiles_vol (userid, emergency_name, emergency_phone, vol_profile_completed) 
               VALUES (?, ?, ?, 1)
               ON DUPLICATE KEY UPDATE 
               emergency_name = VALUES(emergency_name),
               emergency_phone = VALUES(emergency_phone),
               vol_profile_completed = 1";
    
    $volStmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($volStmt, $volSql)) {
        header("Location: profile-edit.php?error=sqlerror");
        exit();
    }
    
    mysqli_stmt_bind_param($volStmt, "iss",
        $user_id,
        $_POST['emergency-name'],
        $_POST['emergency-phone']
    );
    
    mysqli_stmt_execute($volStmt);
}

// Update organizer profile if user is organizer or both
if ($user_type === 'organizer' || $user_type === 'both') {
    $orgSql = "INSERT INTO user_profiles_org (userid, organization_name, job_title, industry, location, 
               official_address, official_contact_number, org_profile_completed) 
               VALUES (?, ?, ?, ?, ?, ?, ?, 1)
               ON DUPLICATE KEY UPDATE 
               organization_name = VALUES(organization_name),
               job_title = VALUES(job_title),
               industry = VALUES(industry),
               location = VALUES(location),
               official_address = VALUES(official_address),
               official_contact_number = VALUES(official_contact_number),
               org_profile_completed = 1";
    
    $orgStmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($orgStmt, $orgSql)) {
        header("Location: profile-edit.php?error=sqlerror");
        exit();
    }
    
    mysqli_stmt_bind_param($orgStmt, "issssss",
        $user_id,
        $_POST['organization-name'],
        $_POST['job-title'],
        $_POST['industry'],
        $_POST['location'],
        $_POST['official-address'],
        $_POST['official-contact']
    );
    
    mysqli_stmt_execute($orgStmt);
}

header("Location: profile-edit.php?success=updated");
exit(); 