<?php
require 'process/session_start_process.php';
  require 'process/test_db_connect.php';
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
	    <?php include 'test_admin_nav.php';?>
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
											$stmt = $conn->prepare("SELECT i.item_id, i.item_details, i.serial_number, i.status, i.qr_code_data,
                                                b.brand_name, it.item_type_name, p.property_name, l.location_name,
                                                ip.unit_price, ip.total_amount, ip.delivery_date, ip.remarks, per.person_name AS received_by_name,
                                                iss.issued_by, iss.issued_to, iss.date_issued, d.department_name,
                                                per_issued_by.person_name AS issued_by_name, per_issued_to.person_name AS issued_to_name,
                                                w.warranty_ends, w.warranty_slip_no, po.po_number, po.po_date, s.supplier_name,
                                                pr.pr_number, pr.done_or_no_pr, inv.invoice_number
                                                FROM Items i
                                                LEFT JOIN Brands b ON i.brand_id = b.brand_id
                                                LEFT JOIN Item_Types it ON i.item_type_id = it.item_type_id
                                                LEFT JOIN Properties p ON i.property_id = p.property_id
                                                LEFT JOIN Locations l ON i.location_id = l.location_id
                                                LEFT JOIN Item_Purchases ip ON i.item_id = ip.item_id
                                                LEFT JOIN Personnel per ON ip.received_by = per.person_id
                                                LEFT JOIN Item_Issuances iss ON i.item_id = iss.item_id
                                                LEFT JOIN Personnel per_issued_by ON iss.issued_by = per_issued_by.person_id
                                                LEFT JOIN Personnel per_issued_to ON iss.issued_to = per_issued_to.person_id
                                                LEFT JOIN Departments d ON iss.department_id = d.department_id
                                                LEFT JOIN Warranties w ON i.item_id = w.item_id
                                                LEFT JOIN Purchase_Orders po ON ip.po_id = po.po_id
                                                LEFT JOIN Suppliers s ON po.supplier_id = s.supplier_id
                                                LEFT JOIN Purchase_Requests pr ON ip.pr_id = pr.pr_id
                                                LEFT JOIN Invoices inv ON ip.invoice_id = inv.invoice_id
                                                WHERE i.qr_code_data = ?");
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
												<!-- <a href="edit_item.php?id=<?=htmlspecialchars($row["item_id"]) ?>">
													<button type="button" class="form-control btn btn-primary rounded px-3 mb-3">
														Edit Item #<?=htmlspecialchars($row["item_id"]) ?>
													</button>
												</a> -->
												<?php
													echo "<td>" . htmlspecialchars($row["item_id"]) . "</td>";
													echo "<td>" . htmlspecialchars($row["item_details"]) . "</td>";
													echo "<td>" . htmlspecialchars($row["property_name"]) . "</td>";
													echo "<td>" . htmlspecialchars($row["serial_number"]) . "</td>";
													echo "<td>" . htmlspecialchars($row["warranty_ends"]) . "</td>";
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
													echo "<td>" . htmlspecialchars($row["brand_name"]) . "</td>";
													echo "<td>" . htmlspecialchars($row["item_type_name"]) . "</td>";
													echo "<td>" . htmlspecialchars($row["item_details"]) . "</td>";
													echo "<td>" . htmlspecialchars($row["status"]) . "</td>";
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
						    				</tr>
						    			</thead>
						    			<tbody>
						    				<tr>
						    					<?php
													// echo "<td>" . htmlspecialchars($row["justification_of_purchase"]) . "</td>";
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
						    				</tr>
						    			</thead>
						    			<tbody>
						    				<tr>
						    					<?php
						    					echo "<td>" . htmlspecialchars($row["pr_number"]) . "</td>";
													echo "<td>" . htmlspecialchars($row["invoice_number"]) . "</td>";
									      	echo "<td>" . htmlspecialchars($row["received_by_name"]) . "</td>";
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

  <script type="text/javascript">
    let scanner = new Instascan.Scanner({video: document.getElementById('preview')});

    // Function to handle camera permissions
    async function requestCameraPermission() {
      try {
        const stream = await navigator.mediaDevices.getUserMedia({ video: true });
        stream.getTracks().forEach(track => track.stop()); // Stop the stream after permission check
        return true;
      } catch (error) {
        console.error('Camera permission error:', error);
        return false;
      }
    }

    // Function to show camera permission instructions
    function showCameraInstructions() {
      const preview = document.getElementById('preview');
      const message = document.createElement('div');
      message.style.padding = '20px';
      message.style.textAlign = 'center';
      message.innerHTML = `
        <div class="alert alert-warning">
          <h4>Camera Access Required</h4>
          <p>Please enable camera access to use the QR scanner. Here's how:</p>
          <ol style="text-align: left;">
            <li>Click the camera icon in your browser's address bar</li>
            <li>Select "Allow" for camera access</li>
            <li>Refresh the page after enabling access</li>
          </ol>
          <button onclick="window.location.reload()" class="btn btn-primary mt-3">Refresh Page</button>
        </div>
      `;
      preview.parentNode.insertBefore(message, preview);
      preview.style.display = 'none';
    }

    // Initialize scanner with permission check
    async function initializeScanner() {
      const hasPermission = await requestCameraPermission();
      
      if (hasPermission) {
        Instascan.Camera.getCameras().then(function(cameras) {
          if (cameras.length > 0) {
            scanner.start(cameras[0]);
          } else {
            alert('No cameras found on your device');
          }
        }).catch(function(e) {
          console.error(e);
          alert('Error accessing camera: ' + e.message);
        });
      } else {
        showCameraInstructions();
      }
    }

    // Start the initialization process
    initializeScanner();

    scanner.addListener('scan', function(c) {
      document.getElementById('qr_data').value = c;
      document.forms[0].submit();
    });
  </script>
</body>
</html>

