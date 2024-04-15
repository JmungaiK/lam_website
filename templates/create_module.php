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
$name = '';
$description = '';
$nameErr = '';
$descriptionErr = '';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate form data
    if (empty($_POST["name"])) {
        $nameErr = "Name is required";
    } else {
        $name = $_POST["name"];
    }

    if (empty($_POST["description"])) {
        $descriptionErr = "Description is required";
    } else {
        $description = $_POST["description"];
    }

    // If there are no input errors, proceed with creating module
    if (empty($nameErr) && empty($descriptionErr)) {
        // Prepare and execute SQL statement to insert module data into the database
        $sql = "INSERT INTO module (module_name, module_description) VALUES ('$name', '$description')";

        if ($conn->query($sql) === TRUE) {
            // Module created successfully, redirect to admin dashboard or module list page
            header("Location: admin_modules.php");
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
    <h2>Create Module</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo $name; ?>">
            <span class="error"><?php echo $nameErr; ?></span>
        </div>
        <div class="form-group">
            <label for="description">Description:</label>
            <textarea id="description" name="description"><?php echo $description; ?></textarea>
            <span class="error"><?php echo $descriptionErr; ?></span>
        </div>
        <button type="submit">Create Module</button>
    </form>
</div>
</body>

</html>