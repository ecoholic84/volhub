<?php

include_once "../../includes/dbh.inc.php";
include_once '../../includes/functions.inc.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = htmlspecialchars($_POST['email']);
    $pwd = htmlspecialchars($_POST['pwd']);
    $pwdRepeat = htmlspecialchars($_POST['pwdrepeat']);
    $user_type = isset($_POST['user_type']) ? htmlspecialchars($_POST['user_type']) : 'volunteer';

    /*.......................ERROR HANDLERS.......................*/

    if (emptyInputSignup($email, $pwd, $pwdRepeat)) {
        header("Location: signup.php?error=emptyinput"); // Consistent lowercase error names
        exit();
    }

    if (invalidEmail($email)) {
        header("Location: signup.php?error=invalidemail");
        exit();
    }

    if (emailExists($email)) {  // No $con parameter needed
        header("Location: signup.php?error=emailtaken"); 
        exit();
    }

    if (strlen($pwd) < 8) {
        header("Location: signup.php?error=passwordtooshort");
        exit();
    }

    if (pwdMatch($pwd, $pwdRepeat)) {
        header("Location: signup.php?error=passwordsdontmatch");
        exit();
    }


    // Get the database connection
    $conn = getDatabaseConnection();  // Get the connection here


    if (createUser($email, $pwd, 'user', $user_type)) {
        // Redirect on success (handle profile creation separately after signup)
        header("Location: ../profile/profile-index.php?success=1"); // Use a success parameter
        exit();
    } else {
        // Handle user creation failure (log the error, display a message, etc.)
        error_log("User creation failed for email: " . $email); // Log the error
        header("Location: signup.php?error=usercreationfailed"); // Or a more specific error message
        exit();
    }

    // Close the connection
    mysqli_close($conn);  // Close after all database operations


} else {
    header("Location: signup.php");
    exit();
}
?>