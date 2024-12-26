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

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO applications (user_id, name,  student_id, subject, message) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("issss", $user_id, $name, $student_id, $subject, $message);

// Set parameters and execute
$user_id = $_SESSION['user_id'];
$name = $_POST['name'];
$student_id = $_POST['student-id'];
$subject = $_POST['subject'];
$message = $_POST['message'];
$stmt->execute();

$stmt->close();
$conn->close();

// Redirect to homepage or application success page
header('Location: homepage.php');
exit;
?>
