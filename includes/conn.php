<?php

// Database connection parameters
$servername = "localhost";
$username = "root"; // Your MySQL username
$password = ""; // Your MySQL password
$database = "money_website"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
