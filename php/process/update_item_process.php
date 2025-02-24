<?php
require_once 'db_connect.php';

// Set the response header to JSON
header('Content-Type: application/json');

// Check if all required fields are present
if (!isset($_POST['item_id']) || !isset($_POST['item_name']) || !isset($_POST['property'])) {
    echo json_encode(['success' => false, 'message' => 'Required fields are missing']);
    exit;
}

// Prepare the update query
$sql = "UPDATE items SET 
        item_name = ?,
        property = ?,
        order_batch_number = ?,
        model_number = ?,
        serial_number = ?,
        warranty_coverage = ?,
        brand = ?,
        item_type = ?,
        item_details = ?,
        status_description = ?,
        unit_price = ?,
        justification_of_purchase = ?,
        delivery_date = ?,
        supplier_name = ?,
        po_number = ?,
        po_date = ?,
        pr_number = ?,
        invoice_no = ?,
        delivery_receipt = ?,
        items_received_by = ?,
        remarks = ?
        WHERE item_id = ?";

$stmt = $conn->prepare($sql);

if ($stmt === false) {
    echo json_encode(['success' => false, 'message' => 'Error preparing statement: ' . $conn->error]);
    exit;
}

// Bind parameters
$stmt->bind_param('ssssssssssdssssssssssi',
    $_POST['item_name'],
    $_POST['property'],
    $_POST['order_batch_number'],
    $_POST['model_number'],
    $_POST['serial_number'],
    $_POST['warranty_coverage'],
    $_POST['brand'],
    $_POST['item_type'],
    $_POST['item_details'],
    $_POST['status_description'],
    $_POST['unit_price'],
    $_POST['justification_of_purchase'],
    $_POST['delivery_date'],
    $_POST['supplier_name'],
    $_POST['po_number'],
    $_POST['po_date'],
    $_POST['pr_number'],
    $_POST['invoice_no'],
    $_POST['delivery_receipt'],
    $_POST['items_received_by'],
    $_POST['remarks'],
    $_POST['item_id']
);

// Execute the statement
if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error updating item: ' . $stmt->error]);
}

$stmt->close();
$conn->close();