<?php
// Start session to check user authentication
session_start();

// Include header and nav bar
include_once '../components/user/user_header.php';
include_once '../components/user/user_nav.php';

// Include your PHP database connection file
include_once '../includes/conn.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'user') {
    // Redirect to login page if not logged in or not an admin
    header("Location: login.php");
    exit();
}

// Assuming $video_id is the ID of the video clicked by the user
$video_id = $_GET['video_id']; // Get the video ID from the URL

// Process form submissions for commenting and rating
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['rating'])) {
        // Handle rating submission
        $rating = $_POST['rating'];
        // Insert or update rating in the database
        $rating_query = "INSERT INTO rating (rating_video_id, rating) VALUES ($video_id, $rating)
                         ON DUPLICATE KEY UPDATE rating = VALUES(rating)";
        mysqli_query($conn, $rating_query);
    } elseif (isset($_POST['comment'])) {
        // Handle comment submission
        $comment = $_POST['comment'];
        // Insert comment into the database
        $comment_query = "INSERT INTO comment (comment_video_id, comment) VALUES ($video_id, '$comment')";
        mysqli_query($conn, $comment_query);
    }
}

// Fetch video details from the database
$video_query = "SELECT * FROM video WHERE video_id = $video_id";
$video_result = mysqli_query($conn, $video_query);
$video = mysqli_fetch_assoc($video_result);

// Count the number of users who have marked this video as watched
$view_count_query = "SELECT COUNT(*) AS view_count FROM progress WHERE progress_status = 1 AND progress_video_id = $video_id";
$view_count_result = mysqli_query($conn, $view_count_query);
$view_count_data = mysqli_fetch_assoc($view_count_result);
$view_count = $view_count_data['view_count'];

// Display video
echo "<iframe width='1080' height='480' src='" . $video['video_link'] . "' frameborder='0' allowfullscreen></iframe>";
echo "<h2>" . $video['video_title'] . "</h2>";
echo "<p>View Count: " . $view_count . "</p>";

// Fetch and display rating for the video
$rating_query = "SELECT AVG(rating) AS avg_rating FROM rating WHERE rating_video_id = $video_id";
$rating_result = mysqli_query($conn, $rating_query);
$rating_data = mysqli_fetch_assoc($rating_result);
echo "<p>Average Rating: " . round($rating_data['avg_rating'], 1) . "</p>";

// Display rating form
// This form will allow users to rate the video
echo "<form action='' method='post'>";
echo "<input type='hidden' name='video_id' value='" . $video_id . "'>";
echo "<label for='rating'>Rate this video:</label>";
echo "<select name='rating' id='rating'>";
echo "<option value='1'>1</option>";
echo "<option value='2'>2</option>";
echo "<option value='3'>3</option>";
echo "<option value='4'>4</option>";
echo "<option value='5'>5</option>";
echo "</select>";
echo "<button type='submit'>Rate</button>";
echo "</form>";

// Display video description
echo "<p>Description: " . $video['video_description'] . "</p>";

// Fetch and display comments for the video
$comment_query = "SELECT * FROM comment WHERE comment_video_id = $video_id";
$comment_result = mysqli_query($conn, $comment_query);

if (mysqli_num_rows($comment_result) > 0) {
    echo "<h3>Comments</h3>";
    while ($comment = mysqli_fetch_assoc($comment_result)) {
        echo "<p>" . $comment['comment'] . "</p>";
    }
} else {
    echo "<p>No comments yet.</p>";
}

// Display comment form
// This form will allow users to leave comments on the video
echo "<form action='' method='post'>";
echo "<input type='hidden' name='video_id' value='" . $video_id . "'>";
echo "<label for='comment'>Leave a comment:</label><br>";
echo "<textarea id='comment' name='comment' rows='4' cols='50'></textarea><br>";
echo "<button type='submit'>Comment</button>";
echo "</form>";

// Close database connection
mysqli_close($conn);
