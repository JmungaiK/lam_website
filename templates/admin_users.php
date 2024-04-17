<?php
// Start session to check user authentication
session_start();

// Include the admin header
include_once '../components/admin/admin_header.php';

// Include the admin nav
include_once '../components/admin/admin_nav.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['login_id']) || $_SESSION['login_user_role'] !== 'admin') {
    // Redirect to login page if not logged in or not an admin
    header("Location: login.php");
    exit();
}


// Include the database connection file
require_once '../includes/conn.php';

// Retrieve all users from the database
$sql = "SELECT * FROM login";
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
                echo "<td>" . $row["login_user_name"] . "</td>"; // Changed to login_user_name
                echo "<td>" . $row["login_user_email"] . "</td>"; // Changed to login_user_email
                echo "<td>" . $row["login_user_role"] . "</td>"; // Changed to login_user_role
                echo "<td><a href='update_user.php?login_id=" . $row["login_id"] . "'>Update</a> | <a href='delete_user.php?login_id=" . $row["login_id"] . "'>Delete</a></td>"; // Changed user_id to login_id
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