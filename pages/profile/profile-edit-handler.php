<?php
include_once "../../includes/dbh.inc.php";
session_start();

if (!isset($_SESSION["usersid"])) {
    header("Location: ../login/login.php");
    exit();
}

$userId = $_SESSION["usersid"];

// Get form data
$fullName = $_POST['full-name'];
$username = $_POST['username'];
$identity = $_POST['identity'];
$bio = $_POST['bio'];
$degreeType = $_POST['degree-type'];
$institution = $_POST['institution'];
$fieldOfStudy = $_POST['field-of-study'];
$graduationMonth = $_POST['graduation-month'];
$graduationYear = $_POST['graduation-year'];
$links = $_POST['links'];
$phone = $_POST['phone'];
$city = $_POST['city'];
$emergencyName = $_POST['emergency-name'] ?? ''; // For volunteers
$emergencyPhone = $_POST['emergency-phone'] ?? ''; // For volunteers
$organizationName = $_POST['organization_name'] ?? ''; // For organizers
$jobTitle = $_POST['job_title'] ?? ''; // For organizers
$industry = $_POST['industry'] ?? ''; // For organizers
$location = $_POST['location'] ?? ''; // For organizers
$officialAddress = $_POST['official_address'] ?? ''; // For organizers
$officialContactNumber = $_POST['official_contact_number'] ?? ''; // For organizers

// Handle profile picture upload
$profilePicture = $_FILES['profile_picture']['name'];
$profilePictureTmp = $_FILES['profile_picture']['tmp_name'];
$uploadDir = "uploads/"; // Make sure this directory exists and is writable

if ($profilePicture) {
    $profilePicturePath = $uploadDir . basename($profilePicture);
    if (move_uploaded_file($profilePictureTmp, $profilePicturePath)) {
        // Update profile picture path in database
        $updatePictureQuery = "UPDATE user_profiles SET profile_picture = ? WHERE profile_usersId = ?";
        $updatePictureStmt = mysqli_prepare($con, $updatePictureQuery);
        mysqli_stmt_bind_param($updatePictureStmt, "si", $profilePicture, $userId);
        mysqli_stmt_execute($updatePictureStmt);
    } else {
        // Handle upload error
        echo "Error uploading profile picture.";
    }
}

// Update user_profiles table
$updateQuery = "UPDATE user_profiles SET 
                full_name = ?, 
                username = ?, 
                identity = ?, 
                bio = ?, 
                degree_type = ?, 
                institution = ?, 
                field_of_study = ?, 
                graduation_month = ?, 
                graduation_year = ?, 
                links = ?, 
                phone = ?, 
                city = ? 
                WHERE profile_usersId = ?";

$updateStmt = mysqli_prepare($con, $updateQuery);
mysqli_stmt_bind_param($updateStmt, "ssssssssssssi", $fullName, $username, $identity, $bio, $degreeType, $institution, $fieldOfStudy, $graduationMonth, $graduationYear, $links, $phone, $city, $userId);

if (mysqli_stmt_execute($updateStmt)) {
    // Update user_profiles_vol table (for volunteers or both)
    if ($user_type === 'volunteer' || $user_type === 'both') {
        $updateVolQuery = "UPDATE user_profiles_vol SET emergency_name = ?, emergency_phone = ? WHERE userid = ?";
        $updateVolStmt = mysqli_prepare($con, $updateVolQuery);
        mysqli_stmt_bind_param($updateVolStmt, "ssi", $emergencyName, $emergencyPhone, $userId);
        mysqli_stmt_execute($updateVolStmt);
    }

    // Update user_profiles_org table (for organizers or both)
    if ($user_type === 'organizer' || $user_type === 'both') {
        $updateOrgQuery = "UPDATE user_profiles_org SET 
                            organization_name = ?, 
                            job_title = ?, 
                            industry = ?, 
                            location = ?, 
                            official_address = ?, 
                            official_contact_number = ? 
                            WHERE userid = ?";
        $updateOrgStmt = mysqli_prepare($con, $updateOrgQuery);
        mysqli_stmt_bind_param($updateOrgStmt, "sssssssi", $organizationName, $jobTitle, $industry, $location, $officialAddress, $officialContactNumber, $userId);
        mysqli_stmt_execute($updateOrgStmt);
    }

    header("Location: /miniProject/pages/profile/edit-profile.php?success=profileUpdated");
    exit();
} else {
    header("Location: /miniProject/pages/profile/edit-profile.php?error=updateFailed");
    exit();
}