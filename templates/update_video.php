<?php

// Start session to check user authentication
session_start();

// Include the admin header
include_once '../components/admin/admin_header.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['login_id']) || $_SESSION['login_user_role'] !== 'admin') {
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


// Check if video ID is provided in the URL
if (isset($_GET['video_id']) && !empty($_GET['video_id'])) {
    $video_id = $_GET['video_id'];

    // Retrieve video data from the database
    $sql = "SELECT * FROM video WHERE video_id = '$video_id'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $title = $row['video_title'];
        $duration = $row['video_duration'];
        $thumbnail = $row['video_thumbnail'];
        $link = $row['video_link'];
        $description = $row['video_description'];
        $module_id = $row['video_module_id'];
    } else {
        // Video not found
        echo "Video not found.";
        exit();
    }
} else {
    // Video ID not provided
    echo "Video ID not provided.";
    exit();
}

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

    // If there are no input errors, proceed with updating video
    if (empty($titleErr) && empty($durationErr) && empty($thumbnailErr) && empty($linkErr) && empty($descriptionErr) && empty($module_idErr)) {
        // Prepare and execute SQL statement to update video data in the database
        $sql = "UPDATE video SET video_title='$title', video_duration='$duration', video_thumbnail='$thumbnail', video_link='$link', video_description='$description', video_module_id='$module_id' WHERE video_id='$video_id'";

        if ($conn->query($sql) === TRUE) {
            // If video updated successfully, redirect to admin dashboard or video list page
            header("Location: admin_videos.php");
            exit();
        } else {
            echo "Error updating video: " . $conn->error;
        }
    }
}



// Close database connection
$conn->close();
?>




<div class="container">
    <h2>Update Video</h2>
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
                    $selected = ($row_module['module_id'] == $module_id) ? 'selected' : '';
                    echo "<option value='" . $row_module['module_id'] . "' $selected>" . $row_module['module_name'] . "</option>";
                }
                ?>
            </select>
            <span class="error"><?php echo $module_idErr; ?></span>
        </div>
        <input type="hidden" name="video_id" value="<?php echo $video_id; ?>">
        <button type="submit">Update Video</button>
    </form>
</div>
</body>

</html>