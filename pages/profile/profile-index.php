<!DOCTYPE html>
<html>

<head>
    <title>Welcome</title>
    <link href="https://fonts.googleapis.com/css2?family=Google+Sans:wght@400;700&display=swap" rel="stylesheet">

    <style>
        html, body {
            height: 100%;
            margin: 0;
            overflow: hidden;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #ffffff;
            font-family: 'Google Sans', sans-serif;
            color: white;
        }

        .container {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
            background: linear-gradient(to bottom right, #1a1a1a, #000000, #1a1a1a);
        }

        .landing-page {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 20px;
            border-radius: 8px;
            color: white;
            text-align: center;
        }

        h1 {
            font-size: 3em; /* Increased size */
            margin: 10px 0;
        }

        p {
            margin: 10px 0;
        }

        .Btn-Container {
            display: flex;
            width: 220px;
            height: 60px;
            background-color: #ffffff;
            border-radius: 50px;
            box-shadow: none;
            justify-content: space-between;
            align-items: center;
            border: none;
            cursor: pointer;
            text-decoration: none;
            margin: 20px auto;
            padding: 0 15px;
        }

        .icon-Container {
            width: 50px;
            height: 50px;
            background-color: white;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .text {
            flex-grow: 1;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: black;
            font-size: 1.1em;
            letter-spacing: 1.0px;
            font-family: 'Google Sans', sans-serif;
            font-weight: bold;
            padding-left: 15px;
        }

        .icon-Container svg {
            transition-duration: 1.5s;
            fill: black;
        }

        .Btn-Container:hover .icon-Container svg {
            transition-duration: 1.5s;
            animation: arrow 1s linear infinite;
        }

        @keyframes arrow {
            0% {
                opacity: 0;
                transform: translateX(0);
            }
            100% {
                opacity: 1;
                transform: translateX(10px);
            }
        }

        @media (max-height: 400px) {
            h1 {
                font-size: 1.5em;
            }
            p {
                font-size: 0.9em;
            }
            .Btn-Container {
                width: 180px;
                height: 50px;
            }
            .text {
                font-size: 0.9em;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="landing-page">
            <h1>Welcome to VolHub</h1>
            <p>Let's create your personal profile</p>
            <a href="profile-creation.php" class="Btn-Container">
                <span class="text">Create Profile</span>
                <span class="icon-Container">
                    <svg width="16" height="19" viewBox="0 0 16 19" fill="none" xmlns="http://www.w3.org/2000/svg">
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
