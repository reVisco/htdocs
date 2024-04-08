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
</head>

<body>
	<div class="wrapper d-flex align-items-stretch">
		<?php include 'admin_nav.php';?>
    	<!-- Page Content  -->
		<div id="content" class="p-2 pt-5">
			<div class="card">
				<div class="card-header">
					<h3>Upload Item</h3>
					<nav aria-label="breadcrumb">
		                <ol class="breadcrumb">
		                    <li class="breadcrumb-item">
		                        <a href="admin_dashboard.php">Dashboard</a>
		                    </li>
		                    <li class="breadcrumb-item" aria-current="page">
		                        <a href="inventory.php">Inventory</a>
		                    </li>
		                    <li class="breadcrumb-item active" aria-current="page">
		                        Upload Items
		                    </li>
		                </ol>
		            </nav>
				</div>
				<div class="card-body">
					<form action="process/upload_items_process.php" method="post">
	    				<div class="container">
	    					<div class="row">
	    						<div class="col mb-4 mr-4">
	    							<div class="card">
	    								<div class="card-header">
	    									Column 1
	    								</div>
	    								<div class="card-body">
	    									<label for="item_name">Item Name:</label>
								        	<input type="text" id="item_name" name="item_name" required><br><br>

								        	<label for="property">Property:</label>
												<select id="property" name="property" required>
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
												<br><br>

									        <label for="order_batch_number">Order Batch Number:</label>
									        <input type="text" id="order_batch_number" name="order_batch_number"><br><br>

									        <label for="model_number">Model Number:</label>
									        <input type="text" id="model_number" name="model_number"><br><br>

									        <label for="serial_number">Serial Number:</label>
									        <input type="text" id="serial_number" name="serial_number"><br><br>

									        <label for="warranty_coverage">Warranty Coverage (Months):</label>
									        <input type="number" id="warranty_coverage" name="warranty_coverage"><br><br>

									        <label for="brand">Brand:</label>
									        <input type="text" id="brand" name="brand"><br><br>

									        <label for="item_type">Item Type (Enter corresponding code):</label>
									        <input type="number" id="item_type" name="item_type"><br><br>

									        <label for="item_details">Item Details:</label>
									        <textarea id="item_details" name="item_details" rows="4" cols="50"></textarea><br><br>

									        <label for="status_description">Status Description:</label>
				      						<textarea id="status_description" name="status_description" rows="4" cols="50"></textarea><br><br>

									        <label for="unit_price">Unit Price:</label>
									        <input type="number" step="0.01" id="unit_price" name="unit_price"><br><br>
	    								</div>
	    							</div>
	      						</div>

		      					<div class="col mb-4">
		      						<div class="card">
		      							<div class="card-header">
		      								Column 2
		      							</div>
		      							<div class="card-body">
		      								<label for="justification_of_purchase">Justification of Purchase:</label>
									        <textarea id="justification_of_purchase" name="justification_of_purchase" rows="4" cols="50"></textarea><br><br>

				      						<label for="delivery_date">Delivery Date (YYYY-MM-DD):</label>
									        <input type="date" id="delivery_date" name="delivery_date"><br><br>

									        <label for="supplier_name">Supplier Name:</label>
									        <input type="text" id="supplier_name" name="supplier_name"><br><br>

									        <label for="po_number">PO Number:</label>
									        <input type="text" id="po_number" name="po_number"><br><br>

									        <label for="po_date">PO Date (YYYY-MM-DD):</label>
									        <input type="date" id="po_date" name="po_date"><br><br>

									        <label for="pr_number">PR Number:</label>
									        <input type="text" id="pr_number" name="pr_number"><br><br>

									        <label for="invoice_no">Invoice Number:</label>
									        <input type="text" id="invoice_no" name="invoice_no"><br><br>

									        <label for="delivery_receipt">Delivery Receipt:</label>
									        <input type="text" id="delivery_receipt" name="delivery_receipt"><br><br>

									        <label for="items_received_by">Items Received By:</label>
													<select id="items_received_by" name="items_received_by" required>
													  <option value="ALVIN">ALVIN</option>
													  <option value="CLINTON">CLINTON</option>
													</select>
													<br><br>

									        <label for="remarks">Remarks:</label>
									        <textarea id="remarks" name="remarks" rows="4" cols="50"></textarea><br><br>

									        
		      							</div>
		      						</div>
		      					</div>
	    					</div>
	    					<div class="row">
	    						<button type="submit" class="btn btn-primary btn-block">Add Item</button>
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