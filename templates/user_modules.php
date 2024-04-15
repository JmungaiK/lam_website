<?php
// Start session to check user authentication
session_start();

// Include header and nav bar
include_once '../components/user/user_header.php';
include_once '../components/user/user_nav.php';

// Include your PHP database connection file
include_once '../includes/conn.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'user') {
    // Redirect to login page if not logged in or not an admin
    header("Location: login.php");
    exit();
}

// Fetch modules from the database
$query = "SELECT * FROM module";
$result = mysqli_query($conn, $query);

// Display modules as cards
while ($row = mysqli_fetch_assoc($result)) {
    echo "    <h2>Modules Page</h2>";
    echo "<div class='module-card'>";
    echo "<h2>" . $row['module_name'] . "</h2>";
    echo "<p>" . $row['module_description'] . "</p>";
    echo "<a href='user_module.php?module_id=" . $row['module_id'] . "' class='view-module-btn'>View Module</a>";
    echo "</div>";
}

// Close database connection
mysqli_close($conn);
