<!DOCTYPE html>
<html>
<head>
    <title>Profile Page</title>
    <!-- Link to Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Google+Sans:wght@400;700&display=swap" rel="stylesheet">

    <style>
        html, body {
            height: 100%;
            margin: 0; /* Ensure no margin */
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f2f2f2;
            margin: 0; /* Ensure there is no default margin */
            font-family: 'Google Sans', sans-serif; /* Apply Google Sans to the entire page */
        }

        .landing-page {
            display: flex; /* Enable Flexbox */
            flex-direction: column; /* Stack children vertically */
            justify-content: center; /* Center content horizontally */
            align-items: center; /* Center content vertically */
            min-height: 100vh; /* Full viewport height */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .Btn-Container {
            display: flex;
            width: 170px;
            height: fit-content;
            background-color: #1d2129;
            border-radius: 40px;
            box-shadow: 0px 5px 10px #bebebe;
            justify-content: space-between;
            align-items: center;
            border: none;
            cursor: pointer;
            text-decoration: none; /* Remove underline from the link */
            margin: 20px auto; /* Center the button horizontally */
        }
        
        .icon-Container {
            width: 45px;
            height: 45px;
            background-color: #f59aff;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            border: 3px solid #1d2129;
        }

        .text {
            width: calc(170px - 45px);
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 0.9em;
            letter-spacing: 1.0px;
            font-family: 'Google Sans', sans-serif; /* Apply Google Sans to button text */
        }


        .icon-Container svg {
            transition-duration: 1.5s;
        }

        .Btn-Container:hover .icon-Container svg {
            transition-duration: 1.5s;
            animation: arrow 1s linear infinite;
        }

        @keyframes arrow {
            0% {
                opacity: 0;
                margin-left: 0px;
            }
            100% {
                opacity: 1;
                margin-left: 10px;
            }
        }

        /* Background Pattern */
        .container {
            width: 100%;
            height: 100vh;
            background-color: #ffffff;
            background-image: radial-gradient(rgba(12, 12, 12, 0.171) 2px, transparent 0);
            background-size: 30px 30px;
            background-position: -5px -5px;
        }

    </style>
</head>
<body>
 
<div class="container">

    <div class="landing-page">
        <h1>Welcome to VolunteerConnect</h1>
        <p>Let's create your personal profile</p>

        <a href="profile-about.php" class="Btn-Container">
            <span class="text">Create Profile</span>
            <span class="icon-Container">
                <svg
                    width="16"
                    height="19"
                    viewBox="0 0 16 19"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                >
                <circle cx="1.61321" cy="1.61321" r="1.5" fill="black"></circle>
                <circle cx="5.73583" cy="1.61321" r="1.5" fill="black"></circle>
                <circle cx="5.73583" cy="5.5566" r="1.5" fill="black"></circle>
                <circle cx="9.85851" cy="5.5566" r="1.5" fill="black"></circle>
                <circle cx="9.85851" cy="9.5" r="1.5" fill="black"></circle>
                <circle cx="13.9811" cy="9.5" r="1.5" fill="black"></circle>
                <circle cx="5.73583" cy="13.4434" r="1.5" fill="black"></circle>
                <circle cx="9.85851" cy="13.4434" r="1.5" fill="black"></circle>
                <circle cx="1.61321" cy="17.3868" r="1.5" fill="black"></circle>
                <circle cx="5.73583" cy="17.3868" r="1.5" fill="black"></circle>
                </svg>
            </span>
        </a>
    </div>
</div>
</body>
</html>
