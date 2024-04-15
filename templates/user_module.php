<?php
// Start session to check user authentication
session_start();

// Include header and nav bar
include_once '../components/user/user_header.php';
include_once '../components/user/user_nav.php';

// Check if user is logged in and is a user
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'user') {
    // Redirect to login page if not logged in or not a user
    header("Location: login.php");
    exit();
}

// Include your PHP database connection file
include_once '../includes/conn.php';

// Check if module_id is set in the URL
if (isset($_GET['module_id'])) {
    $module_id = $_GET['module_id'];

    // Fetch module details from the database
    $query = "SELECT * FROM module WHERE module_id = $module_id";
    $result = mysqli_query($conn, $query);
    $module = mysqli_fetch_assoc($result);

    // Display module details
    echo "    <h2>Module Page</h2>";
    echo "<div class='module-details'>";
    echo "<h2>" . $module['module_name'] . "</h2>";
    echo "<p>" . $module['module_description'] . "</p>";
    echo "</div>";

    // Fetch videos related to the selected module
    $video_query = "SELECT * FROM video WHERE video_module_id = $module_id";
    $video_result = mysqli_query($conn, $video_query);

    // Display videos
    echo "<div class='video-section'>";
    while ($video = mysqli_fetch_assoc($video_result)) {
        echo "<article class='video-container'>";
        echo "<a href='user_video.php?video_id=" . $video['video_id'] . "' class='thumbnail' data-duration='" . $video['video_duration'] . "'>";
        echo "<img class='thumbnail-image' src='" . $video['video_thumbnail'] . "' />";
        echo "</a>";
        echo "<div class='video-details'>";
        echo "<a href='user_video.php?video_id=" . $video['video_id'] . "' class='video-title'>" . $video['video_title'] . "</a>";
        echo "<div class='video-metadata'>";
        echo "Duration: " . $video['video_duration'];
        echo "</div>";
        echo "</div>";
        echo "</article>";
    }
    echo "</div>";
} else {
    echo "<p>No module selected.</p>";
}

// Close database connection
mysqli_close($conn);
