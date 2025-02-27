<?php
  require 'process/session_start_process.php';
?>

<html>
<head>
	<title>Upload Items</title>
  	<meta charset="utf-8">
  	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  	<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="../css/admin.css">
	<script src="js/upload_items.js"></script>
<style>
.form-group {
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 4px;
    padding: 15px;
    margin-bottom: 20px;
}

.form-group label {
    font-weight: 500;
    color: #495057;
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.card-header {
    background-color: #f1f3f5;
    border-bottom: 2px solid #dee2e6;
}

.serial-number-input {
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 4px;
    padding: 15px;
    margin-bottom: 10px;
}
</style>
</head>

<body>
	<div class="wrapper d-flex align-items-stretch">
		<?php include 'admin_nav.php';?>
    	<!-- Page Content  -->
		<div id="content" class="p-2 pt-5">
			<div class="card">
				<div class="card-header">
					<h3>Upload Items</h3>
					<nav aria-label="breadcrumb">
		                <ol class="breadcrumb">
		                    <li class="breadcrumb-item"><a href="admin_dashboard.php">Dashboard</a></li>
		                    <li class="breadcrumb-item"><a href="inventory.php">Inventory</a></li>
		                    <li class="breadcrumb-item active" aria-current="page">Upload Items</li>
		                </ol>
		            </nav>
				</div>
				<div class="card-body">
					<form action="process/upload_items_process.php" method="post">
		    			<div class="container-fluid">
							<div class="row mb-4">
								<div class="col-md-4">
									<div class="card w-100">
										<div class="card-header">
											<h5 class="mb-0">Common Item Details</h5>
										</div>
										<div class="card-body">
											<div class="form-group">
												<label for="item_name">Item Name</label>
												<input type="text" class="form-control" id="item_name" name="item_name" required>
											</div>

											<div class="form-group">
		    									<label for="property">Property</label>
												<select class="form-control" id="property" name="property" required>
												<option value="ACES">ACES</option>
												<option value="AVLCI">AVLCI</option>
												<option value="APZ">APZ</option>
												<option value="AC3">AC3</option>
												<option value="AB1">AB1</option>
												<option value="APW">APW</option>
												<option value="SPR">SPR</option>
												<option value="AHR">AHR</option>
												<option value="ABH">ABH</option>
												<option value="SIARGAO">SIARGAO</option>
												<option value="AGL">AGL</option>
												<option value="ACHI">ACHI</option>
												<option value="NEXGEN">NEXGEN</option>
												<option value="CHRDY">CHRDY</option>
												<option value="AGB">AGB</option>
												</select>
		    								</div>

											<div class="form-group">
		    									<label for="order_batch_number">Order Batch Number</label>
												<input type="text" class="form-control" id="order_batch_number" name="order_batch_number">
		    								</div>

		    								<div class="form-group">
		    									<label for="model_number">Model Number</label>
												<input type="text" class="form-control" id="model_number" name="model_number">
		    								</div>

		    								<div class="form-group">
		    									<label for="warranty_coverage">Warranty Coverage (Months)</label>
												<input type="number" class="form-control" id="warranty_coverage" name="warranty_coverage">
		    								</div>

		    								<div class="form-group">
		    									<label for="brand">Brand</label>
												<input type="text" class="form-control" id="brand" name="brand">
		    								</div>

		    								<div class="form-group">
		    									<label for="item_type">Item Type (Enter corresponding code)</label>
												<input type="number" class="form-control" id="item_type" name="item_type">
		    								</div>

		    								<div class="form-group">
		    									<label for="item_details">Item Details</label>
		    									<input type="text" class="form-control" id="item_details" name="item_details">
											</div>

		    								<div class="form-group">
		    									<label for="status_description">Status Description</label>
		    									<input type="text" class="form-control" id="status_description" name="status_description">
											</div>

		    								<div class="form-group">
		    									<label for="unit_price">Unit Price</label>
												<input type="number" step="0.01" class="form-control" id="unit_price" name="unit_price">
		    								</div>
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<div class="card">
										<div class="card-header">
											<h5 class="mb-0">Purchase Details</h5>
										</div>
										<div class="card-body">
											<div class="form-group">
		    									<label for="justification_of_purchase">Justification of Purchase</label>
												<textarea class="form-control" id="justification_of_purchase" name="justification_of_purchase" rows="4"></textarea>
		    								</div>

											<div class="form-group">
		    									<label for="delivery_date">Delivery Date</label>
												<input type="date" class="form-control" id="delivery_date" name="delivery_date">
		    								</div>

											<div class="form-group">
		    									<label for="supplier_name">Supplier Name</label>
												<input type="text" class="form-control" id="supplier_name" name="supplier_name">
		    								</div>

		    								<div class="form-group">
		    									<label for="po_number">PO Number</label>
												<input type="text" class="form-control" id="po_number" name="po_number">
		    								</div>

		    								<div class="form-group">
		    									<label for="po_date">PO Date</label>
												<input type="date" class="form-control" id="po_date" name="po_date">
		    								</div>

		    								<div class="form-group">
		    									<label for="pr_number">PR Number</label>
												<input type="text" class="form-control" id="pr_number" name="pr_number">
		    								</div>

		    								<div class="form-group">
		    									<label for="invoice_no">Invoice Number</label>
									        	<input type="text" class="form-control id="invoice_no" name="invoice_no"><br><br>
											</div>

											<div class="form-group">
												<label for="delivery_receipt">Delivery Receipt</label>
												<input type="text" class="form-control id="delivery_receipt" name="delivery_receipt"><br><br>
											</div>

											<div class="form-group">
												<label for="items_received_by">Items Received By</label>
												<input type="text" class="form-control id="items_received_by" name="items_received_by"><br><br>
											</div>

											<div class="form-group">
												<label for="remarks">Remarks:</label>
												<input type="text" class="form-control id="remarks" name="remarks"><br><br>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<div class="card">
										<div class="card-header">
											Serial Number/s
										</div>
										<div class="card-body">
											<div id="serial_numbers_container">
	    										<div class="serial-number-input">
	    											<label for="serial_numbers[]">Serial Number 1:</label>
	    											<input type="text" name="serial_numbers[]" required>
	    										</div>
	    									</div>
	    									<button type="button" class="btn btn-secondary mt-3" onclick="addSerialNumberField()">Add Another Serial Number</button>
										</div>
									</div>
								</div>
							</div>
							<div class="row mb-4">
								
							</div>
							<div class="row">
								<div class="col">
									<div class="col text-center">
										<input type="submit" value="Submit" class="btn btn-primary btn-lg px-5 w-100">
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

<?php
$conn->close();
?>