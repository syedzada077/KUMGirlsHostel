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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['username'], $_POST['password'])) {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $conn->real_escape_string($_POST['password']);

    // Retrieve user from database
    $sql = "SELECT id, username, password FROM registrations WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // User found, verify password
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // Password is correct, start session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            // Redirect to homepage or any other page after successful login
            header('Location: homepage.php');
            exit;
        } else {
            echo "Invalid username or password.";
        }
    } else {
        echo "Invalid username or password.";
    }

    $stmt->close();
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
            color: #333;
            background-color: #f0f8ff;
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
            padding: 20px 0;
        }

        section {
            margin-bottom: 30px;
            text-align: center;
        }

        h1 {
            color: #007bff;
            font-size: 2.5em;
        }

        form {
            max-width: 400px;
            margin: 0 auto;
            background-color: rgba(255, 255, 255, 0.8);
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1em;
        }

        button {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 1em;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
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
                        <li><a href="adminlogin.php">Login As Admin</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>
    <main>
        <section id="login">
            <div class="container">
                <h1>Login to Kohsar University Murree Girls Hostel Portal</h1>
                <form action="login.php" method="POST">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required><br><br>
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required><br><br>
                    <button type="submit">Login</button>

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
