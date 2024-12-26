<?php
// Start session
session_start();

// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "girls_hostel";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$reg_number_valid = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if 'reg_number' is set
    if (isset($_POST['reg_number'])) {
        $provided_reg_number = $_POST['reg_number'];
        $valid_reg_numbers = file('valid_reg_numbers.txt', FILE_IGNORE_NEW_LINES);

        if (in_array($provided_reg_number, $valid_reg_numbers)) {
            $reg_number_valid = true;
        }
    }

    if ($reg_number_valid && isset($_POST['full_name'], $_POST['username'], $_POST['admission_year'], $_POST['department'], $_POST['semester'], $_POST['password'], $_POST['confirm_password'])) {
        $full_name = $conn->real_escape_string($_POST['full_name']);
        $username = $conn->real_escape_string($_POST['username']);
        $reg_number = $conn->real_escape_string($_POST['reg_number']);
        $admission_year = $conn->real_escape_string($_POST['admission_year']);
        $department = $conn->real_escape_string($_POST['department']);
        $semester = $conn->real_escape_string($_POST['semester']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password

        $sql = "INSERT INTO registrations (full_name, username, reg_number, admission_year, department, semester, password) 
                VALUES ('$full_name', '$username', '$reg_number', '$admission_year', '$department', '$semester', '$password')";

        if ($conn->query($sql) === TRUE) {
            // Registration successful, redirect to login page
            header('Location: login.php');
            exit;
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Invalid registration number or incomplete form data.";
    }
}

$conn->close();
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kohsar University Murree - Girls Hostel Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">

    <style>
         body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f8ff;
            color: #333;
            background: linear-gradient(rgba(255,255,255,0.1),rgba(255,255,255,0.1)),url('img/cover.jpg') no-repeat center center/cover;
        }

        .container {
            width: 80%;
            margin: 0 auto;
        }

        header {
            background-color: #007bff;
            color: #fff;
            padding: 15px 0;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .header-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .logo {
            display: flex;
            align-items: center;
        }

        .logo img {
            width: 50px;
            margin-right: 10px;
        }

        .logo p {
            margin: 0;
            font-size: 1.5em;
            color: #fff;
        }

        nav ul {
            list-style: none;
            display: flex;
            gap: 20px;
            padding: 0;
            margin: 0;
        }

        nav ul li {
            display: inline;
        }

        nav ul li a {
            text-decoration: none;
            color: #fff;
            padding: 10px 20px;
            transition: background-color 0.3s, color 0.3s;
            border-radius: 5px;
        }

        nav ul li a:hover {
            background-color: #ff6347;
            color: #fff;
        }

        main {
            padding: 40px 0;
        }

        section {
            margin-bottom: 30px;
            text-align: center;
        }

        h1 {
            color: #007bff;
            font-size: 2.5em;
            margin-bottom: 20px;
        }

        form {
            max-width: 600px;
            margin: 0 auto;
            background-color: rgba(255, 255, 255, 0.8);
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1em;
        }

        button, input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 1em;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover, input[type="submit"]:hover {
            background-color: #ff6347;
        }

        footer {
            background-color: #007bff;
            color: #fff;
            padding: 20px 0;
            text-align: center;
        }

        footer p {
            margin: 5px 0;
        }

        footer a {
            color: #fff;
            text-decoration: none;
            margin: 0 10px;
            transition: color 0.3s;
        }

        footer a:hover {
            color: #ff6347;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <img src="img/logo.png" alt="KUM Logo"><p><b>KUM Girls Hostel</b></p>
                </div>
                <nav>
                    <ul>
                        <li><a href="register.php">Register</a></li>
                        <li><a href="login.php">Login</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>
    <main>
        <section id="register">
            <div class="container">
                <h1>Register for Kohsar University Murree Girls Hostel Portal</h1>
                <form action="register.php" method="POST">
                    <div class="form-group">
                        <label for="full_name">Full Name:</label>
                        <input type="text" id="full_name" name="full_name" required>
                    </div>
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" id="username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="reg_number">Last Four Digits of Registration Number:</label>
                        <input type="text" id="reg_number" name="reg_number" maxlength="17" required>
                    </div>
                    <div class="form-group">
                        <label for="admission_year">Year of Admission:</label>
                        <input type="text" id="admission_year" name="admission_year" required>
                    </div>
                    <div class="form-group">
                        <label for="department">Department:</label>
                        <select id="department" name="department" required>
                            <option value="">Select Department</option>
                            <option value="Computer Science">BS Computer Science</option>
                            <option value="Electrical Engineering">BS Software Engineering</option>
                            <option value="Mechanical Engineering">BS Psychology</option>
                            <option value="Civil Engineering">BS Sociology</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="semester">Semester:</label>
                        <select id="semester" name="semester" required>
                            <option value="">Select Semester</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirm Password:</label>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                    </div>
                    <div class="form-group">
                        <input type="submit" value="Register">
                    </div>
                </form>

            </div>
        </section>
    </main>
    
    <footer>
        <div class="container">
            <p>&copy; 2024 Kohsar University Murree. All rights reserved.</p>
            <p>Follow us on:
                <a href="#">Facebook</a> |
                <a href="#">Twitter</a> |
                <a href="#">Instagram</a>
            </p>
        </div>
    </footer>

    

</body>
</html>
