<?php
// Start session to check user authentication
session_start();

// Include the admin header
include_once '../components/admin/admin_header.php';

// Include the admin nav
include_once '../components/admin/admin_nav.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    // Redirect to login page if not logged in or not an admin
    header("Location: login.php");
    exit();
}

// Include the database connection file
require_once '../includes/conn.php';

// Retrieve all videos from the database
$sql = "SELECT * FROM video";
$result = $conn->query($sql);

// Close database connection
$conn->close();
?>


<div class="container">
    <h2>Admin Videos</h2>
    <table>
        <tr>
            <th>Title</th>
            <th>Options</th>
        </tr>
        <?php
        // Display videos in table rows
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["video_title"] . "</td>";
                echo "<td><a href='update_video.php?video_id=" . $row["video_id"] . "'>Update</a> | <a href='delete_video.php?video_id=" . $row["video_id"] . "'>Delete</a></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='2'>No videos found.</td></tr>";
        }
        ?>
    </table>
    <br>
    <a href="create_video.php">Create New Video</a>
</div>
</body>

</html>