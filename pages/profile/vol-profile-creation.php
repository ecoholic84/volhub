<?php
session_start();
require_once '../../includes/dbh.inc.php';

// Check if user is logged in
if (!isset($_SESSION["usersid"])) {
    header("Location: ../login/login.php");
    exit();
}

// Check if user already has a volunteer profile
$userId = $_SESSION["usersid"];
$checkVolProfileQuery = "SELECT vol_profile_completed FROM user_profiles_vol WHERE userid = ?";
$stmt = mysqli_stmt_init($con);
if (!mysqli_stmt_prepare($stmt, $checkVolProfileQuery)) {
    echo "SQL error";
    exit();
}
mysqli_stmt_bind_param($stmt, "i", $userId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$volProfileExists = mysqli_fetch_assoc($result);

// If volunteer profile exists, redirect to dashboard
if ($volProfileExists) {
    header("Location: ../../includes/dashboard.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volunteer Safety Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        [x-cloak] { display: none }
        .collapse-content {
            transition: max-height 0.3s ease-out;
        }
    </style>
    <script src="https://unpkg.com/alpinejs" defer></script>
</head>

<body class="bg-gradient-to-br from-black via-black to-gray-800 min-h-screen">
    <?php include "../../includes/header.php" ?>

    <!-- Collapsible Info Section -->
    <div class="max-w-4xl mx-auto pt-8 px-4 sm:px-6 lg:px-8" x-data="{ open: false }">
        <div class="bg-gradient-to-r from-indigo-900 to-purple-900 rounded-lg shadow-xl overflow-hidden mb-8">
            <!-- Header/Toggle Section -->
            <button @click="open = !open" class="w-full p-6 flex items-center justify-between text-left">
                <div class="flex items-center space-x-4">
                    <div class="p-3 bg-indigo-600 rounded-lg">
                        <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-white">Volunteer Safety Profile</h2>
                        <p class="text-indigo-200">Click to learn about this important safety information</p>
                    </div>
                </div>
                <svg class="w-6 h-6 text-white transform transition-transform duration-200" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>

            <!-- Collapsible Content -->
            <div x-show="open" x-collapse x-cloak>
                <div class="p-6 pt-0">
                    <div class="bg-indigo-800 bg-opacity-50 rounded-lg p-4 mb-4">
                        <h3 class="text-white font-semibold mb-2">Why we need this information:</h3>
                        <ul class="text-indigo-200 space-y-2">
                            <li class="flex items-center">
                                <svg class="h-5 w-5 mr-2 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Quick emergency response during events
                            </li>
                            <li class="flex items-center">
                                <svg class="h-5 w-5 mr-2 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Immediate contact with your loved ones if needed
                            </li>
                            <li class="flex items-center">
                                <svg class="h-5 w-5 mr-2 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Ensures your safety during volunteering activities
                            </li>
                        </ul>
                    </div>
                    <div class="bg-indigo-700 bg-opacity-50 rounded-lg p-4">
                        <h3 class="text-white font-semibold mb-2">How we protect your information:</h3>
                        <ul class="text-indigo-200 space-y-2">
                            <li class="flex items-center">
                                <svg class="h-5 w-5 mr-2 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                Securely stored and encrypted
                            </li>
                            <li class="flex items-center">
                                <svg class="h-5 w-5 mr-2 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                Only accessed in emergency situations
                            </li>
                            <li class="flex items-center">
                                <svg class="h-5 w-5 mr-2 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                Shared only with authorized event organizers
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Keep the existing form -->
    <div class="flex items-center justify-center w-full max-w-full py-12">
        <form action="vol-profile-handler.php" method="POST" class="space-y-2 w-full max-w-3xl">
            <input type="hidden" name="form_type" value="volunteer">
            <input type="hidden" name="volunteer-profile" value="1">
            <div class="space-y-12">
                <div class="border-b border-gray-900/10 pb-12">
                    <h2 class="text-3xl font-semibold leading-7 text-white">Emergency Contact</h2>
                    <p class="mt-1 text-sm leading-6 text-gray-400">In case something goes wrong when you're
                        attending an event organized on VolHub, who'd you like us to reach out to first?</p>

                    <div class="mt-5">
                        <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                            <div class="sm:col-span-3">
                                <label for="emergency-name"
                                    class="block text-sm font-medium leading-6 text-white">Emergency Contact
                                    Name</label>
                                <div class="mt-2">
                                    <input type="text" name="emergency-name" id="emergency-name"
                                        class="block w-full rounded-md border-0 py-2 px-2 text-white bg-transparent shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                </div>
                            </div>

                            <div class="sm:col-span-3">
                                <label for="emergency-phone"
                                    class="block text-sm font-medium leading-6 text-white">Emergency Contact
                                    Number</label>
                                <div class="mt-2">
                                    <input type="tel" name="emergency-phone" id="emergency-phone"
                                        class="block w-full rounded-md border-0 py-2 px-2 text-white bg-transparent shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pb-10 flex items-center justify-end gap-x-6">
                    <a href="../../includes/dashboard.php" 
                       class="text-sm font-semibold leading-6 text-white hover:text-gray-300">
                        Skip for now
                    </a>
                    <button type="submit" name="volunteer_profile"
                        class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Save</button>
                </div>
            </div>
        </form>
    </div>
</body>

</html>
