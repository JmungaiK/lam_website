<?php

// Database connection parameters
$servername = ""; // Your database server name
$username = ""; // Your database username
$password = ""; // Your database password
$database = ""; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
