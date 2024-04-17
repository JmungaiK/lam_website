<?php
// Start session to check user authentication
session_start();

// Include header and nav bar
include_once '../components/user/user_header.php';
include_once '../components/user/user_nav.php';

// Include your PHP database connection file
include_once '../includes/conn.php';

// Check if user is logged in and is a user
if (!isset($_SESSION['login_id']) || $_SESSION['login_user_role'] !== 'user') {
    // Redirect to login page if not logged in or not a user
    header("Location: login.php");
    exit();
}

// Fetch user's progress from the database
// Assuming $login_id is the ID of the logged-in user
$login_id = $_SESSION['login_id']; // Assuming you are using sessions for user authentication
$query = "SELECT COUNT(*) AS total_videos, SUM(progress_status) AS completed_videos FROM progress WHERE progress_login_id = $login_id";
$result = mysqli_query($conn, $query);
$progress_data = mysqli_fetch_assoc($result);

// Calculate progress percentage
$total_videos = $progress_data['total_videos'];
$completed_videos = $progress_data['completed_videos'];
$progress_percentage = ($total_videos > 0) ? ($completed_videos / $total_videos) * 100 : 0;


echo "<h2>Progress Page</h2>";
// Display progress information
echo "<p>You have watched $completed_videos out of $total_videos videos.</p>";

// Display progress bar
echo "<div class='progress-bar'>";
echo "<div class='progress' style='width: " . $progress_percentage . "%'></div>";
echo "</div>";

// Close database connection
mysqli_close($conn);
