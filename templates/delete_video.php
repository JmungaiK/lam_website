<?php
// Include the database connection file
require_once '../includes/conn.php';

// Check if video ID is provided in the URL
if (isset($_GET['video_id']) && !empty($_GET['video_id'])) {
    $video_id = $_GET['video_id'];

    // Delete the video
    $sql_delete_video = "DELETE FROM video WHERE video_id = '$video_id'";
    if ($conn->query($sql_delete_video) === TRUE) {
        // Video deleted successfully
        header("Location: admin_videos.php");
        exit();
    } else {
        echo "Error deleting video: " . $conn->error;
    }
} else {
    // Video ID not provided
    echo "Video ID not provided.";
    exit();
}

// Close database connection
$conn->close();
