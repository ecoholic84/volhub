<?php
session_start(); // Start the session at the very beginning

if (!isset($_SESSION["usersid"])) {
    header("Location: ../login/login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organizer Profile Creation</title>
    <style>
    [x-cloak] {
        display: none
    }
    </style>
    <script src="https://unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-br from-gray-900 via-black to-gray-800 min-h-screen">
    <?php include "../../includes/header.php" ?>
    <div class="flex items-center justify-center w-full max-w-full py-12">
        <form action="profile-handler.php" method="POST" class="space-y-2 w-full max-w-3xl">
            <input type="hidden" name="form_type" value="organizer">
            <input type="hidden" name="organizers-profile" value="1">
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
                <button type="submit"
                    class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Save</button>
            </div>
        </form>
    </div>
</body>

</html>