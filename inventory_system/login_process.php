<?php
session_start();

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

// Get username and password from the form
$username = $_POST['username'];
$password = $_POST['password'];

// Prepare SQL query to fetch user data
$sql = "SELECT * FROM Users WHERE username = '$username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // User found
  $user = $result->fetch_assoc();  // Fetch user data as associative array

  // Verify password using password_verify()
  if (password_verify($password, $user['password'])) {
    // Login successful
    $_SESSION['username'] = $username;
    header("Location: admin_dashboard.php");  // Redirect to dashboard
  } else {
    // Password mismatch
    header("Location: ../index.php?error=Invalid+credentials");
  }
} else {
  // Username not found
  header("Location: ../index.php?error=Invalid+credentials");
}

$conn->close();
?>
