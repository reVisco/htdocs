<?php
require 'test_db_connect.php';
require '../vendor/autoload.php'; // Ensure Endroid QR Code library is installed

use ZipStream\ZipStream;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;

if (isset($_POST['item_ids']) && is_array($_POST['item_ids'])) {
    $itemIds = $_POST['item_ids'];
    
    // Create a temporary directory for the zip file
    $temp_dir = sys_get_temp_dir();
    $zip_file = $temp_dir . '/qr_codes_' . time() . '.zip';
    
    $zip = new ZipArchive();
    if ($zip->open($zip_file, ZipArchive::CREATE) !== TRUE) {
        die('Could not create ZIP file');
    }
    
    // Get item details and add QR codes to the zip file
    try {
        error_log("Starting QR code download process");
        error_log("Item IDs received: " . json_encode($itemIds));
        
        $stmt = $conn->prepare("SELECT item_id, item_details FROM Items WHERE item_id IN (" . implode(',', array_fill(0, count($itemIds), '?')) . ")");
        $stmt->bind_param(str_repeat('i', count($itemIds)), ...$itemIds);
        $stmt->execute();
        $result = $stmt->get_result();
        
        error_log("SQL query executed successfully");
        
        while ($row = $result->fetch_assoc()) {
            $itemId = $row['item_id'];
            $qr_code_path = "../qr_codes/{$itemId}.png";
            
            error_log("Processing item ID: " . $itemId);
            error_log("Looking for QR code at: " . realpath($qr_code_path));
            
            if (file_exists($qr_code_path)) {
                error_log("QR code file found for item ID: " . $itemId);
                $zip->addFile($qr_code_path, basename($qr_code_path));
            } else {
                error_log("QR code file NOT found for item ID: " . $itemId);
            }
        }
        
        error_log("Closing ZIP file");
        $zip->close();
        
        error_log("ZIP file created at: " . $zip_file);
        error_log("ZIP file size: " . (file_exists($zip_file) ? filesize($zip_file) : 'file not found'));
        
        // Set headers for download
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="qr_codes.zip"');
        header('Content-Length: ' . filesize($zip_file));
        header('Pragma: no-cache');
        
        // Output the zip file
        readfile($zip_file);
        error_log("ZIP file sent to browser");
        
        // Clean up - ensure output is flushed before deleting
        flush();
        ob_flush();
        unlink($zip_file);
        error_log("Cleanup completed");
        
    } catch (Exception $e) {
        error_log("Error in QR code download process: " . $e->getMessage());
        die('Error creating zip file: ' . $e->getMessage());
    } finally {
        if (isset($stmt)) {
            $stmt->close();
        }
        $conn->close();
    }
}
?>