<?php
  require 'process/session_start_process.php';
?>

<!doctype html>
<html lang="en">
  <head>
  	
	<title>Admin Dashboard</title>
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
  					<h3>Admin Dashboard</h3>

	      			<nav aria-label="breadcrumb">
	      				<ol class="breadcrumb">
	      					<li class="breadcrumb-item active" aria-current="page"><a href="admin_dashboard.php">Dashboard</a></li>
	      				</ol>
	      			</nav>
  				</div>
  				<div class="card-body">
  					<div class="row">
	      				<div class="col p-4 mb-3 mr-4">
	      					<div class="card">
	      						<div class="card-header">
	      							<h4>Check Item</h4>
	      						</div>
	      						<div class="card-body">
	      							<p>Available Items</p>
	      						</div>
	      					</div>
	      				</div>
	      				<div class="col p-4 mb-3 mr-4">
	      					<div class="card">
	      						<div class="card-header">
		      						<h4>Inventory</h4>
		      					</div>
		      					<div class="card-body">
		      						<p>Available Items</p>
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