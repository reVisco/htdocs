<?php
// Include database connection and session
require 'session_start_process.php';
require 'test_db_connect.php';

// Include QR code library (only needed if generating QR code)
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

$userId = $_SESSION['user_id']; // Assuming user authentication is set

// Initialize variables to prevent errors
$itemDetails = "";
$property = "";
$modelNumber = "";
$warrantyCoverage = "";
$brand = "";
$itemType = "";
$unitPrice = "";
$status = "";
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
$serialNumbers = [];
$qty = 1;
$uom = "Unit";
$totalAmount = null;
$location = null;
$issuedBy = null;
$issuedTo = null;
$department = null;
$dateIssued = null;
$warrantySlipNo = null;
$doneOrNoPr = null;
$generateQr = "yes"; // Default to generating QR code

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $itemDetails = trim($_POST['item_details']);
    $property = trim($_POST['property']);
    $modelNumber = trim($_POST['model_number']);
    $serialNumbers = isset($_POST['serial_numbers']) ? $_POST['serial_numbers'] : [];
    $warrantyCoverage = !empty($_POST['warranty_coverage']) ? (int) trim($_POST['warranty_coverage']) : null;
    $brand = !empty($_POST['brand']) ? trim($_POST['brand']) : null;
    $itemType = !empty($_POST['item_type']) ? trim($_POST['item_type']) : null;
    $unitPrice = !empty($_POST['unit_price']) ? (float) trim($_POST['unit_price']) : null;
    $status = !empty($_POST['status']) ? trim($_POST['status']) : null;
    $justificationOfPurchase = !empty($_POST['justification_of_purchase']) ? trim($_POST['justification_of_purchase']) : null;
    $deliveryDate = !empty($_POST['delivery_date']) ? trim($_POST['delivery_date']) : null;
    $supplierName = !empty($_POST['supplier_name']) ? trim($_POST['supplier_name']) : null;
    $poNumber = !empty($_POST['po_number']) ? trim($_POST['po_number']) : null;
    $poDate = !empty($_POST['po_date']) ? trim($_POST['po_date']) : null;
    $prNumber = !empty($_POST['pr_number']) ? trim($_POST['pr_number']) : null;
    $invoiceNo = !empty($_POST['invoice_no']) ? trim($_POST['invoice_no']) : null;
    $deliveryReceipt = !empty($_POST['delivery_receipt']) ? trim($_POST['delivery_receipt']) : null;
    $itemsReceivedBy = !empty($_POST['items_received_by']) ? trim($_POST['items_received_by']) : null;
    $remarks = !empty($_POST['remarks']) ? trim($_POST['remarks']) : null;
    $qty = !empty($_POST['qty']) ? (int) trim($_POST['qty']) : 1;
    $uom = !empty($_POST['uom']) ? trim($_POST['uom']) : "Unit";
    $totalAmount = !empty($_POST['total_amount']) ? (float) trim($_POST['total_amount']) : null;
    $location = !empty($_POST['location']) ? trim($_POST['location']) : null;
    $issuedBy = !empty($_POST['issued_by']) ? trim($_POST['issued_by']) : null;
    $issuedTo = !empty($_POST['issued_to']) ? trim($_POST['issued_to']) : null;
    $department = !empty($_POST['department']) ? trim($_POST['department']) : null;
    $dateIssued = !empty($_POST['date_issued']) ? trim($_POST['date_issued']) : null;
    $warrantySlipNo = !empty($_POST['warranty_slip_no']) ? trim($_POST['warranty_slip_no']) : null;
    $doneOrNoPr = !empty($_POST['done_or_no_pr']) ? trim($_POST['done_or_no_pr']) : null;
    $generateQr = !empty($_POST['generate_qr']) ? trim($_POST['generate_qr']) : "yes";

    $successCount = 0;
    $message = "";
    $lastQrCodePath = ""; // To store the path of the last generated QR code for display

    // Start transaction
    $conn->begin_transaction();

    try {
        // Insert or retrieve property from Properties
        $stmt = $conn->prepare("INSERT INTO Properties (property_name) VALUES (?) ON DUPLICATE KEY UPDATE property_id=LAST_INSERT_ID(property_id)");
        $stmt->bind_param("s", $property);
        $stmt->execute();
        $property_id = $conn->insert_id;

        if (!$property_id) {
            // If property_id is still not set, retrieve it
            $stmt = $conn->prepare("SELECT property_id FROM Properties WHERE property_name = ?");
            $stmt->bind_param("s", $property);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows === 0) {
                throw new Exception("Failed to insert or retrieve property: " . $property);
            }
            $property_id = $result->fetch_assoc()['property_id'];
        }

        // Insert into Brands (if brand exists)
        $brand_id = null;
        if ($brand) {
            $stmt = $conn->prepare("INSERT INTO Brands (brand_name) VALUES (?) ON DUPLICATE KEY UPDATE brand_id=LAST_INSERT_ID(brand_id)");
            $stmt->bind_param("s", $brand);
            $stmt->execute();
            $brand_id = $conn->insert_id;
        }

        // Insert into Item_Types (if item_type exists)
        $item_type_id = null;
        if ($itemType) {
            $stmt = $conn->prepare("INSERT INTO Item_Types (item_type_name) VALUES (?) ON DUPLICATE KEY UPDATE item_type_id=LAST_INSERT_ID(item_type_id)");
            $stmt->bind_param("s", $itemType);
            $stmt->execute();
            $item_type_id = $conn->insert_id;
        }

        // Insert into Suppliers (if supplier_name exists)
        $supplier_id = null;
        if ($supplierName) {
            $stmt = $conn->prepare("INSERT INTO Suppliers (supplier_name) VALUES (?) ON DUPLICATE KEY UPDATE supplier_id=LAST_INSERT_ID(supplier_id)");
            $stmt->bind_param("s", $supplierName);
            $stmt->execute();
            $supplier_id = $conn->insert_id;
        }

        // Insert into Purchase_Orders
        $po_id = null;
        if ($poNumber && $poDate) {
            $stmt = $conn->prepare("INSERT INTO Purchase_Orders (po_number, po_date, supplier_id) VALUES (?, ?, ?)");
            $stmt->bind_param("ssi", $poNumber, $poDate, $supplier_id);
            $stmt->execute();
            $po_id = $conn->insert_id;
        }

        // Insert into Purchase_Requests
        $pr_id = null;
        if ($prNumber) {
            $stmt = $conn->prepare("INSERT INTO Purchase_Requests (pr_number, done_or_no_pr) VALUES (?, ?)");
            $stmt->bind_param("ss", $prNumber, $doneOrNoPr);
            $stmt->execute();
            $pr_id = $conn->insert_id;
        }

        // Insert into Invoices
        $invoice_id = null;
        if ($invoiceNo) {
            $stmt = $conn->prepare("INSERT INTO Invoices (invoice_number) VALUES (?)");
            $stmt->bind_param("s", $invoiceNo);
            $stmt->execute();
            $invoice_id = $conn->insert_id;
        }

        // Insert into Personnel (for items_received_by)
        $received_by_id = null;
        if ($itemsReceivedBy) {
            $stmt = $conn->prepare("INSERT INTO Personnel (person_name) VALUES (?) ON DUPLICATE KEY UPDATE person_id=LAST_INSERT_ID(person_id)");
            $stmt->bind_param("s", $itemsReceivedBy);
            $stmt->execute();
            $received_by_id = $conn->insert_id;
        }

        // Insert into Personnel (for issued_by and issued_to)
        $issued_by_id = null;
        if ($issuedBy) {
            $stmt = $conn->prepare("INSERT INTO Personnel (person_name) VALUES (?) ON DUPLICATE KEY UPDATE person_id=LAST_INSERT_ID(person_id)");
            $stmt->bind_param("s", $issuedBy);
            $stmt->execute();
            $issued_by_id = $conn->insert_id;
        }

        $issued_to_id = null;
        if ($issuedTo) {
            $stmt = $conn->prepare("INSERT INTO Personnel (person_name) VALUES (?) ON DUPLICATE KEY UPDATE person_id=LAST_INSERT_ID(person_id)");
            $stmt->bind_param("s", $issuedTo);
            $stmt->execute();
            $issued_to_id = $conn->insert_id;
        }

        // Insert into Departments
        $department_id = null;
        if ($department) {
            $stmt = $conn->prepare("INSERT INTO Departments (department_name) VALUES (?) ON DUPLICATE KEY UPDATE department_id=LAST_INSERT_ID(department_id)");
            $stmt->bind_param("s", $department);
            $stmt->execute();
            $department_id = $conn->insert_id;
        }

        // Insert into Locations (if location exists)
        $location_id = null;
        if ($location) {
            $stmt = $conn->prepare("INSERT INTO Locations (location_name) VALUES (?) ON DUPLICATE KEY UPDATE location_id=LAST_INSERT_ID(location_id)");
            $stmt->bind_param("s", $location);
            $stmt->execute();
            $location_id = $conn->insert_id;
        }

        // Create items based on qty
        for ($i = 0; $i < $qty; $i++) {
            // Use serial number if available, otherwise use "N/A"
            $serialNumber = isset($serialNumbers[$i]) && !empty($serialNumbers[$i]) && $serialNumbers[$i] !== "N/A" ? $serialNumbers[$i] : null;

            // Insert into Items
            $stmt = $conn->prepare("INSERT INTO Items (item_details, serial_number, brand_id, item_type_id, property_id, status, location_id, qr_code_data) VALUES (?, ?, ?, ?, ?, ?, ?, NULL)");
            $stmt->bind_param("ssiiisi", $itemDetails, $serialNumber, $brand_id, $item_type_id, $property_id, $status, $location_id);
            $stmt->execute();
            $item_id = $conn->insert_id;
            
            // Generate QR code only if requested
            $qrCodeData = null;
            if ($generateQr === "yes") {
                $writer = new PngWriter();
                $qrCodeData = $item_id;
                $qrCode = QrCode::create($qrCodeData)
                    ->setEncoding(new Encoding('UTF-8'))
                    ->setErrorCorrectionLevel(ErrorCorrectionLevel::Low)
                    ->setSize(145)
                    ->setMargin(10)
                    ->setRoundBlockSizeMode(RoundBlockSizeMode::Margin)
                    ->setForegroundColor(new Color(0, 0, 0))
                    ->setBackgroundColor(new Color(255, 255, 255));

                $label = Label::create($qrCodeData)
                    ->setTextColor(new Color(0, 0, 0));

                $result = $writer->write($qrCode, null, $label);

                // Save QR code to file
                $qrCodePath = __DIR__ . '/../qr_codes/' . $qrCodeData . '.png';
                $result->saveToFile($qrCodePath);

                // Update the Items table with QR code data
                $updateStmt = $conn->prepare("UPDATE Items SET qr_code_data = ? WHERE item_id = ?");
                $updateStmt->bind_param("si", $qrCodeData, $item_id);
                $updateStmt->execute();

                $lastQrCodePath = $qrCodePath; // Store the last QR code path for display
            }
        }

        // Insert into Item_Purchases
        if (!$totalAmount && $unitPrice) {
            $totalAmount = $unitPrice * $qty; // Total amount for the entire batch
        }
        $stmt = $conn->prepare("INSERT INTO Item_Purchases (item_id, po_id, pr_id, invoice_id, qty, uom, unit_price, total_amount, justification_of_purchase, delivery_date, received_by, remarks, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iiiisdssdssii", $item_id, $po_id, $pr_id, $invoice_id, $qty, $uom, $unitPrice, $totalAmount, $justificationOfPurchase, $deliveryDate, $received_by_id, $remarks, $userId);
        $stmt->execute();

        // Insert into Item_Issuances (if issuance details are provided)
        if ($issued_by_id || $issued_to_id || $department_id || $dateIssued) {
            $stmt = $conn->prepare("INSERT INTO Item_Issuances (item_id, issued_by, issued_to, department_id, date_issued) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("iiiis", $item_id, $issued_by_id, $issued_to_id, $department_id, $dateIssued);
            $stmt->execute();
        }

        // Insert into Warranties (if warranty details are provided)
        if ($warrantyCoverage && $deliveryDate) {
            $warranty_ends = date('Y-m-d', strtotime($deliveryDate . " + $warrantyCoverage months"));
            $stmt = $conn->prepare("INSERT INTO Warranties (item_id, warranty_ends, warranty_slip_no) VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $item_id, $warranty_ends, $warrantySlipNo);
            $stmt->execute();
        }

        $successCount++;
        

        // Commit transaction
        $conn->commit();
        $message = $successCount . " item(s) added successfully!";
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        $message = "Error: " . $e->getMessage();
        // Clean up any generated QR code files if they exist
        if (!empty($lastQrCodePath) && file_exists($lastQrCodePath)) {
            unlink($lastQrCodePath);
        }
    }

    // Close the connection
    $conn->close();
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
        <!-- Page Content -->
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
                            <?php if ($generateQr === "yes" && !empty($lastQrCodePath) && file_exists($lastQrCodePath)) : ?>
                                <div class="qr-code-container mb-4">
                                    <h5>Generated QR Code (Last Item):</h5>
                                    <img src="../qr_codes/<?php echo basename($lastQrCodePath); ?>" alt="QR Code" class="img-fluid mb-3">
                                    <div class="btn-group" role="group" aria-label="Action buttons">
                                        <a href="../qr_codes/<?php echo basename($lastQrCodePath); ?>" class="btn btn-info" download="QR_Code_<?php echo $qrCodeData; ?>.png">Download QR Code</a>
                                        <a href="../upload_items.php" class="btn btn-primary">Add Another Item</a>
                                        <a href="../inventory.php" class="btn btn-secondary">View Inventory</a>
                                    </div>
                                </div>
                            <?php else : ?>
                                <p>No QR code generated for this item (non-physical item).</p>
                                <div class="btn-group" role="group" aria-label="Action buttons">
                                    <a href="../upload_items.php" class="btn btn-primary">Add Another Item</a>
                                    <a href="../inventory.php" class="btn btn-secondary">View Inventory</a>
                                </div>
                            <?php endif; ?>
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