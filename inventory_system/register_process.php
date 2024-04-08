<?php
session_start(); // Not strictly necessary for registration, but good practice

// Database connection details (replace with your actual credentials)
$servername = "localhost";
$username = "admin";
$password = "aw!KS7g2J/dA]dRW";
$dbname = "ict_assets_inventory";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Get user data from the form
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

// Input validation (basic example)
if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
  header("Location: register.php?error=Empty+fields!");
  exit();
}

// Check if passwords match
if ($password !== $confirm_password) {
  header("Location: register.php?error=Passwords+do+not+match!");
  exit();
}

// Hash the password securely
$hashed_password = password_hash($password, PASSWORD_DEFAULT);  // Hash using password_hash()

// Prepare SQL query to insert new user
$sql = "INSERT INTO Users (username, email, password, registration_time) VALUES ('$username', '$email', '$hashed_password', NOW())";

if ($conn->query($sql) === TRUE) {
  // Registration successful
  header("Location: login.php?message=Registration+successful!+Please+login.");  // Redirect to login with success message
} else {
  // Registration failed (e.g., username or email might already exist)
  header("Location: register.php?error=Registration+failed!");
  exit();
}

$conn->close();
?>
