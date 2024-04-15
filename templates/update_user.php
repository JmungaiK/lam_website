<?php
// Start session to check user authentication
session_start();


// Include the admin header
include_once '../components/admin/admin_header.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    // Redirect to login page if not logged in or not an admin
    header("Location: login.php");
    exit();
}

// Include the database connection file
require_once '../includes/conn.php';

// Initialize variables to store form data and error messages
$username = $email = $role = '';
$usernameErr = $emailErr = $roleErr = '';

// Check if user ID is provided in the URL
if (isset($_GET['user_id']) && !empty($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    // Retrieve user data from the database
    $sql = "SELECT * FROM user WHERE user_id = '$user_id'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $username = $row['user_name'];
        $email = $row['user_email'];
        $role = $row['user_role'];
    } else {
        // User not found
        echo "User not found.";
        exit();
    }
} else {
    // User ID not provided
    echo "User ID not provided.";
    exit();
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate form data
    if (empty($_POST["username"])) {
        $usernameErr = "Username is required";
    } else {
        $username = $_POST["username"];
    }

    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } else {
        $email = $_POST["email"];
    }

    if (empty($_POST["role"])) {
        $roleErr = "Role is required";
    } else {
        $role = $_POST["role"];
    }

    // If there are no input errors, proceed with updating user
    if (empty($usernameErr) && empty($emailErr) && empty($roleErr)) {
        // Prepare and execute SQL statement to update user data in the database
        $sql = "UPDATE user SET username='$username', email='$email', role='$role' WHERE user_id='$user_id'";

        if ($conn->query($sql) === TRUE) {
            // User updated successfully, redirect to admin users page
            header("Location: admin_users.php");
            exit();
        } else {
            echo "Error updating user: " . $conn->error;
        }
    }
}

// Close database connection
$conn->close();
?>


<div class="container">
    <h2>Update User</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo $username; ?>">
            <span class="error"><?php echo $usernameErr; ?></span>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo $email; ?>">
            <span class="error"><?php echo $emailErr; ?></span>
        </div>
        <div class="form-group">
            <label for="role">Role:</label>
            <select id="role" name="role">
                <option value="">Select Role</option>
                <option value="admin" <?php echo ($role === 'admin') ? 'selected' : ''; ?>>Admin</option>
                <option value="user" <?php echo ($role === 'user') ? 'selected' : ''; ?>>User</option>
            </select>
            <span class="error"><?php echo $roleErr; ?></span>
        </div>
        <button type="submit">Update User</button>
    </form>
</div>
</body>

</html>