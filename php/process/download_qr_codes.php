<?php
require_once 'db_connect.php';
require_once 'session_start_process.php';

if (!isset($_POST['item_ids'])) {
    header('Location: ../view_items.php');
    exit;
}

$item_ids = json_decode($_POST['item_ids']);

if (empty($item_ids)) {
    header('Location: ../view_items.php');
    exit;
}

// Create a temporary directory for the zip file
$temp_dir = sys_get_temp_dir();
$zip_file = $temp_dir . '/qr_codes_' . time() . '.zip';

$zip = new ZipArchive();
if ($zip->open($zip_file, ZipArchive::CREATE) !== TRUE) {
    die('Could not create ZIP file');
}

// Get item details and add QR codes to the zip file
try {
    foreach ($item_ids as $item_id) {
        $stmt = $conn->prepare("SELECT item_name FROM items WHERE item_id = ?");
        $stmt->bind_param('i', $item_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $item = $result->fetch_assoc();
        
        if ($item) {
            $qr_code_path = "../qr_codes/{$item_id}-{$item['item_name']}.png";
            if (file_exists($qr_code_path)) {
                $zip->addFile($qr_code_path, basename($qr_code_path));
            }
        }
    }
    
    $zip->close();
    
    // Set headers for download
    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="qr_codes.zip"');
    header('Content-Length: ' . filesize($zip_file));
    header('Pragma: no-cache');
    
    // Output the zip file
    readfile($zip_file);
    
    // Clean up
    unlink($zip_file);
    
} catch (Exception $e) {
    die('Error creating zip file: ' . $e->getMessage());
} finally {
    if (isset($stmt)) {
        $stmt->close();
    }
    $conn->close();
}