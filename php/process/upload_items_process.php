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
  $serialNumbers = isset($_POST['serial_numbers']) ? $_POST['serial_numbers'] : array();
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

  $successCount = 0;
  $message = "";

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
    remarks,
    date_added
  ) VALUES (
    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW()
  )";

  // Prepare the statement
  if ($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, "sssssisisdsssssssssss", $itemName, $property, $orderBatchNumber, $modelNumber, $currentSerialNumber, $warrantyCoverage, $brand, $itemType, $itemDetails, $unitPrice, $statusDescription, $justificationOfPurchase, $deliveryDate, $supplierName, $poNumber, $poDate, $prNumber, $invoiceNo, $deliveryReceipt, $itemsReceivedBy, $remarks);
    
    // Loop through each serial number
    foreach ($serialNumbers as $currentSerialNumber) {
      if (mysqli_stmt_execute($stmt)) {
        $successCount++;
        // Get the newly inserted item ID
        $lastId = mysqli_stmt_insert_id($stmt);

        $writer = new PngWriter();

        $qrCodeData = $lastId . "-" . $itemName;
        // Create QR code
        $qrCode = QrCode::create($qrCodeData)
          ->setEncoding(new Encoding('UTF-8'))
          ->setErrorCorrectionLevel(ErrorCorrectionLevel::Low)
          ->setSize(145)
          ->setMargin(10)
          ->setRoundBlockSizeMode(RoundBlockSizeMode::Margin)
          ->setForegroundColor(new Color(0, 0, 0))
          ->setBackgroundColor(new Color(255, 255, 255));

        // Create generic label
        $label = Label::create($qrCodeData)
            ->setTextColor(new Color(0, 0, 0));

        $result = $writer->write($qrCode, null, $label);

        // Update the items table with QR code data
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

        // Save QR code to a file
        $result->saveToFile(__DIR__.'/../qr_codes/'. $qrCodeData . '.png');
      }
    }
    
    $message = $successCount . " item(s) added successfully!";
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
  <title>Upload Items Result</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="../../css/admin.css">
</head>
<body>
  <div class="wrapper d-flex align-items-stretch">
    <?php include '../admin_nav.php'; ?>
    <!-- Page Content  -->
    <div id="content" class="p-2 pt-5">
      <div class="card">
        <div class="card-header">
          <h3>Upload Items Result</h3>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">
                <a href="../admin_dashboard.php">Dashboard</a>
              </li>
              <li class="breadcrumb-item">
                <a href="../inventory.php">Inventory</a>
              </li>
              <li class="breadcrumb-item active" aria-current="page">
                Upload Items Result
              </li>
            </ol>
          </nav>
        </div>
        <div class="card-body text-center">
          <?php if (isset($message) && strpos($message, 'item(s) added successfully') !== false) : ?>
            <div class="alert alert-success" role="alert">
              <h4 class="alert-heading"><?php echo $message; ?></h4>
              <hr>
              <div class="qr-code-container mb-4">
                <h5>Generated QR Code:</h5>
                <?php
                  $qrCodePath = "../qr_codes/" . $lastId . "-" . $itemName . ".png";
                  if (file_exists($qrCodePath)) :
                ?>
                <img src="<?php echo $qrCodePath; ?>" alt="QR Code" class="img-fluid mb-3">
                <div class="btn-group" role="group" aria-label="Action buttons">
                  <a href="<?php echo $qrCodePath; ?>" class="btn btn-info" download="QR_Code_<?php echo $lastId; ?>_<?php echo $itemName; ?>.png">Download QR Code</a>
                  <a href="../upload_items.php" class="btn btn-primary">Add Another Item</a>
                  <a href="../inventory.php" class="btn btn-secondary">View Inventory</a>
                </div>
                <?php else: ?>
                <p class="text-danger">QR Code file not found.</p>
                <div class="btn-group" role="group" aria-label="Action buttons">
                  <a href="../upload_items.php" class="btn btn-primary">Add Another Item</a>
                  <a href="../inventory.php" class="btn btn-secondary">View Inventory</a>
                </div>
                <?php endif; ?>
              </div>
            </div>
          <?php else : ?>
            <div class="alert alert-danger" role="alert">
              <h4 class="alert-heading">Error</h4>
              <p><?php echo $message; ?></p>
              <hr>
              <a href="../upload_items.php" class="btn btn-primary">Try Again</a>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <script src="../../js/jquery.min.js"></script>
  <script src="../../js/popper.js"></script>
  <script src="../../js/bootstrap.min.js"></script>
  <script src="../../js/main.js"></script>
</body>
</html>
