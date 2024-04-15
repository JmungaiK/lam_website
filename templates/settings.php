<?php
// Include the database connection file
require_once '../includes/conn.php';

// Start session to access user data
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}

// Check user role
if ($_SESSION['user_role'] === 'admin') {
    // Include admin header and nav bar
    include_once '../components/admin/admin_header.php';
    include_once '../components/admin/admin_nav.php';
} else {
    // Include user header and nav bar
    include_once '../components/user/user_header.php';
    include_once '../components/user/user_nav.php';
}

// Fetch user data from the database
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM user WHERE user_id = '$user_id'";
$result = $conn->query($sql);
if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $current_name = $row['user_name'];
    $current_email = $row['user_email'];
} else {
    // User not found
    echo "User not found.";
    exit();
}

// Initialize variables for form data and error messages
$new_name = $new_email = $current_password = $new_password = '';
$nameErr = $emailErr = $passwordErr = $confirmPasswordErr = '';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle name update
    if (!empty($_POST["new_name"])) {
        $new_name = $_POST["new_name"];
        // Validate name
        // Update name in the database
        $sql = "UPDATE user SET name = '$new_name' WHERE user_id = '$user_id'";
        if ($conn->query($sql) === TRUE) {
            // Name updated successfully
            $current_name = $new_name;
        } else {
            // Error updating name
            echo "Error updating name: " . $conn->error;
        }
    }

    // Handle email update
    if (!empty($_POST["new_email"])) {
        $new_email = $_POST["new_email"];
        // Validate email
        if (!filter_var(
            $new_email,
            FILTER_VALIDATE_EMAIL
        )) {
            $emailErr = "Invalid email format";
        } else {
            // Update email in the database
            $sql = "UPDATE user SET email = '$new_email' WHERE user_id = '$user_id'";
            if ($conn->query($sql) === TRUE) {
                // Email updated successfully
                $current_email = $new_email;
            } else {
                // Error updating email
                echo "Error updating email: " . $conn->error;
            }
        }
    }

    // Handle password change
    if (!empty($_POST["current_password"]) && !empty($_POST["new_password"])) {
        $current_password = $_POST["current_password"];
        $new_password = $_POST["new_password"];
        // Validate current password
        if (password_verify($current_password, $row['password'])) {
            // Hash and update new password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $sql = "UPDATE user SET password = '$hashed_password' WHERE user_id = '$user_id'";
            if ($conn->query($sql) === TRUE) {
                // Password updated successfully
            } else {
                // Error updating password
                echo "Error updating password: " . $conn->error;
            }
        } else {
            // Incorrect current password
            $passwordErr = "Incorrect current password";
        }
    }

    // Handle account deletion
    if (isset($_POST["delete_account"])) {
        // Delete user account from the database
        $sql = "DELETE FROM user WHERE user_id = '$user_id'";
        if ($conn->query($sql) === TRUE) {
            // User account deleted successfully
            // Redirect to logout or home page
            header("Location: logout.php"); // Assuming you have a logout script
            exit();
        } else {
            // Error deleting user account
            echo "Error deleting user account: " . $conn->error;
        }
    }
}

// Close database connection
$conn->close();
?>

<body>
    <hr>
    <div class="settings-container">
        <h2>User Settings</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label for="new_name">New Name:</label>
                <input type="text" id="new_name" name="new_name" value="<?php echo $current_name; ?>">
            </div>
            <div class="form-group">
                <label for="new_email">New Email:</label>
                <input type="email" id="new_email" name="new_email" value="<?php echo $current_email; ?>">
            </div>
            <div class="form-group">
                <label for="current_password">Current Password:</label>
                <input type="password" id="current_password" name="current_password">
                <span class="error"><?php echo $passwordErr; ?></span>
            </div>
            <div class="form-group">
                <label for="new_password">New Password:</label>
                <input type="password" id="new_password" name="new_password">
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm New Password:</label>
                <input type="password" id="confirm_password" name="confirm_password">
                <span class="error"><?php echo $confirmPasswordErr; ?></span>
            </div>
            <div class="form-group">
                <input type="checkbox" id="delete_account" name="delete_account">
                <label for="delete_account">Delete Account</label>
            </div>
            <button type="submit" name="submit">Save Changes</button>
        </form>
        <p>Need to <a href="logout.php">logout</a>?</p> <!-- Link to logout script -->
    </div>
</body>

</html>