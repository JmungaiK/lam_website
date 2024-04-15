<?php

require_once 'conn.php';

// Create database if not exists
$sql = "CREATE DATABASE IF NOT EXISTS $database";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully\n";
} else {
    echo "Error creating database: " . $conn->error . "\n";
}

// Select the database
$conn->select_db($database);

// SQL statements to create tables
$sql = "CREATE TABLE IF NOT EXISTS user (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    user_name VARCHAR(255) NOT NULL,
    user_email VARCHAR(255) NOT NULL,
    user_role ENUM('user', 'admin') NOT NULL DEFAULT 'user',
    user_password VARCHAR(255) NOT NULL
);";

$sql .= "CREATE TABLE IF NOT EXISTS module (
    module_id INT AUTO_INCREMENT PRIMARY KEY,
    module_name VARCHAR(255) NOT NULL,
    module_description TEXT
);";

$sql .= "CREATE TABLE IF NOT EXISTS video (
    video_id INT AUTO_INCREMENT PRIMARY KEY,
    video_title VARCHAR(255) NOT NULL,
    video_duration TIME NOT NULL,
    video_thumbnail VARCHAR(255) NOT NULL,
    video_link VARCHAR(255) NOT NULL,
    video_description TEXT,
    video_module_id INT,
    FOREIGN KEY (video_module_id) REFERENCES module(module_id)
);";

$sql .= "CREATE TABLE IF NOT EXISTS comment (
    comment_id INT AUTO_INCREMENT PRIMARY KEY,
    comment TEXT NOT NULL,
    comment_user_id INT NOT NULL,
    comment_video_id INT NOT NULL,
    FOREIGN KEY (comment_user_id) REFERENCES user(user_id),
    FOREIGN KEY (comment_video_id) REFERENCES video(video_id)
);";

$sql .= "CREATE TABLE IF NOT EXISTS rating (
    rating_id INT AUTO_INCREMENT PRIMARY KEY,
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    rating_user_id INT NOT NULL,
    rating_video_id INT NOT NULL,
    FOREIGN KEY (rating_user_id) REFERENCES user(user_id),
    FOREIGN KEY (rating_video_id) REFERENCES video(video_id)
);";

$sql .= "CREATE TABLE IF NOT EXISTS progress (
    progress_id INT AUTO_INCREMENT PRIMARY KEY,
    progress_status BOOLEAN NOT NULL DEFAULT false,
    progress_user_id INT NOT NULL,
    progress_video_id INT NOT NULL,
    FOREIGN KEY (progress_user_id) REFERENCES user(user_id),
    FOREIGN KEY (progress_video_id) REFERENCES video(video_id)
);";

// Execute SQL statements
if ($conn->multi_query($sql) === TRUE) {
    echo "Tables created successfully\n";
} else {
    echo "Error creating tables: " . $conn->error . "\n";
}
