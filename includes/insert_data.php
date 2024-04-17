<?php

// Include the database connection file
require_once 'conn.php';

// Sample user data
$users = array(
    array('John Doe', 'john@example.com', 'user', 'password123'),
    array('Jane Smith', 'jane@example.com', 'admin', 'admin123')
);

// SQL statement to insert users into the table
$sql = "INSERT INTO login (login_user_name, login_user_email, login_user_role, login_password) VALUES ";

foreach ($users as $user) {
    $name = $user[0];
    $email = $user[1];
    $role = $user[2];
    $password = password_hash($user[3], PASSWORD_DEFAULT); // Hash the password

    $sql .= "('$name', '$email', '$role', '$password'),";
}

// Remove the trailing comma
$sql = rtrim($sql, ',');

// Execute the SQL statement
if ($conn->query($sql) === TRUE) {
    echo "Sample users inserted successfully\n";
} else {
    echo "Error inserting sample users: " . $conn->error . "\n";
}

// Close connection
$conn->close();
