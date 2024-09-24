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
    <title>Volunteer Profile Creation</title>
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
                    <button type="button" class="text-sm font-semibold leading-6 text-white">Cancel</button>
                    <button type="submit" name="volunteer_profile"
                        class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Save</button>
                </div>
            </div>
        </form>
    </div>
</body>

</html>
