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

// Get user input
$user_id = $_SESSION['user_id'];
$application_id = $_POST['application_id'];
$vote = $_POST['vote'];

// Check if user has already voted on this application
$sql_check = "SELECT * FROM application_votes WHERE user_id = ? AND application_id = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("ii", $user_id, $application_id);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows == 0) {
    // User has not voted yet, insert vote
    $sql_insert = "INSERT INTO application_votes (user_id, application_id, vote) VALUES (?, ?, ?)";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bind_param("iis", $user_id, $application_id, $vote);
    $stmt_insert->execute();
    $stmt_insert->close();

    // Update the vote count in the applications table
    if ($vote == 'agree') {
        
        $sql_update = "UPDATE applications SET agree_count = agree_count + 1 WHERE id = ?";
       } else {
           $sql_update = "UPDATE applications SET disagree_count = disagree_count + 1 WHERE id = ?";
       }

       $stmt_update = $conn->prepare($sql_update);
       $stmt_update->bind_param("i", $application_id);
       $stmt_update->execute();
       $stmt_update->close();
   } else {
       // User has already voted, update the vote
       $sql_update_vote = "UPDATE application_votes SET vote = ? WHERE user_id = ? AND application_id = ?";
       $stmt_update_vote = $conn->prepare($sql_update_vote);
       $stmt_update_vote->bind_param("sii", $vote, $user_id, $application_id);
       $stmt_update_vote->execute();
       $stmt_update_vote->close();

       // Update the vote count in the applications table
       if ($vote == 'agree') {
           $sql_update = "UPDATE applications SET agree_count = agree_count + 1, disagree_count = disagree_count - 1 WHERE id = ?";
       } else {
           $sql_update = "UPDATE applications SET disagree_count = disagree_count + 1, agree_count = agree_count - 1 WHERE id = ?";
       }

       $stmt_update = $conn->prepare($sql_update);
       $stmt_update->bind_param("i", $application_id);
       $stmt_update->execute();
       $stmt_update->close();
   }

   $conn->close();

   // Redirect back to homepage
   header('Location: homepage.php');
   exit;
   ?>
