<?php
// Start session to check user authentication
session_start();

// Include header and nav bar
include_once '../components/user/user_header.php';
include_once '../components/user/user_nav.php';

// Include your PHP database connection file
include_once '../includes/conn.php';

// Check if user is logged in and is a user
if (!isset($_SESSION['login_id']) || $_SESSION['login_user_role'] !== 'user') {
    // Redirect to login page if not logged in or not a user
    header("Location: login.php");
    exit();
}


?>
<h2>User Homapage</h2>

<div class="content">
    <div class="categories">
        <section class="category-section">
            <?php
            // Fetch modules from the database
            $module_query = "SELECT * FROM module";
            $module_result = mysqli_query($conn, $module_query);

            // Display module buttons
            while ($module = mysqli_fetch_assoc($module_result)) {
                echo "<button class='category' onclick=\"location.href='user_module.php?module_id=" . $module['module_id'] . "'\">" . $module['module_name'] . "</button>";
            }
            ?>
        </section>
    </div>
    <div class="videos">
        <section class="video-section">
            <?php
            // Fetch videos from the database ordered by ID
            $video_query = "SELECT * FROM video ORDER BY video_id";
            $video_result = mysqli_query($conn, $video_query);

            // Display videos
            while ($video = mysqli_fetch_assoc($video_result)) {
                echo "<article class='video-container'>";
                echo "<a href='user_video.php?video_id=" . $video['video_id'] . "' class='thumbnail' data-duration=" . $video['video_duration'] . ">";
                echo "<img class='thumbnail-image' src='" . $video['video_thumbnail'] . "' />";
                echo "</a>";
                echo "<div class='video-bottom-section'>";
                echo "<div class='video-details'>";
                echo "<a href='user_video.php?video_id=" . $video['video_id'] . "' class='video-title'>" . $video['video_title'] . "</a>";
                echo "<div class='video-metadata'>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
                echo "</article>";
            }
            ?>
        </section>
    </div>
</div>
</body>


</html>