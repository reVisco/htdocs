<?php
require_once 'db_connect.php';

// Set header to indicate JSON response
header('Content-Type: application/json');

// Get JSON data from the request
$json_data = file_get_contents('php://input');
$data = json_decode($json_data, true);

if (!isset($data['item_ids']) || empty($data['item_ids'])) {
    echo json_encode(['success' => false, 'message' => 'No items selected for deletion']);
    exit;
}

$item_ids = $data['item_ids'];
$success = true;
$deleted_items = [];
$error_messages = [];

// Start transaction
$conn->begin_transaction();

try {
    foreach ($item_ids as $item_id) {
        // First, get the item details for QR code deletion
        $stmt = $conn->prepare("SELECT item_name FROM items WHERE item_id = ?");
        $stmt->bind_param('i', $item_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $item = $result->fetch_assoc();
        
        if ($item) {
            // First delete from inventory table
            $delete_inventory_stmt = $conn->prepare("DELETE FROM inventory WHERE item_id = ?");
            $delete_inventory_stmt->bind_param('i', $item_id);
            $delete_inventory_stmt->execute();
            $delete_inventory_stmt->close();
            
            // Then delete the item from items table
            $delete_stmt = $conn->prepare("DELETE FROM items WHERE item_id = ?");
            $delete_stmt->bind_param('i', $item_id);
            
            if ($delete_stmt->execute()) {
                $deleted_items[] = $item_id;
                
                // Delete associated QR code if it exists
                $qr_code_path = "../qr_codes/{$item_id}-{$item['item_name']}.png";
                if (file_exists($qr_code_path)) {
                    unlink($qr_code_path);
                }
            } else {
                $error_messages[] = "Failed to delete item ID: $item_id";
                $success = false;
            }
            
            $delete_stmt->close();
        } else {
            $error_messages[] = "Item ID $item_id not found";
            $success = false;
        }
        
        $stmt->close();
    }
    
    if ($success) {
        $conn->commit();
        echo json_encode([
            'success' => true,
            'message' => 'Items deleted successfully',
            'deleted_items' => $deleted_items
        ]);
    } else {
        throw new Exception(implode(", ", $error_messages));
    }
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

$conn->close();