<?php
include_once "../../includes/dbh.inc.php";
session_start();
$user_id = $_SESSION["usersid"];

if (!isset($_SESSION["usersid"])) {
    header("Location: ../login/login.php");
    exit();
}

// Fetch the user's profile data
$sql = "SELECT * FROM user_profiles WHERE profile_usersId=?";
$stmt = mysqli_stmt_init($con);
if (!mysqli_stmt_prepare($stmt, $sql)) {
    echo "SQL error";
} else {
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($result)) {
        $full_name = $row['full_name'];
        $username = $row['username'];
        $identity = $row['identity'];
        $bio = $row['bio'];
        $degree_type = $row['degree_type'];
        $institution = $row['institution'];
        $field_of_study = $row['field_of_study'];
        $graduation_month = $row['graduation_month'];
        $graduation_year = $row['graduation_year'];
        $phone = $row['phone'];
        $city = $row['city'];
        $emergency_name = $row['emergency_name'];
        $emergency_phone = $row['emergency_phone'];
        $links = $row['links'];
    }
}
mysqli_stmt_close($stmt);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <style>
    [x-cloak] {
        display: none
    }
    </style>
    <script src="https://unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <?php include "../../includes/header.php"; ?>
    <div class="flex items-start justify-center h-full bg-gradient-to-br from-gray-900 via-black to-gray-800">
        <div class="flex items-center justify-center w-full max-w-full">

            <form action="edit-profile-handler.php" method="POST" class="space-y-2">
                <div class="space-y-12">
                    <div class="border-b border-gray-900/10 pb-12 pt-12">
                        <h2 class="text-3xl font-semibold leading-7 text-white">Edit Profile</h2>

                        <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                            <div class="sm:col-span-4">
                                <label for="full-name" class="block text-sm font-medium leading-6 text-white">Full
                                    Name</label>
                                <div class="mt-2">
                                    <input type="text" name="full-name" id="full-name" placeholder=""
                                        value="<?php echo htmlspecialchars($full_name); ?>" autocomplete="name"
                                        class="block w-full rounded-md border-0 py-2 px-2 text-white bg-transparent shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                </div>
                            </div>

                            <div class="sm:col-span-4">
                                <label for="username"
                                    class="block text-sm font-medium leading-6 text-white">Username</label>
                                <div class="mt-2">
                                    <div
                                        class="flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600 sm:max-w-md">
                                        <span
                                            class="flex select-none items-center pl-3 text-gray-500 sm:text-sm">volhub.com/</span>
                                        <input type="text" name="username" id="username" placeholder=""
                                            value="<?php echo htmlspecialchars($username); ?>" autocomplete="username"
                                            class="block flex-1 border-0 bg-transparent py-2 px-2 pl-1 text-white placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6"
                                            placeholder="janesmith">
                                    </div>
                                </div>
                            </div>

                            <div class="sm:col-span-3">
                                <label for="identity" class="block text-sm font-medium leading-6 text-white">I Identify
                                    As</label>
                                <div class="mt-2">
                                    <select id="identity" name="identity"
                                        class="block w-full rounded-md border-0 py-2 px-2 text-white bg-black shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6">
                                        <option value="" disabled selected hidden>
                                            <?php echo htmlspecialchars($identity); ?></option>
                                        <option>Male</option>
                                        <option>Female</option>
                                        <option>Prefer not to say</option>
                                    </select>
                                </div>
                            </div>


                            <div class="col-span-full">
                                <label for="bio" class="block text-sm font-medium leading-6 text-white">Bio</label>
                                <div class="mt-2">
                                    <textarea id="bio" name="bio" rows="3"
                                        class="block w-full rounded-md border-0 py-2 px-2 text-white bg-transparent shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"><?php echo htmlspecialchars($bio); ?></textarea>
                                </div>
                                <p class="mt-3 text-sm leading-6 text-gray-400">Write a few sentences about yourself.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="border-b border-gray-900/10 pb-12">
                        <h2 class="text-3xl font-semibold leading-7 text-white">Education</h2>
                        <p class="mt-1 text-sm leading-6 text-gray-400">The information you provide here helps us in
                            performing analytics.</p>

                        <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                            <div class="sm:col-span-3">
                                <label for="degree-type" class="block text-sm font-medium leading-6 text-white">Degree
                                    Type</label>
                                <div class="mt-2">
                                    <select id="degree-type" name="degree-type"
                                        class="block w-full rounded-md border-0 py-2 px-2 text-white bg-black shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6">
                                        <option value="" disabled selected hidden>
                                            <?php echo htmlspecialchars($degree_type); ?></option>
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
                                        value="<?php echo htmlspecialchars($institution); ?>"
                                        class="block w-full rounded-md border-0 py-2 px-2 text-white bg-transparent shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                </div>
                            </div>

                            <div class="sm:col-span-3">
                                <label for="field-of-study" class="block text-sm font-medium leading-6 text-white">Field
                                    of Study</label>
                                <div class="mt-2">
                                    <input type="text" name="field-of-study" id="field-of-study"
                                        value="<?php echo htmlspecialchars($field_of_study); ?>"
                                        class="block w-full rounded-md border-0 py-2 px-2 text-white bg-transparent shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                </div>
                            </div>

                            <div class="sm:col-span-2">
                                <label for="graduation-month"
                                    class="block text-sm font-medium leading-6 text-white">Month of Graduation</label>
                                <div class="mt-2">
                                    <select id="graduation-month" name="graduation-month"
                                        class="block w-full rounded-md border-0 py-2 px-2 text-white bg-black shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6">
                                        <option value="" disabled selected hidden>
                                            <?php echo htmlspecialchars($graduation_month); ?></option>
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
                                        <option value="" disabled selected hidden>
                                            <?php echo htmlspecialchars($graduation_year); ?></option>
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

                        <div class="mt-10" x-data="{ links: [] }">
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

                    <div class="border-b border-gray-900/10 pb-12">
                        <h2 class="text-3xl font-semibold leading-7 text-white">Contact</h2>
                        <p class="mt-1 text-sm leading-6 text-gray-400">How can we reach you?</p>

                        <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                            <div class="sm:col-span-4">
                                <label for="phone" class="block text-sm font-medium leading-6 text-white">Phone
                                    number</label>
                                <div class="mt-2">
                                    <input type="tel" name="phone" id="phone"
                                        value="<?php echo htmlspecialchars($phone); ?>" autocomplete="tel"
                                        class="block w-full rounded-md border-0 py-2 px-2 text-white bg-transparent shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                </div>
                            </div>

                            <div class="sm:col-span-3">
                                <label for="city" class="block text-sm font-medium leading-6 text-white">City</label>
                                <div class="mt-2">
                                    <input type="text" name="city" id="city"
                                        value="<?php echo htmlspecialchars($city); ?>" autocomplete="address-level2"
                                        class="block w-full rounded-md border-0 py-2 px-2 text-white bg-transparent shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                </div>
                            </div>
                        </div>

                        <div class="mt-10">
                            <h3 class="text-lg font-medium leading-6 text-white">Emergency Contact</h3>
                            <p class="mt-1 text-sm leading-6 text-gray-400">In case something goes wrong when you're
                                attending an event organized on VolHub, who'd you like us to reach out to first?</p>

                            <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                <div class="sm:col-span-3">
                                    <label for="emergency-name"
                                        class="block text-sm font-medium leading-6 text-white">Emergency Contact
                                        Name</label>
                                    <div class="mt-2">
                                        <input type="text" name="emergency-name" id="emergency-name"
                                            value="<?php echo htmlspecialchars($emergency_name); ?>"
                                            class="block w-full rounded-md border-0 py-2 px-2 text-white bg-transparent shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    </div>
                                </div>

                                <div class="sm:col-span-3">
                                    <label for="emergency-phone"
                                        class="block text-sm font-medium leading-6 text-white">Emergency Contact
                                        Number</label>
                                    <div class="mt-2">
                                        <input type="tel" name="emergency-phone" id="emergency-phone"
                                            value="<?php echo htmlspecialchars($emergency_phone); ?>"
                                            class="block w-full rounded-md border-0 py-2 px-2 text-white bg-transparent shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pb-10 flex  py-items-center justify-end gap-x-6">
                    <button type="button" class="text-sm font-semibold leading-6 text-white">Cancel</button>
                    <button type="submit"
                        class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Save</button>
                </div>
                <!-- Error Handlers -->
                <?php
                if (isset($_GET["error"])) {
                    $errorMessages = [
                        "invalidUsername" => "Choose a proper username!",
                        "usernameTaken" => "Sorry, username is taken. Try again!",
                        "none" => "You have signed up!",
                    ];

                    $errorKey = $_GET["error"];
                    if (array_key_exists($errorKey, $errorMessages)) {
                        echo "<div class='mt-2 pt-2 text-red-500 text-center text-sm'>{$errorMessages[$errorKey]}</div>";
                    }
                }
                ?>

            </form>
        </div>
    </div>
</body>

</html>