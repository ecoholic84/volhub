<?php
session_start();
require_once '../../includes/dbh.inc.php';

// Check if user is logged in
if (!isset($_SESSION["usersid"])) {
    header("Location: ../login/login.php");
    exit();
}

// Check if user already has an organizer profile
$userId = $_SESSION["usersid"];
$checkOrgProfileQuery = "SELECT org_profile_completed FROM user_profiles_org WHERE userid = ?";
$stmt = mysqli_stmt_init($con);
if (!mysqli_stmt_prepare($stmt, $checkOrgProfileQuery)) {
    echo "SQL error";
    exit();
}
mysqli_stmt_bind_param($stmt, "i", $userId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$orgProfileExists = mysqli_fetch_assoc($result);

// If organizer profile exists, redirect to dashboard
if ($orgProfileExists) {
    header("Location: ../../includes/org-dashboard.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organizer Profile Creation</title>
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
        <div class="bg-gradient-to-r from-blue-900 to-indigo-900 rounded-lg shadow-xl overflow-hidden">
            <!-- Header/Toggle Section -->
            <button @click="open = !open" class="w-full p-6 flex items-center justify-between text-left">
                <div class="flex items-center space-x-4">
                    <div class="p-3 bg-blue-600 rounded-lg">
                        <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-white">Organizer Profile Setup</h2>
                        <p class="text-blue-200">Click to learn why this profile matters</p>
                    </div>
                </div>
                <svg class="w-6 h-6 text-white transform transition-transform duration-200" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>

            <!-- Collapsible Content -->
            <div x-show="open" x-collapse x-cloak>
                <div class="p-6 pt-0">
                    <div class="bg-blue-800 bg-opacity-50 rounded-lg p-4 mb-4">
                        <h3 class="text-white font-semibold mb-2">Why this profile matters:</h3>
                        <ul class="text-blue-200 space-y-2">
                            <li class="flex items-center">
                                <svg class="h-5 w-5 mr-2 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Verify your identity and professional background
                            </li>
                            <li class="flex items-center">
                                <svg class="h-5 w-5 mr-2 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Show volunteers your experience and expertise
                            </li>
                            <li class="flex items-center">
                                <svg class="h-5 w-5 mr-2 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Build trust through transparent information
                            </li>
                        </ul>
                    </div>
                    <div class="bg-blue-700 bg-opacity-50 rounded-lg p-4">
                        <h3 class="text-white font-semibold mb-2">This profile helps volunteers know:</h3>
                        <ul class="text-blue-200 space-y-2">
                            <li class="flex items-center">
                                <svg class="h-5 w-5 mr-2 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                Your professional role and industry experience
                            </li>
                            <li class="flex items-center">
                                <svg class="h-5 w-5 mr-2 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                How to reach you for event coordination
                            </li>
                            <li class="flex items-center">
                                <svg class="h-5 w-5 mr-2 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 104 0 2 2 0 012-2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064"></path>
                                </svg>
                                Your credibility and commitment to organizing events
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex items-center justify-center w-full max-w-full py-12">
        <form action="org-profile-handler.php" method="POST" class="space-y-2 w-full max-w-3xl">
            <input type="hidden" name="form_type" value="organizer">
            <input type="hidden" name="organizer-profile" value="1">
            <div class="space-y-12">
                <div class="border-b border-gray-900/10 pb-12">
                    <h2 class="text-3xl font-semibold leading-7 text-white">Professional Details*</h2>
                    <p class="mt-1 text-sm leading-6 text-gray-400">Please provide your professional information.</p>

                    <div class="mt-5 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <!-- Organization Name -->
                        <div class="sm:col-span-4">
                            <label for="organization" class="block text-sm font-medium leading-6 text-white">Organization Name</label>
                            <div class="mt-2">
                                <input type="text" name="organization" id="organization" autocomplete="organization"
                                    class="block w-full rounded-md border-0 py-2 px-2 text-white bg-transparent shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    placeholder="Enter your organization name">
                            </div>
                        </div>

                        <!-- Job Title/Role -->
                        <div class="sm:col-span-4">
                            <label for="job-title" class="block text-sm font-medium leading-6 text-white">Job Title/Role</label>
                            <div class="mt-2">
                                <input type="text" name="job-title" id="job-title" autocomplete="job-title"
                                    class="block w-full rounded-md border-0 py-2 px-2 text-white bg-transparent shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    placeholder="Enter your job title or role">
                            </div>
                        </div>

                        <!-- Industry -->
                        <div class="sm:col-span-4">
                            <label for="industry" class="block text-sm font-medium leading-6 text-white">Industry</label>
                            <div class="mt-2">
                                <select id="industry" name="industry" autocomplete="industry"
                                    class="block w-full rounded-md border-0 py-2 px-2 text-white bg-black shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option value="" disabled selected hidden>Select Industry</option>
                                    <option value="Technology">Technology</option>
                                    <option value="Healthcare">Healthcare</option>
                                    <option value="Education">Education</option>
                                    <!-- Add more options as needed -->
                                </select>
                            </div>
                        </div>

                        <!-- Location -->
                        <div class="sm:col-span-4">
                            <label for="location" class="block text-sm font-medium leading-6 text-white">Location</label>
                            <div class="mt-2">
                                <input type="text" name="location" id="location" autocomplete="location"
                                    class="block w-full rounded-md border-0 py-2 px-2 text-white bg-transparent shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    placeholder="Enter your work location (city, state, country)">
                            </div>
                        </div>

                        <!-- Official Address -->
                        <div class="col-span-full">
                            <label for="official-address" class="block text-sm font-medium leading-6 text-white">Official Address</label>
                            <div class="mt-2">
                                <textarea id="official-address" name="official-address" rows="3"
                                    placeholder="Enter your official address"
                                    class="block w-full rounded-md border-0 py-2 px-2 text-white bg-transparent shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"></textarea>
                            </div>
                        </div>

                        <!-- Official Contact Number -->
                        <div class="sm:col-span-4">
                            <label for="official-contact" class="block text-sm font-medium leading-6 text-white">Official Contact Number</label>
                            <div class="mt-2">
                                <input type="tel" name="official-contact" id="official-contact" autocomplete="tel"
                                    class="block w-full rounded-md border-0 py-2 px-2 text-white bg-transparent shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    placeholder="Enter your official contact number">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-4 pb-10 flex items-center justify-end gap-x-6">
                <button type="button" class="text-sm font-semibold leading-6 text-white">Cancel</button>
                <button type="submit" name="organizer_profile"
                    class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Save</button>
            </div>
        </form>
    </div>
</body>

</html>