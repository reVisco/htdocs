<?php
require 'db_connect.php';

// Get username and password from the form
$username = trim($_POST['username']);
$password = trim($_POST['password']);

// Prepare SQL query with placeholders
$sql = "SELECT * FROM Users WHERE username = ?";
$stmt = $conn->prepare($sql);

// Bind parameter to the placeholder
$stmt->bind_param("s", $username);

// Execute the prepared statement
$stmt->execute();

$result = $stmt->get_result(); // Get the result set

if ($result->num_rows > 0) {
  // User found
  $user = $result->fetch_assoc();  // Fetch user data as associative array

  // Verify password using password_verify()
  if (password_verify($password, $user['password'])) {
    // Login successful
    session_start();
    $_SESSION['loggedIn'] = true; // Set the session variable to indicate logged in
    $_SESSION['username'] = $username;
    $_SESSION['user_id'] = $user['user_id'];
    header("Location: ../admin_dashboard.php");  // Redirect to dashboard
  } else {
    // Password mismatch
    header("Location: ../../index.php?error=incorrect_password&message=" . urlencode("Incorrect password. Please try again."));
  }
} else {
  // Username not found
  header("Location: ../../index.php?error=invalid_username&message=" . urlencode("Username not found. Please check your username or register."));
}

$stmt->close();  // Close the prepared statement
$conn->close();  // Close the database connection
