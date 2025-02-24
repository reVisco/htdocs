<?php
require 'session_start_process.php';
require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['user_id'];
    $firstName = trim($_POST['first_name']);
    $lastName = trim($_POST['last_name']);

    // Basic validation
    if (empty($firstName) || empty($lastName)) {
        header('Location: ../user_profile.php?error=All fields are required');
        exit;
    }

    // Prepare the update statement
    $stmt = $conn->prepare("UPDATE users SET first_name = ?, last_name = ? WHERE user_id = ?");
    $stmt->bind_param("ssi", $firstName, $lastName, $userId);

    // Execute the update
    if ($stmt->execute()) {
        header('Location: ../user_profile.php?success=Profile updated successfully');
    } else {
        header('Location: ../user_profile.php?error=Failed to update profile');
    }

    $stmt->close();
    $conn->close();
} else {
    header('Location: ../user_profile.php');
}