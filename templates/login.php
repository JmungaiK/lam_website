<!-- login.php -->
<?php
// Include the database connection file
require_once '../includes/conn.php';

// Initialize variables to store user input and error messages
$email = $password = '';
$emailErr = $passwordErr = '';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate user input
    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } else {
        $email = $_POST["email"];
    }

    if (empty($_POST["password"])) {
        $passwordErr = "Password is required";
    } else {
        $password = $_POST["password"];
    }

    // If there are no input errors, proceed with login
    if (empty($emailErr) && empty($passwordErr)) {
        // Prepare and execute SQL statement to fetch user data
        $sql = "SELECT * FROM login WHERE login_user_email='$email'";
        $result = $conn->query($sql);

        if ($result->num_rows == 1) {
            // User found, verify password
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['login_password'])) {
                // Password is correct, set session variables and redirect based on user role
                session_start();
                $_SESSION['login_id'] = $row['login_id'];
                $_SESSION['login_user_name'] = $row['login_user_name'];
                $_SESSION['login_user_role'] = $row['login_user_role'];

                // Redirect based on user role
                if ($row['login_user_role'] == 'admin') {
                    header("Location: admin_home.php");
                } else {
                    header("Location: user_home.php");
                }
                exit();
            } else {
                // Password is incorrect
                $passwordErr = "Incorrect password";
            }
        } else {
            // User not found
            $emailErr = "User not found";
        }
    }
}


// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>

<body>
    <div class="container">
        <h2>Login</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
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
            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="register.php">Register</a></p>
    </div>
</body>

</html>