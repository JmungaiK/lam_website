<?php
// Include your PHP database connection file
include_once 'conn.php';

// Function to handle rating a video
function rateVideo($video_id, $rating)
{
    global $conn;

    // Sanitize input
    $video_id = mysqli_real_escape_string($conn, $video_id);
    $rating = mysqli_real_escape_string($conn, $rating);

    // Insert or update rating in the database
    $query = "INSERT INTO rating (rating_video_id, rating) VALUES ($video_id, $rating)
              ON DUPLICATE KEY UPDATE rating = VALUES(rating)";
    mysqli_query($conn, $query);
}

// Function to handle commenting on a video
function commentVideo($video_id, $comment)
{
    global $conn;

    // Sanitize input
    $video_id = mysqli_real_escape_string($conn, $video_id);
    $comment = mysqli_real_escape_string($conn, $comment);

    // Insert comment into the database
    $query = "INSERT INTO comment (comment_video_id, comment) VALUES ($video_id, '$comment')";
    mysqli_query($conn, $query);
}
