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
$module_name = $module_description = '';
$module_nameErr = $module_descriptionErr = '';

// Check if module ID is provided in the URL
if (isset($_GET['module_id']) && !empty($_GET['module_id'])) {
    $module_id = $_GET['module_id'];

    // Retrieve module data from the database
    $sql = "SELECT * FROM module WHERE module_id = '$module_id'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $module_name = $row['module_name'];
        $module_description = $row['module_description'];
    } else {
        // Module not found
        echo "Module not found.";
        exit();
    }
} else {
    // Module ID not provided
    echo "Module ID not provided.";
    exit();
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate form data
    if (empty($_POST["module_name"])) {
        $module_nameErr = "Module Name is required";
    } else {
        $module_name = $_POST["module_name"];
    }

    if (empty($_POST["module_description"])) {
        $module_descriptionErr = "Module Description is required";
    } else {
        $module_description = $_POST["module_description"];
    }

    // If there are no input errors, proceed with updating module
    if (empty($module_nameErr) && empty($module_descriptionErr)) {
        // Prepare and execute SQL statement to update module data in the database
        $sql = "UPDATE module SET module_name='$module_name', module_description='$module_description' WHERE module_id='$module_id'";

        if ($conn->query($sql) === TRUE) {
            // Module updated successfully, redirect to admin module page
            header("Location: admin_modules.php");
            exit();
        } else {
            echo "Error updating modules: " . $conn->error;
        }
    }
}

// Close database connection
$conn->close();
?>


<div class="container">
    <h2>Update Module</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="form-group">
            <label for="module_name">Module Name:</label>
            <input type="text" id="module_name" name="module_name" value="<?php echo $module_name; ?>">
            <span class="error"><?php echo $module_nameErr; ?></span>
        </div>
        <div class="form-group">
            <label for="module_description">Module Description:</label>
            <textarea id="module_description" name="module_description"><?php echo $module_description; ?></textarea>
            <span class="error"><?php echo $module_descriptionErr; ?></span>
        </div>
        <button type="submit">Update Module</button>
    </form>
</div>
</body>

</html>