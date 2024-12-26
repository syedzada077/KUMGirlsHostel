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
$password = ""; // Replace with your database password if set
$dbname = "girls_hostel";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user details from database
$user_id = $_SESSION['user_id'];
$sql_user = "SELECT username, full_name, reg_number, department, semester, admission_year, joined_at FROM registrations WHERE id = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$result_user = $stmt_user->get_result();

if ($result_user->num_rows == 1) {
    // User found, fetch user details
    $user = $result_user->fetch_assoc();
    $username = $user['username'];
    $full_name = $user['full_name'];
    $reg_number = $user['reg_number'];
    $department = $user['department'];
    $semester = $user['semester'];
    $admission_year = $user['admission_year'];
    $joined_at = $user['joined_at'];
} else {
    // No user found (shouldn't normally happen if session is valid)
    header('Location: login.php');
    exit;
}

$stmt_user->close();

// Fetch announcements
$sql_announcements = "SELECT title, description FROM announcements";
$result_announcements = $conn->query($sql_announcements);

// Fetch events
$sql_events = "SELECT title, date, time, venue FROM events";
$result_events = $conn->query($sql_events);

// Fetch applications with vote status
$sql_applications = "SELECT a.id, a.subject, a.message, a.agree_count, a.disagree_count, r.full_name
                    FROM applications a
                    JOIN registrations r ON a.user_id = r.id";
$result_applications = $conn->query($sql_applications);

// Fetch user votes for applications
$sql_votes = "SELECT application_id, vote FROM application_votes WHERE user_id = ?";
$stmt_votes = $conn->prepare($sql_votes);
$stmt_votes->bind_param("i", $user_id);
$stmt_votes->execute();
$result_votes = $stmt_votes->get_result();

$user_votes = [];
while ($row = $result_votes->fetch_assoc()) {
    $user_votes[$row['application_id']] = $row['vote'];
}

$stmt_votes->close();
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
        html, body {
    margin: 0;
    padding: 0;
}

/* Global styles */
body {
    font-family: 'Roboto', sans-serif;
    line-height: 1.6;
    background-color: #f4f4f4;
    color: #333;
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
    width: 50px;
    height: auto;
    margin-right: 10px;
}

.logo p {
    margin: 0;
    font-size: 1.5em;
    font-weight: bold;
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
    background-color: #fff;
    padding: 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
}

h2 {
    color: #007bff;
    font-size: 2em;
    margin-bottom: 20px;
}

.profile-info {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
}

.profile-info img {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    margin-right: 20px;
}

.user-details {
    font-size: 1.1em;
}

.announcement h3,
.event h3 {
    color: #007bff;
}

.gallery-images {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    grid-gap: 10px;
}

.gallery-images img {
    width: 100%;
    height: auto;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s, box-shadow 0.3s;
}

.gallery-images img:hover {
    transform: scale(1.05);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
}

footer {
    background-color: #007bff;
    color: #fff;
    padding: 20px 0;
    text-align: center;
    margin-top: 40px;
    box-shadow: 0 -4px 8px rgba(0, 0, 0, 0.1);
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

.application {
    border: 1px solid #ddd;
    padding: 20px;
    margin-bottom: 20px;
    border-radius: 10px;
    background-color: #f9f9f9;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.application h3 {
    margin-top: 0;
    color: #007bff;
}

.application p {
    margin-bottom: 10px;
}

.application form {
    display: flex;
    gap: 10px;
}

.application button {
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    font-size: 1em;
}

.application button[disabled] {
    background-color: #ccc;
    cursor: not-allowed;
}

.application button[name="agree"] {
    background-color: #28a745;
    color: #fff;
}

.application button[name="agree"]:hover {
    background-color: #218838;
}

.application button[name="disagree"] {
    background-color: #dc3545;
    color: #fff;
}

.application button[name="disagree"]:hover {
    background-color: #c82333;
}

    </style>
</head>
<body>
    <header>
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <img src="img/logo.png" alt="KUM Logo">
                    <p><b>KUM Girls Hostel</b></p>
                </div>
                <nav>
                    <ul>
                        <li><a href="application.php">Write an Application</a></li>
                        <li><a href="logout.php">Logout</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>
    
    <main>
        <section id="user-profile">
            <div class="container">
                <h2>Welcome, <?php echo $username; ?>!</h2>
                <div class="profile-info">
                    
                    <div class="user-details">
                        <p>Name: <?php echo $full_name; ?></p>
                        <p>Reg No.: <?php echo $reg_number; ?></p>
                        <p>Department: <?php echo $department; ?></p>
                        <p>Semester: <?php echo $semester; ?></p>
                        <p>Year of Admission: <?php echo $admission_year; ?></p>
                        <p>Joined At: <?php echo $joined_at; ?></p>
                    </div>
                </div>
            </div>
        </section>
        
        <section id="announcements">
            <div class="container">
                <h2>Announcements</h2>
                <?php if ($result_announcements->num_rows > 0): ?>
                    <?php while($announcement = $result_announcements->fetch_assoc()): ?>
                        <div class="announcement">
                            <h3><?php echo htmlspecialchars($announcement['title']); ?></h3>
                            <p><?php echo htmlspecialchars($announcement['description']); ?></p>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No announcements available.</p>
                <?php endif; ?>
            </div>
        </section>
        
        <section id="events">
            <div class="container">
                <h2>Upcoming Events</h2>
                <?php if ($result_events->num_rows > 0): ?>
                    <?php while($event = $result_events->fetch_assoc()): ?>
                        <div class="event">
                            <h3><?php echo htmlspecialchars($event['title']); ?></h3>
                            <p>Date: <?php echo htmlspecialchars($event['date']); ?></p>
                            <p>Time: <?php echo htmlspecialchars($event['time']); ?></p>
                            <p>Venue: <?php echo htmlspecialchars($event['venue']); ?></p>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No upcoming events available.</p>
                <?php endif; ?>
            </div>
        </section>
        
        <section id="applications">
            <div class="container">
                <h2>Applications</h2>
                <?php if ($result_applications->num_rows > 0): ?>
                    <?php while($application = $result_applications->fetch_assoc()): ?>
                        <div class="application">
                            <h3><?php echo htmlspecialchars($application['subject']); ?></h3>
                            <p>By: <?php echo htmlspecialchars($application['full_name']); ?></p>
                            <p><?php echo nl2br(htmlspecialchars($application['message'])); ?></p>
                            <p>Agree: <?php echo $application['agree_count']; ?> | Disagree: <?php echo $application['disagree_count']; ?></p>
                            <form action="vote.php" method="POST">
                                <input type="hidden" name="application_id" value="<?php echo $application['id']; ?>">
                                <button type="submit" name="vote" value="agree" <?php echo isset($user_votes[$application['id']]) && $user_votes[$application['id']] == 'agree' ? 'disabled' : ''; ?>>Agree</button>
                                <button type="submit" name="vote" value="disagree" <?php echo isset($user_votes[$application['id']]) && $user_votes[$application['id']] == 'disagree' ? 'disabled' : ''; ?>>Disagree</button>
                            </form>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No applications available.</p>
                <?php endif; ?>
            </div>
        </section>
        
        <section id="gallery">
            <div class="container">
                <h2>Photo Gallery</h2>
                <div class="gallery-images">
                    <img src="img/pic1.jpg" alt="First Image">
                    <img src="img/pic2.jpeg" alt="Second Image">
                    <img src="img/pic3.jpg" alt="Third Image">
                    <img src="img/pic4.jpg" alt="Fourth Image">
                </div>
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
