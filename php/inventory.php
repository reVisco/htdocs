<?php
  require 'process/session_start_process.php';
?>

<html>
	<head>
		<title>Inventory</title>
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
      					<h3>Inventory</h3>
		      			<nav aria-label="breadcrumb">
		      				<ol class="breadcrumb">
		      					<li class="breadcrumb-item"><a href="admin_dashboard.php">Dashboard</a></li>
		      					<li class="breadcrumb-item active" aria-current="page">Inventory</li>
		      				</ol>
		      			</nav>
      				</div>
      				<div class="card-body">
      					<div class="row">
		                  	<div class="col p-4 mb-3 mr-4">
			                  	<div class="card">
			                  		<div class="card-header">
			                  			<h4>View Items</h4>
			                  		</div>
			                  		<div class="card-body">
			                  			<p>View different Items in Database</p>
			          					<a href="view_items.php"><button type="button" class="btn btn-primary">View</button></a>
			                  		</div>
			                  	</div>
		        			</div>
		          			
			          		<div class="col p-4 mb-3">
			          			<div class="card">
			          				<div class="card-header">
			          					<h4>Upload Items</h4>
			          				</div>
			          				<div class="card-body">
			          					<p>Upload Items in Database</p>
			          					<a href="upload_items.php"><button type="button" class="btn btn-primary">Upload</button></a>
			          				</div>
			          			</div>
			          		</div>
			          	</div>

			          	<div class="row">
			    			<div class="col p-4 mb-3 mr-4">
			    				<div class="card">
			    					<div class="card-header">
			    						<h4>Update Items</h4>
			    					</div>
			    					<div class="card-body">
			    						<p>Update Items in Database.</p>
				      					<button type="button" class="btn btn-primary">Update</button>
			    					</div>
			    				</div>
			    			</div>
			    			<div class="col p-4 mb-3">
			    				<div class="card">
			    					<div class="card-header">
			    						<h4>Delete Items</h4>
			    					</div>
			    					<div class="card-body">
			    						<p>Delete Items in Database.</p>
				      					<button type="button" class="btn btn-primary">Delete</button>
			    					</div>
			    				</div>		      			
			    			</div>
			            </div>

      				</div>
      			</div>
      			
      		</div>
		</div>

	</body>
</html>

<?php
$conn->close();
?>