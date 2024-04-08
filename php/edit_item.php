<?php
require 'process/session_start_process.php';
require 'process/db_connect.php';

$errorMessage = "";
$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  // GET method: Show the data of the client
    if (!isset($_GET['id'])) {
        header("location: view_items.php");
        exit;
    }

    $id = $_GET['id'];

    // Prepare the statement
    $stmt = $conn->prepare("SELECT * FROM items where item_id = ?");
    $stmt->bind_param("i", $id); // Bind the parameter

    // Execute the statement
    $stmt->execute();
    $result = $stmt->get_result();

    if(!$result){
        header("location: view_items.php");
        exit();
    }

    $row = $result->fetch_assoc();
    $itemName = $row['item_name'];
    $property = $row['property'];
    $orderBatchNumber = $row['order_batch_number'];
    $modelNumber = $row['model_number'];
    $serialNumber = $row['serial_number'];
    $warrantyCoverage = (int) $row['warranty_coverage'];
    $brand = $row['brand'];
    $itemType = (int) $row['item_type'];
    $itemDetails = $row['item_details'];
    $unitPrice = (float) $row['unit_price'];
    $statusDescription = $row['status_description'];
    $justificationOfPurchase = $row['justification_of_purchase'];
    $deliveryDate = $row['delivery_date'];
    $supplierName = $row['supplier_name'];
    $poNumber = $row['po_number'];
    $poDate = $row['po_date'];
    $prNumber = $row['pr_number'];
    $invoiceNo = $row['invoice_no'];
    $deliveryReceipt = $row['delivery_receipt'];
    $itemsReceivedBy = $row['items_received_by'];
    $remarks = $row['remarks'];
} else {
    // POST method:
    $itemId = trim($_POST['item_id']);
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

    $id = $itemId;

    // Prepare the SQL statement to insert data
    $sql = "
    UPDATE items
    SET 
        item_name=?,
        property = ?,
        order_batch_number = ?,
        model_number = ?,
        serial_number = ?,
        warranty_coverage = ?,
        brand = ?,
        item_type = ?,
        item_details = ?,
        unit_price = ?,
        status_description = ?,
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
    WHERE item_id = ?;";

    // Prepare and execute the statement
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "sssssisisdsssssssssssi", $itemName, $property, $orderBatchNumber, $modelNumber, $serialNumber, $warrantyCoverage, $brand, $itemType, $itemDetails, $unitPrice, $statusDescription, $justificationOfPurchase, $deliveryDate, $supplierName, $poNumber, $poDate, $prNumber, $invoiceNo, $deliveryReceipt, $itemsReceivedBy, $remarks, $itemId);
        if (mysqli_stmt_execute($stmt)) {
            // Success message
            $message = "Item added successfully!";
        }
    }
    
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Item Page</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
    <div class="wrapper d-flex align-items-stretch">
        <?php include 'admin_nav.php';?>
        <div id="content" class="p-2 pt-5">
            <div class="card">
                <div class="card-header">
                    <h3>Edit Item</h3>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="admin_dashboard.php">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">
                                <a href="inventory.php">Inventory</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="view_items.php">View Items</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Edit Item
                            </li>
                        </ol>
                    </nav>
                </div>
                <div class="card-body">
                    <form method="post">
                        <input type="hidden" name="item_id" value="<?php echo $id; ?>"> 
                        <div class="container">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Item ID: <?php echo $id; ?></h4>
                                    <h4>Item Name: <?php echo $itemName; ?></h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col mb-4 mr-4">
                                            <?php if (isset($message) && $message === 'Item/s added successfully!') : ?>
                                            <h1><?php echo $message; ?></h1>

                                            <?php endif; ?>
                                        
                                            <label for="item_name">Item Name:</label>
                                            <br>
                                            <input type="text" id="item_name" name="item_name" value="<?php echo $itemName; ?>">

                                            <br><br>

                                            <label for="property">Property:</label>
                                            <br>
                                            <select name="property" id="property">
<?php
    // Assuming you have an array $properties containing the property values
    $propertyValues = array('ACES', 'AVLCI', 'APZ', 'AC3', 'AB1', 'APW', 'SPR', 'AHR', 'ABH', 'SIARGAO', 'AGL', 'ACHI', 'NEXGEN', 'CHRDY', 'AGB');

    // Loop through the property values and check for selection
    foreach ($propertyValues as $propertyValue) {
        $selected = (isset($property) && $property === $propertyValue) ? ' selected="selected"' : '';
        echo "<option value=\"$propertyValue\"$selected>$propertyValue</option>";
    }
?>
                                            </select>

                                            <br><br>

                                            <label for="order_batch_number">Order Batch Number:</label>
                                            <br>
                                            <input type="text" id="order_batch_number" name="order_batch_number" value="<?php echo $orderBatchNumber; ?>">

                                            <br><br>

                                            <label for="model_number">Model Number:</label>
                                            <br>
                                            <input type="text" id="model_number" name="model_number" value="<?php echo $modelNumber; ?>">

                                            <br><br>

                                            <label for="serial_number">Serial Number:</label>
                                            <br>
                                            <input type="text" id="serial_number" name="serial_number" value="<?php echo $serialNumber ?>">

                                            <br><br>

                                            <label for="warranty_coverage">Warranty Coverage:</label>
                                            <br>
                                            <input type="number" name="warranty_coverage" id="warranty_coverage" value="<?php echo $warrantyCoverage; ?>">

                                            <br><br>

                                            <label for="brand">Brand:</label>
                                            <br>
                                            <input type="text" id="brand" name="brand" value="<?php echo $brand ?>">

                                            <br><br>

                                            <label for="item_type">Item Type (Enter corresponding code):</label>
                                            <br>
                                            <input type="number" id="item_type" name="item_type" value="<?php echo $itemType ?>">

                                            <br><br>

                                            <label for="item_details">Item Details:</label>
                                            <br>
                                            <textarea id="item_details" name="item_details" rows="4" cols="25"><?php echo $itemDetails ?></textarea>

                                            <br><br>

                                            <label for="status_description">Status Description:</label>
                                            <br>
                                            <textarea id="status_description" name="status_description" rows="4" cols="25"><?php echo $statusDescription ?></textarea><br><br>

                                            <label for="unit_price">Unit Price:</label>
                                            <br>
                                            <input type="number" step="0.01" id="unit_price" name="unit_price" value="<?php echo $unitPrice; ?>"><br><br>
                                        </div>
                                        <div class="col mb-4 mr-4">
                                            <label for="justification_of_purchase">Justification of Purchase:</label>
                                            <br>
                                            <textarea name="justification_of_purchase" id="justification_of_purchase" rows="4" cols="25" ><?php echo $justificationOfPurchase; ?></textarea>

                                            <br><br>

                                            <label for="delivery_date">Delivery Date (YYYY-MM-DD):</label>
                                            
                                            <br>

                                            <input type="date" id="delivery_date" name="delivery_date" value="<?php echo $deliveryDate; ?>"><br><br>

                                            <label for="supplier_name">Supplier Name:</label>
                                            <br>

                                            <textarea type="text" id="supplier_name" name="supplier_name" rows="2" cols="25"> <?php echo $supplierName ?></textarea>

                                            <br><br>

                                            <label for="po_number">PO Number:</label>
                                            
                                            <br>

                                            <input type="text" id="po_number" name="po_number" value="<?php echo $poNumber ?>">

                                            <br><br>

                                            <label for="po_date">PO Date (YYYY-MM-DD):</label>

                                            <br>

                                            <input type="date" id="po_date" name="po_date" value="<?php echo $poDate ?>"><br><br>

                                            <label for="pr_number">PR Number:</label>

                                            <br>

                                            <input type="text" id="pr_number" name="pr_number" value="<?php echo $prNumber ?>">

                                            <br><br>

                                            <label for="invoice_no">Invoice Number:</label>
                                            
                                            <br>

                                            <input type="text" id="invoice_no" name="invoice_no" value="<?php echo $invoiceNo; ?>">

                                            <br><br>

                                            <label for="delivery_receipt">Delivery Receipt:</label>

                                            <br>

                                            <input type="text" id="delivery_receipt" name="delivery_receipt" value="<?php echo $deliveryReceipt ?>">

                                            <br><br>

                                            <label for="items_received_by">Items Received By:</label>
                                            <br>
                                            <select name="items_received_by" id="items_received_by">
<?php
    // Assuming you have an array $properties containing the property values
    $receivedByValues = array('ALVIN', 'CLINTON');

    // Loop through the property values and check for selection
    foreach ($receivedByValues as $receivedValue) {
        $selected = (isset($itemsReceivedBy) && $itemsReceivedBy === $receivedValue) ? ' selected="selected"' : '';
        echo "<option value=\"$receivedValue\"$selected>$receivedValue</option>";
    }
?>
                                            </select>

                                            <br><br>

                                            <label for="remarks">Remarks:</label>

                                            <br>

                                            <textarea id="remarks" name="remarks" rows="4" cols="25">
                                                <?php echo $remarks ?>
                                            </textarea>

                                            <br><br>

                                        </div>
                                    </div>
                                    <div class="row">
                                        <button type="submit" class="btn btn-primary btn-block">Update Item</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>