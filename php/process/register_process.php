<?php
session_start();
require 'db_connect.php';

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Get form data
$first_name = trim($_POST['first_name']);
$last_name = trim($_POST['last_name']);
$username = trim($_POST['username']);
$email = trim($_POST['email']);
$password = trim($_POST['password']);
$confirm_password = trim($_POST['confirm_password']);

// Input validation with specific error messages
if (empty($first_name)) {
    $_SESSION['form_data'] = [
        'last_name' => $last_name,
        'username' => $username,
        'email' => $email
    ];
    header("Location: ../register.php?error=First+name+is+required");
    exit();
} elseif (empty($last_name)) {
    $_SESSION['form_data'] = [
        'first_name' => $first_name,
        'username' => $username,
        'email' => $email
    ];
    header("Location: ../register.php?error=Last+name+is+required");
    exit();
} elseif (empty($username)) {
    $_SESSION['form_data'] = [
        'first_name' => $first_name,
        'last_name' => $last_name,
        'email' => $email
    ];
    header("Location: ../register.php?error=Username+is+required");
    exit();
} elseif (empty($email)) {
    $_SESSION['form_data'] = [
        'first_name' => $first_name,
        'last_name' => $last_name,
        'username' => $username
    ];
    header("Location: ../register.php?error=Email+is+required");
    exit();
} elseif (empty($password)) {
    header("Location: ../register.php?error=Password+is+required");
    exit();
} elseif (empty($confirm_password)) {
    header("Location: ../register.php?error=Please+confirm+your+password");
    exit();
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['form_data'] = [
        'first_name' => $first_name,
        'last_name' => $last_name,
        'username' => $username
    ];
    header("Location: ../register.php?error=Invalid+email+format");
    exit();
}

// Check password length
if (strlen($password) < 8) {
    header("Location: ../register.php?error=Password+must+be+at+least+8+characters+long");
    exit();
}

// Check if passwords match
if ($password !== $confirm_password) {
    header("Location: ../register.php?error=Passwords+do+not+match");
    exit();
}

// Hash the password securely
$hashed_password = password_hash($password, PASSWORD_DEFAULT);  // Hash using password_hash()

// Prepare SQL query using prepared statements for security
// First, alter the table to add new columns if they don't exist

// Prepare SQL query using prepared statements for security
$stmt = $conn->prepare("INSERT INTO users (username, email, password, registration_time, first_name, last_name) VALUES (?, ?, ?, NOW(), ?, ?)");
$stmt->bind_param("sssss", $username, $email, $hashed_password, $first_name, $last_name);  // Bind parameters to placeholders

// Check if username already exists
$check_username = $conn->prepare("SELECT username FROM users WHERE username = ?");
$check_username->bind_param("s", $username);
$check_username->execute();
$username_result = $check_username->get_result();

if ($username_result->num_rows > 0) {
    $_SESSION['form_data'] = [
        'first_name' => $first_name,
        'last_name' => $last_name,
        'email' => $email
    ];
    header("Location: ../register.php?error=Username+already+exists");
    exit();
}
$check_username->close();

// Check if email already exists
$check_email = $conn->prepare("SELECT email FROM users WHERE email = ?");
$check_email->bind_param("s", $email);
$check_email->execute();
$email_result = $check_email->get_result();

if ($email_result->num_rows > 0) {
    $_SESSION['form_data'] = [
        'first_name' => $first_name,
        'last_name' => $last_name,
        'username' => $username
    ];
    header("Location: ../register.php?error=Email+already+registered");
    exit();
}
$check_email->close();

if ($stmt->execute()) {
    // Registration successful
    // Clear session data
    unset($_SESSION['form_data']);
    // Registration successful
    header("Location: ../../index.php?message=Registration+successful!+Please+login.");
} else {
    // Registration failed
    $error_message = $stmt->error;
    header("Location: ../register.php?error=Registration+failed:+" . urlencode($error_message));
    exit();
}

$stmt->close();  // Close the prepared statement
$conn->close();  // Close the database connection
