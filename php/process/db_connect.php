<?php
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
