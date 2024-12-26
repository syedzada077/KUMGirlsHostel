<?php
// Start session to continue the user session
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

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

// Fetch user details from database
$user_id = $_SESSION['user_id'];
$sql = "SELECT full_name,  reg_number FROM registrations WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    // User found, fetch user details
    $user = $result->fetch_assoc();
    $full_name = $user['full_name'];
    
    $reg_number = $user['reg_number'];
} else {
    // No user found (shouldn't normally happen if session is valid)
    header('Location: login.php');
    exit;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kohsar University Murree - Hostel Application Form</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">

    <style>
        /* Reset default browser styles */
       /* Reset default browser styles */
html, body {
    margin: 0;
    padding: 0;
    font-family: 'Roboto', sans-serif;
    background-color: #f4f4f4;
    color: #333;
}

/* Global styles */
body {
    line-height: 1.6;
}

.container {
    width: 80%;
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

header {
    background-color: #007bff;
    color: #fff;
    padding: 15px 0;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.logo {
    display: flex;
    align-items: center;
}

.logo img {
    width: 40px;
    height: auto;
    margin-right: 10px;
}

nav ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
    display: flex;
    gap: 20px;
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
    background-color: #0056b3;
}

main {
    padding: 20px 0;
}

section {
    background-color: #fff;
    padding: 20px;
    margin-bottom: 30px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
}

h2 {
    color: #007bff;
    font-size: 1.8em;
    margin-bottom: 20px;
}

form {
    max-width: 500px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    gap: 15px;
}

label {
    font-weight: bold;
    margin-bottom: 5px;
}

input[type="text"],
input[type="email"],
textarea {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
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
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: #0056b3;
}

footer {
    background-color: #f4f4f4;
    padding: 20px 0;
    text-align: center;
}

footer p {
    margin: 0;
}

footer p a {
    text-decoration: none;
    color: #007bff;
    margin: 0 10px;
}

footer p a:hover {
    text-decoration: underline;
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
                        <li><a href="homepage.php">Home</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>
    
    <main>
        <section id="application-form">
            <div class="container">
                <h2>Hostel Application Form</h2>
                <form action="submit_application.php" method="POST">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($full_name); ?>" readonly><br><br>
                    <label for="student-id">Student ID:</label>
                    <input type="text" id="student-id" name="student-id" value="<?php echo htmlspecialchars($reg_number); ?>" readonly><br><br>
                    <label for="subject">Subject:</label>
                    <input type="text" id="subject" name="subject" required><br><br>
                    <label for="message">Message:</label>
                    <textarea id="message" name="message" rows="4" required></textarea><br><br>
                    <button type="submit">Submit Application</button>
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
