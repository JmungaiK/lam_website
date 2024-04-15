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
$name = $email = $password = $role = '';
$nameErr = $emailErr = $passwordErr = '';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate form data
    if (empty($_POST["name"])) {
        $nameErr = "Name is required";
    } else {
        $name = $_POST["name"];
    }

    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } else {
        $email = $_POST["email"];
        // Check if email is valid
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        }
    }

    if (empty($_POST["password"])) {
        $passwordErr = "Password is required";
    } else {
        $password = $_POST["password"];
    }

    // Assign the selected role from the form to the $role variable
    $role = $_POST["role"];

    // If there are no input errors, proceed with creating user
    if (empty($nameErr) && empty($emailErr) && empty($passwordErr)) {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare and execute SQL statement to insert user data into the database
        $sql = "INSERT INTO user (user_name, user_email, user_role, user_password) VALUES ('$name', '$email', '$role', '$hashed_password')";

        if ($conn->query($sql) === TRUE) {
            // User created successfully, redirect to admin dashboard or user list page
            header("Location: admin_users.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

// Close database connection
$conn->close();
?>


<div class="container">
    <h2>Create User</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo $name; ?>">
            <span class="error"><?php echo $nameErr; ?></span>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo $email; ?>">
            <span class="error"><?php echo $emailErr; ?></span>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password">
            <span class="error"><?php echo $passwordErr; ?></span>
        </div>
        <div class="form-group">
            <label for="role">Role:</label>
            <select id="role" name="role">
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>
        </div>
        <button type="submit">Create User</button>
    </form>
</div>
</body>

</html>