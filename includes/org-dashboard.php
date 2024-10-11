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
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($result)) {
        $full_name = !empty($row['full_name']) ? $row['full_name'] : 'Incomplete Profile Info';
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
        $full_name = 'Incomplete Profile Info';
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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organiser Dashbaord</title>
    <style>
    [x-cloak] {
        display: none
    }
    </style>
    <!-- Include the Alpine library on your page -->
    <script src="https://unpkg.com/alpinejs" defer></script>
    <!-- Include the TailwindCSS library on your page -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>

<div class="flex items-start justify-center h-full bg-gray-50">
    <div class="flex items-center justify-center w-full max-w-full">
        <!-- Code Starts Here -->
        <!-- component -->
        <link rel="preconnect" href="https://rsms.me/">
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
        <style>
        :root {
            font-family: 'Inter', sans-serif;
        }

        @supports (font-variation-settings: normal) {
            :root {
                font-family: 'Inter var', sans-serif;
            }
        }
        </style>

        <div class="antialiased bg-black w-full min-h-screen text-slate-300 relative py-4">
        <?php include "header.php" ?>
            <div class="grid grid-cols-12 mx-auto gap-2 sm:gap-4 md:gap-6 lg:gap-10 xl:gap-14 max-w-7xl my-10 px-2">
                <div id="menu" class="bg-white/10 col-span-3 rounded-lg p-4">
                    <h1
                        class="font-bold text-lg lg:text-3xl bg-gradient-to-br from-red-500  via-red/50 to-transparent bg-clip-text text-transparent">
                        Organizer Dashboard<span class="text-orange-600">.</span></h1>
                    <p class="text-slate-400 text-sm mb-2">Welcome back,</p>
                    <a href="#"
                        class="flex flex-col space-y-2 md:space-y-0 md:flex-row mb-5 items-center md:space-x-2 hover:bg-white/10 group transition duration-150 ease-linear rounded-lg group w-full py-3 px-2">
                        <div>
                            <img class="rounded-full w-10 h-10 relative object-cover"
                                src="https://img.freepik.com/free-photo/no-problem-concept-bearded-man-makes-okay-gesture-has-everything-control-all-fine-gesture-wears-spectacles-jumper-poses-against-pink-wall-says-i-got-this-guarantees-something_273609-42817.jpg?w=1800&t=st=1669749937~exp=1669750537~hmac=4c5ab249387d44d91df18065e1e33956daab805bee4638c7fdbf83c73d62f125"
                                alt="">
                        </div>
                        <div>
                            <p class="font-medium group-hover:text-indigo-400 leading-4">
                                <?php echo htmlspecialchars($full_name); ?></p>
                            <span class="text-xs text-slate-400">@<?php echo htmlspecialchars($username); ?></span>
                        </div>
                    </a>
                    <hr class="my-2 border-slate-700">
                    <div id="menu" class="flex flex-col space-y-2 my-5">
                        <a href="#"
                            class="hover:bg-white/10 transition duration-150 ease-linear rounded-lg py-3 px-2 group">
                            <div class="flex flex-col space-y-2 md:flex-row md:space-y-0 space-x-2 items-center">
                                <div>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor"
                                        class="w-6 h-6 group-hover:text-indigo-400">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                                    </svg>

                                </div>
                                <div>
                                    <p
                                        class="font-bold text-base lg:text-lg text-slate-200 leading-4 group-hover:text-indigo-400">
                                        Dashboard</p>
                                    <p class="text-slate-400 text-sm hidden md:block">Data overview</p>
                                </div>

                            </div>
                        </a>
                        <a href="#"
                            class="hover:bg-white/10 transition duration-150 ease-linear rounded-lg py-3 px-2 group">
                            <div class="flex flex-col space-y-2 md:flex-row md:space-y-0 space-x-2 items-center">
                                <div>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor"
                                        class="w-6 h-6 group-hover:text-indigo-400">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p
                                        class="font-bold text-base lg:text-lg text-slate-200 leading-4 group-hover:text-indigo-400">
                                        Users</p>
                                    <p class="text-slate-400 text-sm hidden md:block">Manage users</p>
                                </div>

                            </div>
                        </a>
                        <a href="#"
                            class="hover:bg-white/10 transition duration-150 ease-linear rounded-lg py-3 px-2 group">
                            <div
                                class="relative flex flex-col space-y-2 md:flex-row md:space-y-0 space-x-2 items-center">
                                <div>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor"
                                        class="w-6 h-6 group-hover:text-indigo-400">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                    </svg>
                                </div>
                                <div>
                                    <p
                                        class="font-bold text-base lg:text-lg text-slate-200 leading-4 group-hover:text-indigo-400">
                                        Events</p>
                                    <p class="text-slate-400 text-sm hidden md:block">Manage Events</p>
                                </div>
                                <div
                                    class="absolute -top-3 -right-3 md:top-0 md:right-0 px-2 py-1.5 rounded-full bg-indigo-800 text-xs font-mono font-bold">
                                    23</div>
                            </div>
                        </a>
                        <a href="#"
                            class="hover:bg-white/10 transition duration-150 ease-linear rounded-lg py-3 px-2 group">
                            <div class="flex flex-col space-y-2 md:flex-row md:space-y-0 space-x-2 items-center">
                                <div>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor"
                                        class="w-6 h-6 group-hover:text-indigo-400">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>

                                </div>
                                <div>
                                    <p
                                        class="font-bold text-base lg:text-lg text-slate-200 leading-4 group-hover:text-indigo-400">
                                        Settings</p>
                                    <p class="text-slate-400 text-sm hidden md:block">Edit settings</p>
                                </div>

                            </div>
                        </a>
                    </div>
                    <p class="text-sm text-center text-gray-600">v1.0.0.0 | &copy; 2024 VolHub</p>
                </div>
                <div id="content" class="bg-white/10 col-span-9 rounded-lg p-6">
                    <div id="24h">
                        <h1 class="font-bold py-4 uppercase">Statistics</h1>
                        <div id="stats" class="grid gird-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
                            <div class="bg-black/60 to-white/5 p-6 rounded-lg">
                                <div class="flex flex-row space-x-4 items-center">
                                    <div id="stats-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-white">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-indigo-300 text-base font-medium leading-4">Total Registered
                                            Users</p>
                                        <p class="text-white font-bold text-2xl inline-flex items-center space-x-2">
                                            <span><?php echo htmlspecialchars($total_users); ?></span>
                                            <span>
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" />
                                                </svg>

                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-black/60 p-6 rounded-lg">
                                <div class="flex flex-row space-x-4 items-center">
                                    <div id="stats-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-white">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                        </svg>

                                    </div>
                                    <div>
                                        <p class="text-blue-300 text-base font-medium leading-4">Total Hosted Events</p>
                                        <p class="text-white font-bold text-2xl inline-flex items-center space-x-2">
                                            <span>79</span>
                                            <span>
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" />
                                                </svg>

                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- Code ends here -->
    </div>
    </div>
</body>

</html>