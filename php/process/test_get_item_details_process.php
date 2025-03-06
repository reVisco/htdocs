<?php
require 'test_db_connect.php';

if (isset($_POST['item_id'])) {
    $itemId = $_POST['item_id'];
    $stmt = $conn->prepare("SELECT item_id, item_details, serial_number, status FROM Items WHERE item_id = ?");
    $stmt->bind_param("i", $itemId);
    $stmt->execute();
    $result = $stmt->get_result();
    echo json_encode($result->fetch_assoc());
}

$conn->close();
?>