<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ict_inventory_updated";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
