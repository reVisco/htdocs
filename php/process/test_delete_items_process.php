<?php
require 'test_db_connect.php';

if (isset($_POST['item_ids']) && is_array($_POST['item_ids'])) {
    $itemIds = $_POST['item_ids'];

    try {
        $conn->begin_transaction();

        $stmt = $conn->prepare("DELETE FROM Item_Purchases WHERE item_id IN (" . implode(',', array_fill(0, count($itemIds), '?')) . ")");
        $stmt->bind_param(str_repeat('i', count($itemIds)), ...$itemIds);
        $stmt->execute();

        $stmt = $conn->prepare("DELETE FROM Item_Issuances WHERE item_id IN (" . implode(',', array_fill(0, count($itemIds), '?')) . ")");
        $stmt->bind_param(str_repeat('i', count($itemIds)), ...$itemIds);
        $stmt->execute();

        $stmt = $conn->prepare("DELETE FROM Warranties WHERE item_id IN (" . implode(',', array_fill(0, count($itemIds), '?')) . ")");
        $stmt->bind_param(str_repeat('i', count($itemIds)), ...$itemIds);
        $stmt->execute();

        $stmt = $conn->prepare("DELETE FROM Items WHERE item_id IN (" . implode(',', array_fill(0, count($itemIds), '?')) . ")");
        $stmt->bind_param(str_repeat('i', count($itemIds)), ...$itemIds);
        $stmt->execute();

        $conn->commit();
        echo "Successfully deleted " . $stmt->affected_rows . " items.";
    } catch (Exception $e) {
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }
}

$conn->close();
?>