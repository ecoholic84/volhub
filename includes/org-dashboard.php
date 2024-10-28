<?php
session_start();
include_once "dbh.inc.php";

if (isset($_SESSION['usersid'])) {
    $user_id = $_SESSION['usersid'];
} 
else {
    // Handle error or redirect to login
    header("Location: ../login/login.php?error=notLoggedIn");
    exit();
}

// Fetch the user's profile data
$sql = "SELECT * FROM user_profiles WHERE profile_usersId=?";
$stmt = mysqli_stmt_init($con);
if (!mysqli_stmt_prepare($stmt, $sql))
{
    echo "SQL error";
}
else
{
    mysqli_stmt_bind_param($stmt, "i", $users_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($result)) {
        $full_name = !empty($row['full_name']) ? $row['full_name'] : 'Organizer!';
        $username = !empty($row['username']) ? $row['username'] : 'Incomplete Profile Info';
        $identity = !empty($row['identity']) ? $row['identity'] : 'Incomplete Profile Info';
        $bio = !empty($row['bio']) ? $row['bio'] : 'Incomplete Profile Info';
        $degree_type = !empty($row['degree_type']) ? $row['degree_type'] : 'Incomplete Profile Info';
        $institution = !empty($row['institution']) ? $row['institution'] : 'Incomplete Profile Info';
        $field_of_study = !empty($row['field_of_study']) ? $row['field_of_study'] : 'Incomplete Profile Info';
        $graduation_month = !empty($row['graduation_month']) ? $row['graduation_month'] : 'Incomplete Profile Info';
        $graduation_year = !empty($row['graduation_year']) ? $row['graduation_year'] : 'Incomplete Profile Info';
        $phone = !empty($row['phone']) ? $row['phone'] : 'Incomplete Profile Info';
        $city = !empty($row['city']) ? $row['city'] : 'Incomplete Profile Info';
        $emergency_name = !empty($row['emergency_name']) ? $row['emergency_name'] : 'Incomplete Profile Info';
        $emergency_phone = !empty($row['emergency_phone']) ? $row['emergency_phone'] : 'Incomplete Profile Info';
        $links = !empty($row['links']) ? $row['links'] : 'Incomplete Profile Info';
    } else {
        // Handle the case where no user is found
        $full_name = 'Organizer!';
        $username = 'Incomplete Profile Info';
        $identity = 'Incomplete Profile Info';
        $bio = 'Incomplete Profile Info';
        $degree_type = 'Incomplete Profile Info';
        $institution = 'Incomplete Profile Info';
        $field_of_study = 'Incomplete Profile Info';
        $graduation_month = 'Incomplete Profile Info';
        $graduation_year = 'Incomplete Profile Info';
        $phone = 'Incomplete Profile Info';
        $city = 'Incomplete Profile Info';
        $emergency_name = 'Incomplete Profile Info';
        $emergency_phone = 'Incomplete Profile Info';
        $links = 'Incomplete Profile Info';
    }
    
        // Query to count total users
        $sql_total_users = "SELECT COUNT(*) AS total_users FROM users";
        $result_total_users = mysqli_query($con, $sql_total_users);

        if ($result_total_users) {
            $row_total_users = mysqli_fetch_assoc($result_total_users);
            $total_users = $row_total_users['total_users'];
        }
        mysqli_stmt_close($stmt);
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organizer Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    dark: '#121212',
                    'dark-light': '#1e1e1e',
                    'dark-lighter': '#2a2a2a',
                }
            }
        }
    }
    </script>
    <style>
    html,
    body {
        height: 100%;
        margin: 0;
        padding: 0;
    }
    </style>
    <!-- Include the Alpine library on your page -->
    <script src="https://unpkg.com/alpinejs" defer></script>
</head>

<body class="h-screen bg-gradient-to-br from-black via-black to-gray-800 text-neutral-100 flex flex-col">
    <?php include "header.php" ?>

    <!-- Navbar -->
    <header class="bg-transparent 00 py-4 px-8 flex justify-between items-center">
        <h1 class="text-xl font-bold"></h1>
        <div class="px-4 py-2 rounded-md">
            <?php include_once 'event-creation.php'; ?>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow flex flex-col justify-center items-center">
        <!-- Welcome Message -->
        <div class="text-center mb-10">
            <h2 class="text-3xl font-bold mb-2">Welcome, <?php echo $full_name; ?></h2>
            <p class="text-lg text-gray-300">"Connecting with the right people leads to amazing events."</p>
        </div>

       
    <section class="w-full max-w-4xl mb-12">
        <h3 class="text-2xl font-semibold mb-4">Your Listed Events</h3>
        <div class="bg-dark-light p-4 rounded-lg">
            <?php
            $sql = "SELECT * FROM events WHERE organizer_id = '".$_SESSION['usersid']."'";
            $result = mysqli_query($con, $sql);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
            ?>
                    <div class="border-b border-gray-700 py-4 relative"> 
                        <a href="event-tab.php?id=<?php echo $row['event_id']; ?>">
                            <h4 class="text-xl font-semibold"><?php echo $row['event_name']; ?></h4>
                            <p class="text-sm text-gray-400"><?php echo $row['event_datetime']; ?> |
                                <?php echo $row['event_location']; ?></p>
                        </a>
                        <!-- Admin Approval Status Label -->
                        <span class="absolute top-4 right-4 px-2 py-1 rounded-full text-xs font-bold 
                              <?php 
                              switch ($row['admin_approve']) {
                                  case '0': echo 'bg-yellow-300 text-gray-800'; break; // Awaiting Approval
                                  case '1': echo 'bg-green-400 text-white'; break; // Approved
                                  case '2': echo 'bg-red-400 text-white'; break; // Rejected
                              } 
                              ?>">
                            <?php 
                            switch ($row['admin_approve']) {
                                case '0': echo 'Awaiting Approval'; break;
                                case '1': echo 'Approved'; break;
                                case '2': echo 'Rejected'; break;
                            } 
                            ?>
                        </span>
                    </div>
            <?php
                }
            } else {
            ?>
                <div class="flex flex-col items-center justify-center h-24">
                    <p class="text-gray-400">You haven't listed any events yet.</p>
                    <button id="openModal" class="mt-4 bg-indigo-500 hover:bg-indigo-600 text-white font-bold py-2 px-4 rounded-lg">
                        Create Your First Event
                    </button>
                </div>
            <?php
            }
            ?>
        </div>
    </section>

    </main>
</body>

</html>