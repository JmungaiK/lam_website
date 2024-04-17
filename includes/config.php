<?php

// Database connection parameters
$servername = ""; // Your database server name
$username = ""; // Your database username
$password = ""; // Your database password

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
