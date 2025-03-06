<?php
require 'test_db_connect.php'; // Your database connection file

$username = "john_doe";
$first_name = "John";
$last_name = "Doe";
$email = "john.doe@example.com";
$password = password_hash("securepassword123", PASSWORD_DEFAULT); // Hash the password

$stmt = $conn->prepare("INSERT INTO users (username, first_name, last_name, email, password, registration_time) VALUES (?, ?, ?, ?, ?, NOW())");
$stmt->bind_param("sssss", $username, $first_name, $last_name, $email, $password);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "User added successfully!";
} else {
    echo "Error adding user: " . $conn->error;
}

$stmt->close();
$conn->close();
?>