<?php
// Include the database connection file
require_once '../includes/conn.php';

// Check if user ID is provided in the URL
if (isset($_GET['user_id']) && !empty($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    // Delete the user
    $sql_delete_user = "DELETE FROM user WHERE user_id = '$user_id'";
    if ($conn->query($sql_delete_user) === TRUE) {
        // User deleted successfully
        header("Location: admin_users.php");
        exit();
    } else {
        echo "Error deleting user: " . $conn->error;
    }
} else {
    // User ID not provided
    echo "User ID not provided.";
    exit();
}

// Close database connection
$conn->close();
