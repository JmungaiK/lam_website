<?php
// Include the database connection file
require_once '../includes/conn.php';

// Check if module ID is provided in the URL
if (isset($_GET['module_id']) && !empty($_GET['module_id'])) {
    $module_id = $_GET['module_id'];

    // Delete associated videos first
    $sql_delete_videos = "DELETE FROM video WHERE video_module_id = '$module_id'";
    if ($conn->query($sql_delete_videos) === TRUE) {
        // Videos deleted successfully, now delete the module
        $sql_delete_module = "DELETE FROM module WHERE module_id = '$module_id'";
        if ($conn->query($sql_delete_module) === TRUE) {
            // Module deleted successfully
            header("Location: admin_modules.php");
            exit();
        } else {
            echo "Error deleting module: " . $conn->error;
        }
    } else {
        echo "Error deleting videos: " . $conn->error;
    }
} else {
    // Module ID not provided
    echo "Module ID not provided.";
    exit();
}

// Close database connection
$conn->close();
