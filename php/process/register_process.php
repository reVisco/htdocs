<?php
require 'session_start_process.php'; // Not strictly necessary for registration, but good practice
require 'db_connect.php';

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Input validation (basic example)
if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
  header("Location: ../register.php?error=Empty+fields!");
  exit();
}

// Check if passwords match
if ($password !== $confirm_password) {
  header("Location: ../register.php?error=Passwords+do+not+match!");
  exit();
}

// Hash the password securely
$hashed_password = password_hash($password, PASSWORD_DEFAULT);  // Hash using password_hash()

// Prepare SQL query using prepared statements for security
$stmt = $conn->prepare("INSERT INTO Users (username, email, password, registration_time) VALUES (?, ?, ?, NOW())");
$stmt->bind_param("sss", $username, $email, $hashed_password);  // Bind parameters to placeholders

if ($stmt->execute()) {
    // Registration successful
    header("Location: ../../index.php?message=Registration+successful!+Please+login.");
} else {
    // Registration failed
    header("Location: ../register.php?error=Registration+failed!");
    exit();
}

$stmt->close();  // Close the prepared statement
$conn->close();  // Close the database connection
