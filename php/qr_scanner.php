<?php
	require 'process/session_start_process.php';
  require 'process/db_connect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>QR Scanner</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <script type="text/javascript" src="instascan.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.1.10/vue.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/webrtc-adapter/3.3.3/adapter.min.js"></script>
  
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
	    			<h3>QR Scanner</h3>
		    		<nav aria-label="breadcrumb">
						<ol class="breadcrumb">
							<li class="breadcrumb-item">
								<a href="admin_dashboard.php">Dashboard</a>
							</li>
							<li class="breadcrumb-item" aria-current="page">
								QR Scanner
							</li>
						</ol>
					</nav>
	    		</div>
	    		<div class="card-body">
					<div class="row">
						<div class="col p-4 mb-3 mr-4">
							<div class="card">
								<div class="card-header">
									Camera
								</div>
								<div class="card-body">
									<video id="preview" width="100%"></video>
								</div>
							</div>
						</div>
						<div class="col p-4 mb-3 mr-4">
							<div class="card">
								<div class="card-header">
									Details
								</div>
								<div class="card-body">
									<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" class="form-horizontal mb-3">
					    				<input type="text" name="qr_data" id="qr_data" placeholder="scan qrcode" class="form-control">
					    			</form>
					    		<?php
										if ($_SERVER["REQUEST_METHOD"] == "POST") {
											
											$qrCode = $_POST["qr_data"];
											
											// Prepare the statement
											$stmt = $conn->prepare("SELECT * FROM items WHERE qr_code_data = ?");
											$stmt->bind_param("s", $qrCode); // Bind the parameter

											// Execute the statement
											$stmt->execute();
											$result = $stmt->get_result();
											if($result->num_rows>0){
									?>	
					    		<div class="table-responsive">
					    			<table class="table table-bordered">
						        	<thead>
						        		<tr>
						        			<td>Item ID</td>
						        			<td>Item Name</td>
						        			<td>Property</td>
						        			<td>Serial Number</td>
						        			<td>Warranty Coverage</td>
						        		</tr>
						        	</thead>
						        	<tbody>
						        		<tr>

												<?php
													while($row = $result->fetch_assoc()){
													// Add edit button with the correct item ID
													?>
													<button type="button" class="form-control btn btn-primary rounded px-3 mb-3" onclick="editItem(<?= json_encode($row) ?>)">
														Edit Item #<?=htmlspecialchars($row["item_id"]) ?>
													</button>
													<?php
														echo "<td>" . htmlspecialchars($row["item_id"]) . "</td>";
														echo "<td>" . htmlspecialchars($row["item_name"]) . "</td>";
														echo "<td>" . htmlspecialchars($row["property"]) . "</td>";
														echo "<td>" . htmlspecialchars($row["serial_number"]) . "</td>";
														echo "<td>" . htmlspecialchars($row["warranty_coverage"]) . "month/s</td>";
													?>
													
							        	</tr>
											</tbody>
						      	</table>
						    	</div>
						    	<br>

						    	<div class="table-responsive">
						    		<table class="table table-bordered">
						    			<thead>
						    				<tr>
						    					<td>Brand</td>
						    					<td>Item Type</td>
						    					<td>Item Details</td>
						    					<td>Status</td>
						    					<td>Unit Price</td>
						    				</tr>
						    			</thead>
						    			<tbody>
						    				<tr>
						    					<?php
												echo "<td>" . htmlspecialchars($row["brand"]) . "</td>";
												echo "<td>" . htmlspecialchars($row["item_type"]) . "</td>";
												echo "<td>" . htmlspecialchars($row["item_details"]) . "</td>";
												echo "<td>" . htmlspecialchars($row["status_description"]) . "</td>";
												echo "<td>" . htmlspecialchars($row["unit_price"]) . "</td>";
											?>
						    				</tr>
						    			</tbody>
						    		</table>
						    	</div>
						    	<br>

						    	<div class="table-responsive">
						    		<table class="table table-bordered">
						    			<thead>
						    				<tr>
						    					<td>Justification of Purchase</td>
						    					<td>Delivery Date</td>
						    					<td>Supplier Name</td>
						    					<td>PO Number</td>
						    					<td>PO Date</td>
						    				</tr>
						    			</thead>
						    			<tbody>
						    				<tr>
						    					<?php
												echo "<td>" . htmlspecialchars($row["justification_of_purchase"]) . "</td>";
												echo "<td>" . htmlspecialchars($row["delivery_date"]) . "</td>";
												echo "<td>" . htmlspecialchars($row["supplier_name"]) . "</td>";
												echo "<td>" . htmlspecialchars($row["po_number"]) . "</td>";
												echo "<td>" . htmlspecialchars($row["po_date"]) . "</td>";
											?>
						    				</tr>
						    			</tbody>
						    		</table>
						    	</div>
						    	<br>

						    	<div class="table-responsive">
						    		<table class="table table-bordered">
						    			<thead>
						    				<tr>
						    					<td>PR Number</td>
						    					<td>Invoice Number</td>
						    					<td>Delivery Receipt</td>
						    					<td>Items Received By</td>
						    					<td>Remarks</td>
						    				</tr>
						    			</thead>
						    			<tbody>
						    				<tr>
						    					<?php
						    					echo "<td>" . htmlspecialchars($row["pr_number"]) . "</td>";
													echo "<td>" . htmlspecialchars($row["invoice_no"]) . "</td>";
									      	echo "<td>" . htmlspecialchars($row["delivery_receipt"]) . "</td>";
									      	echo "<td>" . htmlspecialchars($row["items_received_by"]) . "</td>";
									      	echo "<td>" . htmlspecialchars($row["remarks"]) . "</td>";
									      	?>
						    				</tr>
						    			</tbody>
						    		</table>
						    	</div>

						    	<?php
											}
										} else {
									?>
											<div class="table-responsive">
							    		<table class="table table-bordered">
							    			<thead>
							    				<tr>
							    					<td>Status</td>
							    				</tr>
							    			</thead>
							    			<tbody>
							    				<tr>
							    					<?php
							    					echo "<td>Item \"" . $qrCode . "\" not found</td>";
							    					?>
							    				</tr>
							    			</tbody>
							    		</table>
							    	</div>
							    <?php
										}

										$stmt->close();
										$conn->close();
									}
												
									?>

			        
								</div>
							</div>
						</div>
					</div>
				</div>
	    	</div>
	  	</div>
	</div>

  <!-- Edit Modal -->
  <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editModalLabel">Edit Item</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="editForm">
            <input type="hidden" id="edit_item_id" name="item_id">
            <div class="container">
              <div class="row">
                <div class="col-md-6 mb-4">
                  <div class="card">
                    <div class="card-header">
                      <h6 class="mb-0">Common Item Details</h6>
                    </div>
                    <div class="card-body">
                      <div class="form-group mb-3">
                        <label for="edit_item_name" class="form-label">Item Name:</label>
                        <input type="text" class="form-control" id="edit_item_name" name="item_name" required>
                      </div>

                      <div class="form-group mb-3">
                        <label for="edit_property" class="form-label">Property:</label>
                        <select class="form-select" id="edit_property" name="property" required>
                          <?php
                          $properties = ['ACES', 'AVLCI', 'APZ', 'AC3', 'AB1', 'APW', 'SPR', 'AHR', 'ABH', 'SIARGAO', 'AGL', 'ACHI', 'NEXGEN', 'CHRDY', 'AGB'];
                          foreach ($properties as $prop) {
                            echo "<option value=\"$prop\">$prop</option>";
                          }
                          ?>
                        </select>
                      </div>

                      <div class="form-group mb-3">
                        <label for="edit_order_batch_number" class="form-label">Order Batch Number:</label>
                        <input type="text" class="form-control" id="edit_order_batch_number" name="order_batch_number">
                      </div>

                      <div class="form-group mb-3">
                        <label for="edit_model_number" class="form-label">Model Number:</label>
                        <input type="text" class="form-control" id="edit_model_number" name="model_number">
                      </div>

                      <div class="form-group mb-3">
                        <label for="edit_warranty_coverage" class="form-label">Warranty Coverage (Months):</label>
                        <input type="number" class="form-control" id="edit_warranty_coverage" name="warranty_coverage">
                      </div>

                      <div class="form-group mb-3">
                        <label for="edit_brand" class="form-label">Brand:</label>
                        <input type="text" class="form-control" id="edit_brand" name="brand">
                      </div>

                      <div class="form-group mb-3">
                        <label for="edit_item_type" class="form-label">Item Type (Enter corresponding code):</label>
                        <input type="number" class="form-control" id="edit_item_type" name="item_type">
                      </div>

                      <div class="form-group mb-3">
                        <label for="edit_item_details" class="form-label">Item Details:</label>
                        <textarea class="form-control" id="edit_item_details" name="item_details" rows="4"></textarea>
                      </div>

                      <div class="form-group mb-3">
                        <label for="edit_status_description" class="form-label">Status Description:</label>
                        <input type="text" class="form-control" id="edit_status_description" name="status_description">
                      </div>

                      <div class="form-group mb-3">
                        <label for="edit_unit_price" class="form-label">Unit Price:</label>
                        <input type="number" step="0.01" class="form-control" id="edit_unit_price" name="unit_price">
                      </div>

                      <div class="form-group mb-3">
                        <label for="edit_serial_number" class="form-label">Serial Number:</label>
                        <input type="text" class="form-control" id="edit_serial_number" name="serial_number" required>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 mb-4">
                  <div class="card">
                    <div class="card-header">
                      <h6 class="mb-0">Purchase Details</h6>
                    </div>
                    <div class="card-body">
                      <div class="form-group mb-3">
                        <label for="edit_justification_of_purchase" class="form-label">Justification of Purchase:</label>
                        <textarea class="form-control" id="edit_justification_of_purchase" name="justification_of_purchase" rows="4"></textarea>
                      </div>

                      <div class="form-group mb-3">
                        <label for="edit_delivery_date" class="form-label">Delivery Date:</label>
                        <input type="date" class="form-control" id="edit_delivery_date" name="delivery_date">
                      </div>

                      <div class="form-group mb-3">
                        <label for="edit_supplier_name" class="form-label">Supplier Name:</label>
                        <textarea class="form-control" id="edit_supplier_name" name="supplier_name" rows="4"></textarea>
                      </div>

                      <div class="form-group mb-3">
                        <label for="edit_po_number" class="form-label">PO Number:</label>
                        <input type="text" class="form-control" id="edit_po_number" name="po_number">
                      </div>

                      <div class="form-group mb-3">
                        <label for="edit_po_date" class="form-label">PO Date:</label>
                        <input type="date" class="form-control" id="edit_po_date" name="po_date">
                      </div>

                      <div class="form-group mb-3">
                        <label for="edit_pr_number" class="form-label">PR Number:</label>
                        <input type="text" class="form-control" id="edit_pr_number" name="pr_number">
                      </div>

                      <div class="form-group mb-3">
                        <label for="edit_invoice_no" class="form-label">Invoice Number:</label>
                        <input type="text" class="form-control" id="edit_invoice_no" name="invoice_no">
                      </div>

                      <div class="form-group mb-3">
                        <label for="edit_delivery_receipt" class="form-label">Delivery Receipt:</label>
                        <input type="text" class="form-control" id="edit_delivery_receipt" name="delivery_receipt">
                      </div>

                      <div class="form-group mb-3">
                        <label for="edit_items_received_by" class="form-label">Items Received By:</label>
                        <input type="text" class="form-control" id="edit_items_received_by" name="items_received_by">
                      </div>

                      <div class="form-group mb-3">
                        <label for="edit_remarks" class="form-label">Remarks:</label>
                        <textarea class="form-control" id="edit_remarks" name="remarks" rows="4"></textarea>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" onclick="saveChanges()">Save changes</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Add Bootstrap Bundle JS for modal functionality -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
  
  <script type="text/javascript">
    let scanner = new Instascan.Scanner({video: document.getElementById('preview')});

    Instascan.Camera.getCameras().then(function(cameras){
      if(cameras.length > 0){
        scanner.start(cameras[0]);
      } else{
        alert('No cameras found');
      }
    }).catch(function(e){
      console.error(e);
    });

    scanner.addListener('scan',function(c){
      document.getElementById('qr_data').value=c;
      document.forms[0].submit()
    });
    
    function editItem(item) {
      // Populate form fields
      document.getElementById('edit_item_id').value = item.item_id;
      document.getElementById('edit_item_name').value = item.item_name;
      document.getElementById('edit_property').value = item.property;
      document.getElementById('edit_order_batch_number').value = item.order_batch_number;
      document.getElementById('edit_model_number').value = item.model_number;
      document.getElementById('edit_serial_number').value = item.serial_number;
      document.getElementById('edit_warranty_coverage').value = item.warranty_coverage;
      document.getElementById('edit_brand').value = item.brand;
      document.getElementById('edit_item_type').value = item.item_type;
      document.getElementById('edit_item_details').value = item.item_details;
      document.getElementById('edit_status_description').value = item.status_description;
      document.getElementById('edit_unit_price').value = item.unit_price;
      document.getElementById('edit_justification_of_purchase').value = item.justification_of_purchase;
      document.getElementById('edit_delivery_date').value = item.delivery_date;
      document.getElementById('edit_supplier_name').value = item.supplier_name;
      document.getElementById('edit_po_number').value = item.po_number;
      document.getElementById('edit_po_date').value = item.po_date;
      document.getElementById('edit_pr_number').value = item.pr_number;
      document.getElementById('edit_invoice_no').value = item.invoice_no;
      document.getElementById('edit_delivery_receipt').value = item.delivery_receipt;
      document.getElementById('edit_items_received_by').value = item.items_received_by;
      document.getElementById('edit_remarks').value = item.remarks;

      // Show modal
      new bootstrap.Modal(document.getElementById('editModal')).show();
    }

    function saveChanges() {
      const form = document.getElementById('editForm');
      const formData = new FormData(form);

      fetch('process/update_item_process.php', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          alert('Item updated successfully');
          location.reload();
        } else {
          alert('Error updating item: ' + data.message);
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the item.');
      });
    }
  </script>
</body>
</html>

