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
        $links = $row['links'];
        $profile_picture = $row['profile_picture'];
    } else {
        // Set placeholders if no data is found
        $full_name = 'Your Name';
        $username = 'Your Username';
        $identity = 'Select Identity';
        $bio = 'Tell us about yourself...';
        $degree_type = 'Select Degree';
        $institution = 'Your Institution';
        $field_of_study = 'Your Field of Study';
        $graduation_month = 'Select Month';
        $graduation_year = 'Select Year';
        $phone = 'Your Phone Number';
        $city = 'Your City';
        $links = '';
        $profile_picture = '/volhub/images/default_profile.webp'; // Path to your default profile picture
    }
}

// Fetch additional data based on user type
$userTypeQuery = "SELECT user_type FROM users WHERE usersId = ?";
$userTypeStmt = mysqli_stmt_init($con);
if (mysqli_stmt_prepare($userTypeStmt, $userTypeQuery)) {
    mysqli_stmt_bind_param($userTypeStmt, "i", $user_id);
    mysqli_stmt_execute($userTypeStmt);
    $userTypeResult = mysqli_stmt_get_result($userTypeStmt);
    if ($userTypeRow = mysqli_fetch_assoc($userTypeResult)) {
        $user_type = $userTypeRow['user_type'];

        if ($user_type === 'volunteer' || $user_type === 'both') {
            // Fetch volunteer-specific data
            $volQuery = "SELECT * FROM user_profiles_vol WHERE userid = ?";
            $volStmt = mysqli_stmt_init($con);
            if (mysqli_stmt_prepare($volStmt, $volQuery)) {
                mysqli_stmt_bind_param($volStmt, "i", $user_id);
                mysqli_stmt_execute($volStmt);
                $volResult = mysqli_stmt_get_result($volStmt);
                if ($volRow = mysqli_fetch_assoc($volResult)) {
                    $emergency_name = $volRow['emergency_name'];
                    $emergency_phone = $volRow['emergency_phone'];
                } else {
                    $emergency_name = 'Emergency Contact Name';
                    $emergency_phone = 'Emergency Contact Number';
                }
            }
        }

        if ($user_type === 'organizer' || $user_type === 'both') {
            // Fetch organizer-specific data
            $orgQuery = "SELECT * FROM user_profiles_org WHERE userid = ?";
            $orgStmt = mysqli_stmt_init($con);
            if (mysqli_stmt_prepare($orgStmt, $orgQuery)) {
                mysqli_stmt_bind_param($orgStmt, "i", $user_id);
                mysqli_stmt_execute($orgStmt);
                $orgResult = mysqli_stmt_get_result($orgStmt);
                if ($orgRow = mysqli_fetch_assoc($orgResult)) {
                    $organization_name = $orgRow['organization_name'];
                    $job_title = $orgRow['job_title'];
                    $industry = $orgRow['industry'];
                    $location = $orgRow['location'];
                    $official_address = $orgRow['official_address'];
                    $official_contact_number = $orgRow['official_contact_number'];
                } else {
                    $organization_name = 'Organization Name';
                    $job_title = 'Job Title';
                    $industry = 'Industry';
                    $location = 'Location';
                    $official_address = 'Official Address';
                    $official_contact_number = 'Official Contact Number';
                }
            }
        }
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
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .animate-fadeIn {
            animation: fadeIn 0.5s ease-out;
        }
    </style>
</head>

<body class="bg-black text-white min-h-screen flex flex-col">
    <?php include "../../includes/header.php"; ?>

    <main class="flex-grow container mx-auto px-4 py-12 flex justify-center">
        <div class="max-w-4xl w-full bg-black rounded-lg shadow-xl overflow-hidden animate-fadeIn">
            <div class="p-8">
                <h2 class="text-3xl font-bold text-center text-blue-400 mb-8">Edit Profile</h2>

                <form action="/volhub/pages/profile/profile-edit-handler.php" method="POST" enctype="multipart/form-data">
                    <!-- Profile Picture -->
                    <div class="mb-6 text-center">
                        <label for="profile_picture" class="block text-gray-300 font-semibold mb-2">Profile Picture</label>
                        <input type="file" name="profile_picture" id="profile_picture" class="hidden">
                        <label for="profile_picture" class="cursor-pointer">
                            <img src="uploads/<?php echo htmlspecialchars($profile_picture); ?>" alt="Profile Picture"
                                class="rounded-full w-32 h-32 mx-auto object-cover border-2 border-gray-700">
                        </label>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Personal Information -->
                        <div>
                            <h3 class="text-xl font-semibold mb-4 text-blue-400">Personal Information</h3>
                            <div class="space-y-4">
                                <div>
                                    <label for="full-name" class="block text-gray-300">Full Name</label>
                                    <input type="text" name="full-name" id="full-name" placeholder="Your Name"
                                        value="<?php echo htmlspecialchars($full_name); ?>" autocomplete="name"
                                        class="w-full px-4 py-2 rounded-lg bg-gray-900 border border-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                </div>
                                <div>
                                    <label for="username" class="block text-gray-300">Username</label>
                                    <input type="text" name="username" id="username" placeholder="Your Username"
                                        value="<?php echo htmlspecialchars($username); ?>" autocomplete="username"
                                        class="w-full px-4 py-2 rounded-lg bg-gray-900 border border-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                </div>
                                <div>
                                    <label for="identity" class="block text-gray-300">I Identify As</label>
                                    <select id="identity" name="identity"
                                        class="w-full px-4 py-2 rounded-lg bg-gray-900 border border-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                        <option value="" disabled
                                            <?php echo ($identity === '' || $identity === 'Select Identity') ? 'selected' : ''; ?>>
                                            Select Identity</option>
                                        <option value="Male" <?php echo ($identity === 'Male') ? 'selected' : ''; ?>>Male
                                        </option>
                                        <option value="Female" <?php echo ($identity === 'Female') ? 'selected' : ''; ?>>
                                            Female</option>
                                        <option value="Prefer not to say"
                                            <?php echo ($identity === 'Prefer not to say') ? 'selected' : ''; ?>>Prefer not
                                            to say</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="bio" class="block text-gray-300">Bio</label>
                                    <textarea name="bio" id="bio" rows="3" placeholder="Tell us about yourself..."
                                        class="w-full px-4 py-2 rounded-lg bg-gray-900 border border-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400"><?php echo htmlspecialchars($bio); ?></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Education and Links -->
                        <div>
                            <h3 class="text-xl font-semibold mb-4 text-blue-400">Education & Links</h3>
                            <div class="space-y-4">
                                <div>
                                    <label for="degree-type" class="block text-gray-300">Degree Type</label>
                                    <select id="degree-type" name="degree-type"
                                        class="w-full px-4 py-2 rounded-lg bg-gray-900 border border-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                        <option value="" disabled
                                            <?php echo ($degree_type === '' || $degree_type === 'Select Degree') ? 'selected' : ''; ?>>
                                            Select Degree</option>
                                        <option value="Associate"
                                            <?php echo ($degree_type === 'Associate') ? 'selected' : ''; ?>>Associate
                                        </option>
                                        <option value="Bachelors"
                                            <?php echo ($degree_type === 'Bachelors') ? 'selected' : ''; ?>>Bachelors
                                        </option>
                                        <option value="Masters"
                                            <?php echo ($degree_type === 'Masters') ? 'selected' : ''; ?>>Masters
                                        </option>
                                        <option value="PhD" <?php echo ($degree_type === 'PhD') ? 'selected' : ''; ?>>PhD
                                        </option>
                                        <option value="High School"
                                            <?php echo ($degree_type === 'High School') ? 'selected' : ''; ?>>High School
                                        </option>
                                    </select>
                                </div>
                                <div>
                                    <label for="institution" class="block text-gray-300">Educational Institution</label>
                                    <input type="text" name="institution" id="institution" placeholder="Your Institution"
                                        value="<?php echo htmlspecialchars($institution); ?>"
                                        class="w-full px-4 py-2 rounded-lg bg-gray-900 border border-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                </div>
                                <div>
                                    <label for="field-of-study" class="block text-gray-300">Field of Study</label>
                                    <input type="text" name="field-of-study" id="field-of-study"
                                        placeholder="Your Field of Study"
                                        value="<?php echo htmlspecialchars($field_of_study); ?>"
                                        class="w-full px-4 py-2 rounded-lg bg-gray-900 border border-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="graduation-month" class="block text-gray-300">Month of
                                            Graduation</label>
                                        <select id="graduation-month" name="graduation-month"
                                            class="w-full px-4 py-2 rounded-lg bg-gray-900 border border-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                            <option value="" disabled
                                                <?php echo ($graduation_month === '' || $graduation_month === 'Select Month') ? 'selected' : ''; ?>>
                                                Select Month
                                            </option>
                                            <?php
                                            $months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
                                            foreach ($months as $month) {
                                                $selected = ($graduation_month === $month) ? 'selected' : '';
                                                echo "<option value='$month' $selected>$month</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="graduation-year" class="block text-gray-300">Year of
                                            Graduation</label>
                                        <select id="graduation-year" name="graduation-year"
                                            class="w-full px-4 py-2 rounded-lg bg-gray-900 border border-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                            <option value="" disabled
                                                <?php echo ($graduation_year === '' || $graduation_year === 'Select Year') ? 'selected' : ''; ?>>
                                                Select Year
                                            </option>
                                            <?php
                                            for ($year = 2030; $year >= 1950; $year--) {
                                                $selected = ($graduation_year == $year) ? 'selected' : '';
                                                echo "<option value='$year' $selected>$year</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div>
                                    <label for="links" class="block text-gray-300">Links (comma-separated)</label>
                                    <textarea name="links" id="links" rows="3"
                                        placeholder="Your Website, Blog, Github, etc."
                                        class="w-full px-4 py-2 rounded-lg bg-gray-900 border border-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400"><?php echo htmlspecialchars($links); ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        <!-- Contact Information -->
                        <div>
                            <h3 class="text-xl font-semibold mb-4 text-blue-400">Contact Information</h3>
                            <div class="space-y-4">
                                <div>
                                    <label for="phone" class="block text-gray-300">Phone Number</label>
                                    <input type="tel" name="phone" id="phone" placeholder="Your Phone Number"
                                        value="<?php echo htmlspecialchars($phone); ?>" autocomplete="tel"
                                        class="w-full px-4 py-2 rounded-lg bg-gray-900 border border-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                </div>
                                <div>
                                    <label for="city" class="block text-gray-300">City</label>
                                    <input type="text" name="city" id="city" placeholder="Your City"
                                        value="<?php echo htmlspecialchars($city); ?>" autocomplete="address-level2"
                                        class="w-full px-4 py-2 rounded-lg bg-gray-900 border border-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                </div>
                            </div>
                        </div>

                        <!-- Emergency Contact (for Volunteers) -->
                        <?php if ($user_type === 'volunteer' || $user_type === 'both'): ?>
                        <div>
                            <h3 class="text-xl font-semibold mb-4 text-blue-400">Emergency Contact</h3>
                            <div class="space-y-4">
                                <div>
                                    <label for="emergency-name" class="block text-gray-300">Emergency Contact
                                        Name</label>
                                    <input type="text" name="emergency-name" id="emergency-name"
                                        placeholder="Emergency Contact Name"
                                        value="<?php echo htmlspecialchars($emergency_name); ?>"
                                        class="w-full px-4 py-2 rounded-lg bg-gray-900 border border-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                </div>
                                <div>
                                    <label for="emergency-phone" class="block text-gray-300">Emergency Contact
                                        Number</label>
                                    <input type="tel" name="emergency-phone" id="emergency-phone"
                                        placeholder="Emergency Contact Number"
                                        value="<?php echo htmlspecialchars($emergency_phone); ?>"
                                        class="w-full px-4 py-2 rounded-lg bg-gray-900 border border-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Organizer Details (for Organizers) -->
                        <?php if ($user_type === 'organizer' || $user_type === 'both'): ?>
                        <div>
                            <h3 class="text-xl font-semibold mb-4 text-blue-400">Organizer Details</h3>
                            <div class="space-y-4">
                                <div>
                                    <label for="organization_name" class="block text-gray-300">Organization Name</label>
                                    <input type="text" name="organization_name" id="organization_name"
                                        placeholder="Organization Name"
                                        value="<?php echo htmlspecialchars($organization_name); ?>"
                                        class="w-full px-4 py-2 rounded-lg bg-gray-900 border border-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                </div>
                                <div>
                                    <label for="job_title" class="block text-gray-300">Job Title</label>
                                    <input type="text" name="job_title" id="job_title" placeholder="Job Title"
                                        value="<?php echo htmlspecialchars($job_title); ?>"
                                        class="w-full px-4 py-2 rounded-lg bg-gray-900 border border-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                </div>
                                <div>
                                    <label for="industry" class="block text-gray-300">Industry</label>
                                    <input type="text" name="industry" id="industry" placeholder="Industry"
                                        value="<?php echo htmlspecialchars($industry); ?>"
                                        class="w-full px-4 py-2 rounded-lg bg-gray-900 border border-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                </div>
                                <div>
                                    <label for="location" class="block text-gray-300">Location</label>
                                    <input type="text" name="location" id="location" placeholder="Location"
                                        value="<?php echo htmlspecialchars($location); ?>"
                                        class="w-full px-4 py-2 rounded-lg bg-gray-900 border border-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                </div>
                                <div>
                                    <label for="official_address" class="block text-gray-300">Official Address</label>
                                    <textarea name="official_address" id="official_address" rows="3"
                                        placeholder="Official Address"
                                        class="w-full px-4 py-2 rounded-lg bg-gray-900 border border-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400"><?php echo htmlspecialchars($official_address); ?></textarea>
                                </div>
                                <div>
                                    <label for="official_contact_number" class="block text-gray-300">Official Contact
                                        Number</label>
                                    <input type="tel" name="official_contact_number" id="official_contact_number"
                                        placeholder="Official Contact Number"
                                        value="<?php echo htmlspecialchars($official_contact_number); ?>"
                                        class="w-full px-4 py-2 rounded-lg bg-gray-900 border border-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Profile Creation Buttons -->
                    <div class="mt-8 text-center space-y-4">
                        <?php if ($user_type === 'volunteer'): ?>
                        <a href="org-profile-creation.php"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300 ease-in-out transform hover:scale-105">
                            Create Your Organizer Profile
                        </a>
                        <?php endif; ?>
                        <?php if ($user_type === 'organizer'): ?>
                        <a href="vol-profile-creation.php"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300 ease-in-out transform hover:scale-105">
                            Create Your Volunteer Profile
                        </a>
                        <?php endif; ?>
                    </div>

                    <div class="mt-8 text-center">
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300 ease-in-out transform hover:scale-105">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <?php include "../../includes/footer.php"; ?>
</body>

</html>