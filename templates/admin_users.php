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

// Retrieve all users from the database
$sql = "SELECT * FROM user";
$result = $conn->query($sql);

// Close database connection
$conn->close();
?>

<div class="container">
    <h2>Admin Users</h2>
    <table>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Options</th>
        </tr>
        <?php
        // Display users in table rows
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["user_name"] . "</td>";
                echo "<td>" . $row["user_email"] . "</td>";
                echo "<td>" . $row["user_role"] . "</td>";
                echo "<td><a href='update_user.php?user_id=" . $row["user_id"] . "'>Update</a> | <a href='delete_user.php?user_id=" . $row["user_id"] . "'>Delete</a></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No users found.</td></tr>";
        }
        ?>
    </table>
    <br>
    <a href="create_user.php">Create New User</a>
</div>
</body>

</html>