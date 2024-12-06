<?php
session_start();
require_once '../../includes/dbh.inc.php';

// Check if user is logged in
if (!isset($_SESSION["usersid"])) {
    header("Location: ../login/login.php");
    exit();
}

// Check if user already has a basic profile
$userId = $_SESSION["usersid"];
$checkProfileQuery = "SELECT profile_completed FROM users WHERE usersId = ?";
$stmt = mysqli_stmt_init($con);
if (!mysqli_stmt_prepare($stmt, $checkProfileQuery)) {
    echo "SQL error";
    exit();
}
mysqli_stmt_bind_param($stmt, "i", $userId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$userData = mysqli_fetch_assoc($result);

// Also check in user_profiles table
$checkUserProfileQuery = "SELECT profile_id FROM user_profiles WHERE profile_usersId = ?";
$profileStmt = mysqli_stmt_init($con);
if (!mysqli_stmt_prepare($profileStmt, $checkUserProfileQuery)) {
    echo "SQL error";
    exit();
}
mysqli_stmt_bind_param($profileStmt, "i", $userId);
mysqli_stmt_execute($profileStmt);
$profileResult = mysqli_stmt_get_result($profileStmt);
$profileExists = mysqli_fetch_assoc($profileResult);

// If user already has a profile, redirect to appropriate dashboard
if ($userData['profile_completed'] == 1 || $profileExists) {
    if ($_SESSION['user_type'] === 'organizer') {
        header("Location: ../../includes/org-dashboard.php");
    } else {
        header("Location: ../../includes/dashboard.php");
    }
    exit();
}

if (!isset($_SESSION['user_type'])) {
    header('Location: profile-index.php'); // Redirect back if no choice made
    exit;
}

// Store user choice
// $user_choice = $_SESSION['user_type'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION["usersid"];
    $userType = $_POST['user_type'];
}

if (isset($_GET["error"])) {
    $errorMessages = [
        "invalidUsername" => "Choose a proper username!",
        "usernameTaken" => "Sorry, username is taken. Try again!",
        "emailTaken" => "Sorry, this email is already registered. Use another email!",
    ];

    $errorKey = $_GET["error"];
    if (array_key_exists($errorKey, $errorMessages)) {
        echo "<div class='mt-2 pt-2 text-red-500 text-center text-sm'>{$errorMessages[$errorKey]}</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Basic Profile Creation</title>
    <style>
    [x-cloak] {
        display: none
    }
    .collapse-content {
        transition: max-height 0.3s ease-out;
    }
    body {
        background: linear-gradient(to bottom right, #111827, #000000, #1f2937);
        min-height: 100vh;
    }
    </style>
    <script src="https://unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body>
    <?php include "../../includes/header.php" ?>

    <!-- Collapsible Info Section -->
    <div class="max-w-4xl mx-auto pt-8 px-4 sm:px-6 lg:px-8" x-data="{ open: false }">
        <div class="bg-gradient-to-r from-green-900 to-teal-900 rounded-lg shadow-xl overflow-hidden mb-8">
            <!-- Header/Toggle Section -->
            <button @click="open = !open" class="w-full p-6 flex items-center justify-between text-left">
                <div class="flex items-center space-x-4">
                    <div class="p-3 bg-green-600 rounded-lg">
                        <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-white">Basic Profile Setup</h2>
                        <p class="text-green-200">Click to learn about your VolHub identity</p>
                    </div>
                </div>
                <svg class="w-6 h-6 text-white transform transition-transform duration-200" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>

            <!-- Collapsible Content -->
            <div x-show="open" x-collapse x-cloak>
                <div class="p-6 pt-0">
                    <div class="bg-green-800 bg-opacity-50 rounded-lg p-4 mb-4">
                        <h3 class="text-white font-semibold mb-2">Why complete your basic profile?</h3>
                        <ul class="text-green-200 space-y-2">
                            <li class="flex items-center">
                                <svg class="h-5 w-5 mr-2 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Create your unique VolHub identity
                            </li>
                            <li class="flex items-center">
                                <svg class="h-5 w-5 mr-2 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Share your educational background and expertise
                            </li>
                            <li class="flex items-center">
                                <svg class="h-5 w-5 mr-2 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Connect with like-minded volunteers and organizers
                            </li>
                        </ul>
                    </div>
                    <div class="bg-green-700 bg-opacity-50 rounded-lg p-4">
                        <h3 class="text-white font-semibold mb-2">What this profile enables:</h3>
                        <ul class="text-green-200 space-y-2">
                            <li class="flex items-center">
                                <svg class="h-5 w-5 mr-2 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                Access to volunteer opportunities and event creation
                            </li>
                            <li class="flex items-center">
                                <svg class="h-5 w-5 mr-2 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                Build your volunteering network
                            </li>
                            <li class="flex items-center">
                                <svg class="h-5 w-5 mr-2 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                                Establish your credibility in the community
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex items-start justify-center h-full">
    <div class="flex items-center justify-center w-full max-w-full">

            <form id="profile-form" action="profile-handler.php" method="POST" class="space-y-2">
                <div class="space-y-12">
                    <div class="border-b border-gray-900/10 pb-12 pt-12">
                        <h2 class="text-3xl font-semibold leading-7 text-white">Basic Details*</h2>
                        <p class="mt-1 text-sm leading-6 text-gray-400">This information will be displayed publicly so
                            be careful what you share.</p>

                        <div class="mt-5 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                            <div class="sm:col-span-4">
                                <label for="full-name" class="block text-sm font-medium leading-6 text-white">Full
                                    Name*</label>
                                <div class="mt-2">
                                    <input type="text" name="full-name" id="full-name" placeholder=""
                                        autocomplete="name"
                                        class="block w-full rounded-md border-0 py-2 px-2 text-white bg-transparent shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                </div>
                            </div>

                            <div class="sm:col-span-4">
                                <label for="username"
                                    class="block text-sm font-medium leading-6 text-white">Username*</label>
                                <div class="mt-2">
                                    <div
                                        class="flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600 sm:max-w-md">
                                        <span
                                            class="flex select-none items-center pl-3 text-gray-400 sm:text-sm">volhub.com/</span>
                                        <input type="text" name="username" id="username" placeholder=""
                                            autocomplete="username"
                                            class="block flex-1 border-0 bg-transparent py-2 px-2 pl-1 text-white placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6"
                                            placeholder="janesmith">
                                    </div>
                                </div>
                            </div>

                            <div class="sm:col-span-3">
                                <label for="identity" class="block text-sm font-medium leading-6 text-white">I Identify
                                    As*</label>
                                <div class="mt-2">
                                    <select id="identity" name="identity"
                                        class="block w-full rounded-md border-0 py-2 px-2 text-white bg-black shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6">
                                        <option value="" disabled selected hidden>Gender</option>
                                        <option>Male</option>
                                        <option>Female</option>
                                        <option>Prefer not to say</option>
                                    </select>
                                </div>
                            </div>

                            <div class="sm:col-span-4">
                                <label for="phone" class="block text-sm font-medium leading-6 text-white">Phone
                                    number*</label>
                                <div class="mt-2">
                                    <input type="tel" name="phone" id="phone" autocomplete="tel"
                                        class="block w-full rounded-md border-0 py-2 px-2 text-white bg-transparent shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                </div>
                            </div>

                            <div class="sm:col-span-3">
                                <label for="city" class="block text-sm font-medium leading-6 text-white">City*</label>
                                <div class="mt-2">
                                    <input type="text" name="city" id="city" autocomplete="address-level2"
                                        class="block w-full rounded-md border-0 py-2 px-2 text-white bg-transparent shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                </div>
                            </div>


                            <div class="col-span-full">
                                <label for="bio" class="block text-sm font-medium leading-6 text-white">Bio*</label>
                                <div class="mt-2">
                                    <textarea id="bio" name="bio" rows="3"
                                        placeholder="Write a few sentences about yourself."
                                        class="block w-full rounded-md border-0 py-2 px-2 text-white bg-transparent shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="border-b border-gray-900/10 pt-6 pb-12">
                        <h2 class="text-3xl font-semibold leading-7 text-white">Education</h2>
                        <p class="mt-1 text-sm leading-6 text-gray-400">The information you provide here helps us in
                            performing analytics.</p>

                        <div class="mt-5 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                            <div class="sm:col-span-3">
                                <label for="degree-type" class="block text-sm font-medium leading-6 text-white">Degree
                                    Type</label>
                                <div class="mt-2">
                                    <select id="degree-type" name="degree-type"
                                        class="block w-full rounded-md border-0 py-2 px-2 text-white bg-black shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6">
                                        <option value="" disabled selected hidden>Degree Type</option>
                                        <option>Associate</option>
                                        <option>Bachelors</option>
                                        <option>Masters</option>
                                        <option>PhD</option>
                                        <option>High School</option>
                                    </select>
                                </div>
                            </div>

                            <div class="sm:col-span-3">
                                <label for="institution"
                                    class="block text-sm font-medium leading-6 text-white">Educational
                                    Institution</label>
                                <div class="mt-2">
                                    <input type="text" name="institution" id="institution"
                                        class="block w-full rounded-md border-0 py-2 px-2 text-white bg-transparent shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                </div>
                            </div>

                            <div class="sm:col-span-3">
                                <label for="field-of-study" class="block text-sm font-medium leading-6 text-white">Field
                                    of Study</label>
                                <div class="mt-2">
                                    <input type="text" name="field-of-study" id="field-of-study"
                                        class="block w-full rounded-md border-0 py-2 px-2 text-white bg-transparent shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                </div>
                            </div>

                            <div class="sm:col-span-2">
                                <label for="graduation-month"
                                    class="block text-sm font-medium leading-6 text-white">Month of Graduation</label>
                                <div class="mt-2">
                                    <select id="graduation-month" name="graduation-month"
                                        class="block w-full rounded-md border-0 py-2 px-2 text-white bg-black shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6">
                                        <option value="" disabled selected hidden>Which month?</option>
                                        <option>January</option>
                                        <option>February</option>
                                        <option>March</option>
                                        <option>April</option>
                                        <option>May</option>
                                        <option>June</option>
                                        <option>July</option>
                                        <option>August</option>
                                        <option>September</option>
                                        <option>October</option>
                                        <option>November</option>
                                        <option>December</option>
                                    </select>
                                </div>
                            </div>

                            <div class="sm:col-span-2">
                                <label for="graduation-year" class="block text-sm font-medium leading-6 text-white">Year
                                    of Graduation</label>
                                <div class="mt-2">
                                    <select id="graduation-year" name="graduation-year"
                                        class="block w-full rounded-md border-0 py-2 px-2 text-white bg-black shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6">
                                        <option value="" disabled selected hidden>Which year?</option>
                                        <?php
                    for ($year = 2030; $year >= 1950; $year--) {
                      echo "<option>$year</option>";
                    }
                    ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="border-b border-gray-900/10 pb-12">
                        <h2 class="text-3xl font-semibold leading-7 text-white">Links</h2>
                        <p class="mt-1 text-sm leading-6 text-gray-400">Add links to your website, blog, GitHub,
                            LinkedIn, Stack Overflow, Dribbble, Kaggle or anywhere where your work stands out.</p>

                        <div class="mt-5" x-data="{ links: [] }">
                            <button type="button" @click="links.push('')"
                                class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                Add new profile
                            </button>
                            <template x-for="(link, index) in links" :key="index">
                                <div class="mt-2">
                                    <input type="url" x-model="links[index]" :name="'link-' + index"
                                        class="block w-full rounded-md border-0 py-2 px-2 text-white bg-transparent shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pb-10 flex py-items-center justify-end gap-x-6">
                    <button type="button" class="text-sm font-semibold leading-6 text-white">Cancel</button>
                    <a href="skip-profile.php" 
                       class="rounded-md bg-gray-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-600">
                        Skip for now
                    </a>
                    <button type="submit" name="basic_profile"
                        class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Next</button>
                </div>

                <!-- <div class="mt-4 pb-10 flex py-items-center justify-end gap-x-6">
                    <button type="button" class="text-sm font-semibold leading-6 text-white">Cancel</button>
                    <button type="submit" id="submit-button" name="basic_profile" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        Next
                    </button>
                </div> -->

            </form>

        </div>
    </div>

</body>

</html>