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
$title = $duration = $thumbnail = $link = $description = $module_id = '';
$titleErr = $durationErr = $thumbnailErr = $linkErr = $descriptionErr = $module_idErr = '';

// Fetch module data for dropdown
$sql_module = "SELECT * FROM module";
$result_module = $conn->query($sql_module);

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate form data
    if (empty($_POST["title"])) {
        $titleErr = "Title is required";
    } else {
        $title = $_POST["title"];
    }

    if (empty($_POST["duration"])) {
        $durationErr = "Duration is required";
    } else {
        $duration = $_POST["duration"];
    }

    if (empty($_POST["thumbnail"])) {
        $thumbnailErr = "Thumbnail is required";
    } else {
        $thumbnail = $_POST["thumbnail"];
    }

    if (empty($_POST["link"])) {
        $linkErr = "Link is required";
    } else {
        $link = $_POST["link"];
    }

    if (empty($_POST["description"])) {
        $descriptionErr = "Description is required";
    } else {
        $description = $_POST["description"];
    }

    if (empty($_POST["module_id"])) {
        $module_idErr = "Module is required";
    } else {
        $module_id = $_POST["module_id"];
    }

    // If there are no input errors, proceed with creating video
    if (empty($titleErr) && empty($durationErr) && empty($thumbnailErr) && empty($linkErr) && empty($descriptionErr) && empty($module_idErr)) {
        // Prepare and execute SQL statement to insert video data into the database
        $sql = "INSERT INTO video (video_title, video_duration, video_thumbnail, video_link, video_description, video_module_id) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssi", $title, $duration, $thumbnail, $link, $description, $module_id);

        if ($stmt->execute()) {
            // Video created successfully, redirect to admin dashboard or video list page
            header("Location: admin_videos.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $stmt->error;
        }
        $stmt->close();
    }
}

// Close database connection
$conn->close();
?>


<div class="container">
    <h2>Create Video</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="form-group">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" value="<?php echo $title; ?>">
            <span class="error"><?php echo $titleErr; ?></span>
        </div>
        <div class="form-group">
            <label for="duration">Duration:</label>
            <input type="text" id="duration" name="duration" value="<?php echo $duration; ?>">
            <span class="error"><?php echo $durationErr; ?></span>
        </div>
        <div class="form-group">
            <label for="thumbnail">Thumbnail:</label>
            <input type="text" id="thumbnail" name="thumbnail" value="<?php echo $thumbnail; ?>">
            <span class="error"><?php echo $thumbnailErr; ?></span>
        </div>
        <div class="form-group">
            <label for="link">Link:</label>
            <input type="text" id="link" name="link" value="<?php echo $link; ?>">
            <span class="error"><?php echo $linkErr; ?></span>
        </div>
        <div class="form-group">
            <label for="description">Description:</label>
            <textarea id="description" name="description"><?php echo $description; ?></textarea>
            <span class="error"><?php echo $descriptionErr; ?></span>
        </div>
        <div class="form-group">
            <label for="module_id">Module:</label>
            <select id="module_id" name="module_id">
                <option value="">Select Module</option>
                <?php
                while ($row_module = $result_module->fetch_assoc()) {
                    echo "<option value='" . $row_module['module_id'] . "'>" . $row_module['module_name'] . "</option>";
                }
                ?>
            </select>
            <span class="error"><?php echo $module_idErr; ?></span>
        </div>
        <button type="submit">Create Video</button>
    </form>
</div>
</body>

</html>