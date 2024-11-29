<?php

function getDatabaseConnection() {
    static $con = null; // Store the connection statically

    if ($con === null) {
        $serverName = "localhost";
        $dBUserName = "root";
        $dBPassword = "";
        $dbName = "db";

        $con = mysqli_connect($serverName, $dBUserName, $dBPassword);
        if (!$con) {
            die("Initial Connection Failed: " . mysqli_connect_error());
        }

        $dbCreate = "CREATE DATABASE IF NOT EXISTS $dbName CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
        if (!mysqli_query($con, $dbCreate)) {
            die("Database Creation Failed: " . mysqli_error($con));
        }

        mysqli_select_db($con, $dbName);
        if (mysqli_connect_errno()) {
            die("Selecting Database Failed: " . mysqli_connect_error());
        }

        // Table Creation (Corrected order, character set, and collation)
        $tables = [
            "users" => "CREATE TABLE IF NOT EXISTS users (
                usersId INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                usersEmail VARCHAR(150) NOT NULL UNIQUE,
                usersPwd VARCHAR(255) NOT NULL,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                role ENUM('admin', 'user') NOT NULL DEFAULT 'user',
                user_type SET('volunteer', 'organizer', 'both') NOT NULL DEFAULT 'volunteer',
                volunteer BOOLEAN DEFAULT 0,
                organizer BOOLEAN DEFAULT 0
            ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;",


          "user_profiles" =>  "CREATE TABLE IF NOT EXISTS user_profiles (
                profile_id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                profile_usersId INT(11) UNSIGNED NOT NULL,
                full_name VARCHAR(255) NOT NULL,
                username VARCHAR(50) NOT NULL UNIQUE,
                identity VARCHAR(20),
                phone VARCHAR(20),
                city VARCHAR(50),
                bio TEXT,
                degree_type VARCHAR(50),
                institution VARCHAR(100),
                field_of_study VARCHAR(100),
                graduation_month VARCHAR(20),
                graduation_year INT,
                links TEXT,
				profile_picture VARCHAR(255),
                FOREIGN KEY (profile_usersId) REFERENCES users(usersId) ON DELETE CASCADE
            ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;",

            "user_profiles_vol" => "CREATE TABLE IF NOT EXISTS user_profiles_vol (
                id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                emergency_name VARCHAR(100) NOT NULL,
                emergency_phone VARCHAR(20) NOT NULL,
                userid INT(11) UNSIGNED NOT NULL,
                FOREIGN KEY (userid) REFERENCES users(usersId) ON DELETE CASCADE
            ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;",


            "user_profiles_org" => "CREATE TABLE IF NOT EXISTS user_profiles_org (
                id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                organization_name VARCHAR(255) NOT NULL,
                job_title VARCHAR(100) NOT NULL,
                industry VARCHAR(50),
                location VARCHAR(255),
                official_address TEXT,
                official_contact_number VARCHAR(20),
                userid INT(11) UNSIGNED NOT NULL,
                FOREIGN KEY (userid) REFERENCES users(usersId) ON DELETE CASCADE
            ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;",

            "events" => "CREATE TABLE IF NOT EXISTS events (
                event_id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                organizer_id INT(11) UNSIGNED NULL DEFAULT NULL,
                event_name VARCHAR(255) NOT NULL,
                event_description TEXT,
                event_datetime DATETIME NOT NULL,
                event_location VARCHAR(255) NOT NULL,
                event_thumbnail VARCHAR(255),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                reg_status BOOLEAN DEFAULT 1,
                admin_approve BOOLEAN DEFAULT 0,
                FOREIGN KEY (organizer_id) REFERENCES users(usersId) ON DELETE CASCADE
            ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;",

           "requests" => "CREATE TABLE IF NOT EXISTS requests (
                request_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                event_id INT(11) UNSIGNED NOT NULL,
                requests_usersId INT UNSIGNED NOT NULL,
                submission_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                request_status ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending',
                FOREIGN KEY (event_id) REFERENCES events(event_id) ON DELETE CASCADE,
                FOREIGN KEY (requests_usersId) REFERENCES users(usersId) ON DELETE CASCADE
            ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"


        ];

        foreach ($tables as $tableName => $sql) {
            if (!mysqli_query($con, $sql)) {
                die("Error creating table '$tableName': " . mysqli_error($con));
            }
        }
    }


    return $con;
}


?>