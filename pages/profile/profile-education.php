<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    <link rel="stylesheet" href="profile-index.css">
    <style>
        nav ul li:nth-child(1) {
            background-color: #007bff;
        }
    </style>
</head>
<body>
    <div class="container">
        <nav>
            <ul>
                <li><a href="#" class="active">About</a></li>
                <li><a href="profile-education.php">Education</a></li>
                <li><a href="#">Experience</a></li>
                <li><a href="#">Links</a></li>
                <li><a href="#">Contact</a></li>
            </ul>
        </nav>

        <section class="form-section">
            <div class="basic-info">
                <h2>Basic Information</h2>
                <form>
                    <label for="first-name">First Name</label>
                    <input type="text" id="first-name" placeholder="First Name">

                    <label for="last-name">Last Name</label>
                    <input type="text" id="last-name" placeholder="Last Name">

                    <label for="gender">I Identify As</label>
                    <select id="gender">
                        <option value="Gender">Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>

                    <label for="tshirt-size">T-Shirt Size</label>
                    <select id="tshirt-size">
                        <option value="T-shirt size">T-shirt size</option>
                        <option value="Small">Small</option>
                        <option value="Medium">Medium</option>
                        <option value="Large">Large</option>
                        <option value="X-Large">X-Large</option>
                    </select>

                    <a href="#" class="size-chart">Size chart - find your right fit</a>
                </form>
            </div>

            <div class="about-you">
                <h2>About You</h2>
                <form>
                    <label for="bio">Bio</label>
                    <textarea id="bio" placeholder="Add a bio."></textarea>

                    <label for="readme">README.md</label>
                    <textarea id="readme" placeholder="This is your chance to tell us more about yourself! Things you're good at, what drives you, and interesting projects you've built."></textarea>
                </form>
            </div>
        </section>
    </div>
</body>
</html>

