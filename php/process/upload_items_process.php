<?php
// Include database connection details (replace with your actual credentials)
require 'session_start_process.php';
require 'db_connect.php';

require '../vendor/autoload.php';
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\ValidationException;



$userId = $_SESSION['user_id']; // Assuming you have user authentication

// Initialize variables to prevent errors (optional)
$itemName = "";
$property = "";
$orderBatchNumber = "";
$modelNumber = "";
$serialNumber = "";
$warrantyCoverage = "";
$brand = "";
$itemType = "";
$itemDetails = "";
$unitPrice = "";
$statusDescription = "";
$justificationOfPurchase = "";
$deliveryDate = "";
$supplierName = "";
$poNumber = "";
$poDate = "";
$prNumber = "";
$invoiceNo = "";
$deliveryReceipt = "";
$itemsReceivedBy = "";
$remarks = "";

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $itemName = trim($_POST['item_name']);
  $property = trim($_POST['property']);
  $orderBatchNumber = trim($_POST['order_batch_number']);
  $modelNumber = trim($_POST['model_number']);
  $serialNumber = trim($_POST['serial_number']);
  $warrantyCoverage = (int) trim($_POST['warranty_coverage']);
  $brand = trim($_POST['brand']);
  $itemType = (int) trim($_POST['item_type']);
  $itemDetails = trim($_POST['item_details']);
  $unitPrice = (float) trim($_POST['unit_price']);
  $statusDescription = trim($_POST['status_description']);
  $justificationOfPurchase = trim($_POST['justification_of_purchase']);
  $deliveryDate = trim($_POST['delivery_date']);
  $supplierName = trim($_POST['supplier_name']);
  $poNumber = trim($_POST['po_number']);
  $poDate = trim($_POST['po_date']);
  $prNumber = trim($_POST['pr_number']);
  $invoiceNo = trim($_POST['invoice_no']);
  $deliveryReceipt = trim($_POST['delivery_receipt']);
  $itemsReceivedBy = trim($_POST['items_received_by']);
  $remarks = trim($_POST['remarks']);

  // Prepare the SQL statement to insert data
  $sql = "INSERT INTO items (
    item_name,
    property,
    order_batch_number,
    model_number,
    serial_number,
    warranty_coverage,
    brand,
    item_type,
    item_details,
    unit_price,
    status_description,
    justification_of_purchase,
    delivery_date,
    supplier_name,
    po_number,
    po_date,
    pr_number,
    invoice_no,
    delivery_receipt,
    items_received_by,
    remarks
  ) VALUES (
    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
  )";

  // Prepare and execute the statement
  if ($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, "sssssisisdsssssssssss", $itemName, $property, $orderBatchNumber, $modelNumber, $serialNumber, $warrantyCoverage, $brand, $itemType, $itemDetails, $unitPrice, $statusDescription, $justificationOfPurchase, $deliveryDate, $supplierName, $poNumber, $poDate, $prNumber, $invoiceNo, $deliveryReceipt, $itemsReceivedBy, $remarks);
    if (mysqli_stmt_execute($stmt)) {
      // Success message
      $message = "Item added successfully!";

      // Get the newly inserted item ID
      $lastId = mysqli_stmt_insert_id($stmt);

      $writer = new PngWriter();

      $qrCodeData = $lastId . "-" . $itemName;
      // Create QR code
      $qrCode = QrCode::create($qrCodeData)
        ->setEncoding(new Encoding('UTF-8'))
        ->setErrorCorrectionLevel(ErrorCorrectionLevel::Low)
        ->setSize(100)
        ->setMargin(10)
        ->setRoundBlockSizeMode(RoundBlockSizeMode::Margin)
        ->setForegroundColor(new Color(0, 0, 0))
        ->setBackgroundColor(new Color(255, 255, 255));

      // Create generic logo
      /*$logo = Logo::create(__DIR__.'\..\assets\astoria_logo.png')
          ->setResizeToWidth(50)
          ->setPunchoutBackground(true)
      ;*/

      $result = $writer->write($qrCode);

      /*$result = $writer->write($qrCode, $logo);*/

            // Create generic label
      $label = Label::create($qrCodeData)
          ->setTextColor(new Color(0, 0, 0));

      $result = $writer->write($qrCode, null, $label);


      // Update the items table with QR code data (optional)
      if (!empty($qrCodeData)) {
        $updateSql = "UPDATE items SET qr_code_data = ? WHERE item_id = ?";
        $updateStmt = mysqli_prepare($conn, $updateSql);
        mysqli_stmt_bind_param($updateStmt, "si", $qrCodeData, $lastId);
        $updateStmt->execute();
      }

      // Prepare inventory table insertion
      $inventorySql = "INSERT INTO inventory (item_id, user_id, batch_order_id, date_added)
                        VALUES (?, ?, ?, NOW())";
      $inventoryStmt = mysqli_prepare($conn, $inventorySql);
      mysqli_stmt_bind_param($inventoryStmt, "iii", $lastId, $userId, $orderBatchNumber);

      // Execute inventory insertion
      $inventoryStmt->execute();

      // Save it to a file
      $result->saveToFile(__DIR__.'\..\qr_codes\\'. $qrCodeData . '.png');



    } else {
      // Error message
      $message = "Error adding item: " . mysqli_error($conn);
    }
  } else {
    // Error preparing statement
    $message = "Error preparing statement: " . mysqli_error($conn);
  }

  // Close the statement and connection
  mysqli_stmt_close($stmt);
  mysqli_close($conn);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Item Result</title>
</head>
<body>
  <h1><?php echo $message; ?></h1>
  <?php if (isset($message) && $message === 'Item/s added successfully!') : ?>
    <a href="upload_items.php">Add Another Item</a>
    <img src="data:image/png;base64,<?php echo base64_encode($result); ?>" alt="QR Code">

  <?php endif; ?>

  
</body>
</html>
