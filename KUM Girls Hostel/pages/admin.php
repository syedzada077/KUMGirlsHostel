<?php
// Start session to continue the user session
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

// Handle CRUD operations for announcements
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['create_announcement'])) {
        $title = $_POST['title'];
        $description = $_POST['description'];
        $stmt = $conn->prepare("INSERT INTO announcements (title, description) VALUES (?, ?)");
        $stmt->bind_param("ss", $title, $description);
        $stmt->execute();
        $stmt->close();
    } elseif (isset($_POST['update_announcement'])) {
        $id = $_POST['id'];
        $title = $_POST['title'];
        $description = $_POST['description'];
        $stmt = $conn->prepare("UPDATE announcements SET title = ?, description = ? WHERE id = ?");
        $stmt->bind_param("ssi", $title, $description, $id);
        $stmt->execute();
        $stmt->close();
    } elseif (isset($_POST['delete_announcement'])) {
        $id = $_POST['id'];
        $stmt = $conn->prepare("DELETE FROM announcements WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }
}

// Handle CRUD operations for events
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['create_event'])) {
        $title = $_POST['title'];
        $date = $_POST['date'];
        $time = $_POST['time'];
        $venue = $_POST['venue'];
        $stmt = $conn->prepare("INSERT INTO events (title, date, time, venue) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $title, $date, $time, $venue);
        $stmt->execute();
        $stmt->close();
    } elseif (isset($_POST['update_event'])) {
        $id = $_POST['id'];
        $title = $_POST['title'];
        $date = $_POST['date'];
        $time = $_POST['time'];
        $venue = $_POST['venue'];
        $stmt = $conn->prepare("UPDATE events SET title = ?, date = ?, time = ?, venue = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $title, $date, $time, $venue, $id);
        $stmt->execute();
        $stmt->close();
    } elseif (isset($_POST['delete_event'])) {
        $id = $_POST['id'];
        $stmt = $conn->prepare("DELETE FROM events WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }
}

// Fetch announcements and events
$announcements = $conn->query("SELECT * FROM announcements");
$events = $conn->query("SELECT * FROM events");

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Announcements and Events</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">

    <style>
        html, body {
    margin: 0;
    padding: 0;
    font-family: 'Roboto', sans-serif;
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
    margin-bottom: 20px;
}

header .container {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

header h1 {
    margin: 0;
    font-size: 2em;
}

nav ul {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    gap: 20px;
}

nav ul li {
    display: inline;
}

nav ul li a {
    text-decoration: none;
    color: #ff6347;
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
    display: flex;
    flex-direction: column;
    gap: 15px;
}

form input[type="text"],
form input[type="date"],
form input[type="time"],
form textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 1em;
}

form button {
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    font-size: 1em;
}

form button[name="create_announcement"],
form button[name="create_event"] {
    background-color: #28a745;
    color: #fff;
}

form button[name="update_announcement"],
form button[name="update_event"] {
    background-color: #ffc107;
    color: #fff;
}

form button[name="delete_announcement"],
form button[name="delete_event"] {
    background-color: #dc3545;
    color: #fff;
}

form button:hover {
    opacity: 0.8;
}

ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

ul li {
    background-color: #f9f9f9;
    margin-bottom: 10px;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

ul li h3 {
    margin-top: 0;
    color: #007bff;
}

ul li p {
    margin: 10px 0;
}

ul li button {
    padding: 5px 10px;
    border: none;
    border-radius: 5px;
    background-color: #007bff;
    color: #fff;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

ul li button:hover {
    background-color: #0056b3;
}

    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1>Admin Panel</h1>
            <nav>
                <ul>
                    <li><a href="homepage.php">Home</a></li>
                </ul>
            </nav>
        </div>
    </header>
    
    <main>
        <section id="announcements">
            <div class="container">
                <h2>Manage Announcements</h2>
                <form method="POST">
                    <input type="hidden" name="id" id="announcement_id">
                    <input type="text" name="title" id="announcement_title" placeholder="Title" required>
                    <textarea name="description" id="announcement_description" placeholder="Description" required></textarea>
                    <button type="submit" name="create_announcement">Create</button>
                    <button type="submit" name="update_announcement">Update</button>
                    <button type="submit" name="delete_announcement">Delete</button>
                </form>
                <ul>
                    <?php while ($row = $announcements->fetch_assoc()): ?>
                    <li>
                        <h3><?php echo $row['title']; ?></h3>
                        <p><?php echo $row['description']; ?></p>
                        <button onclick="editAnnouncement(<?php echo $row['id']; ?>, '<?php echo addslashes($row['title']); ?>', '<?php echo addslashes($row['description']); ?>')">Edit</button>
                    </li>
                    <?php endwhile; ?>
                </ul>
            </div>
        </section>
        
        <section id="events">
            <div class="container">
                <h2>Manage Events</h2>
                <form method="POST">
                    <input type="hidden" name="id" id="event_id">
                    <input type="text" name="title" id="event_title" placeholder="Title" required>
                    <input type="date" name="date" id="event_date" required>
                    <input type="time" name="time" id="event_time" required>
                    <input type="text" name="venue" id="event_venue" placeholder="Venue" required>
                    <button type="submit" name="create_event">Create</button>
                    <button type="submit" name="update_event">Update</button>
                    <button type="submit" name="delete_event">Delete</button>
                </form>
                <ul>
                    <?php while ($row = $events->fetch_assoc()): ?>
                    <li>
                        <h3><?php echo $row['title']; ?></h3>
                        <p>Date: <?php echo $row['date']; ?></p>
                        <p>Time: <?php echo $row['time']; ?></p>
                        <p>Venue: <?php echo $row['venue']; ?></p>
                        <button onclick="editEvent(<?php echo $row['id']; ?>, '<?php echo addslashes($row['title']); ?>', '<?php echo $row['date']; ?>', '<?php echo $row['time']; ?>', '<?php echo addslashes($row['venue']); ?>')">Edit</button>
                    </li>
                    <?php endwhile; ?>
                </ul>
            </div>
        </section>
    </main>

    <script>
        function editAnnouncement(id, title, description) {
            document.getElementById('announcement_id').value = id;
            document.getElementById('announcement_title').value = title;
            document.getElementById('announcement_description').value = description;
        }

        function editEvent(id, title, date, time, venue) {
            document.getElementById('event_id').value = id;
            document.getElementById('event_title').value = title;
            document.getElementById('event_date').value = date;
            document.getElementById('event_time').value = time;
            document.getElementById('event_venue').value = venue;
        }
    </script>
</body>
</html>
