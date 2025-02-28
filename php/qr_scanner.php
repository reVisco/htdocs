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
												<a href="edit_item.php?id=<?=htmlspecialchars($row["item_id"]) ?>">
													<button type="button" class="form-control btn btn-primary rounded px-3 mb-3">
														Edit Item #<?=htmlspecialchars($row["item_id"]) ?>
													</button>
												</a>
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
  </script>
</body>
</html>

