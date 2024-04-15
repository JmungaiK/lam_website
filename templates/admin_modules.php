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

// Retrieve all modules from the database
$sql = "SELECT * FROM module";
$result = $conn->query($sql);

// Close database connection
$conn->close();
?>


<div class="container">
    <h2>Admin Modules</h2>
    <table>
        <tr>
            <th>Name</th>
            <th>Options</th>
        </tr>
        <?php
        // Display modules in table rows
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["module_name"] . "</td>";
                echo "<td><a href='update_module.php?module_id=" . $row["module_id"] . "'>Update</a> | <a href='delete_module.php?module_id=" . $row["module_id"] . "'>Delete</a></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='2'>No modules found.</td></tr>";
        }
        ?>
    </table>
    <br>
    <a href="create_module.php">Create New Module</a>
</div>
</body>

</html>