<?php
  require 'process/session_start_process.php';
  require 'process/db_connect.php';

  // Get total items count
  $total_items_query = "SELECT COUNT(*) as total FROM items";
  $total_items_result = $conn->query($total_items_query);
  $total_items = $total_items_result->fetch_assoc()['total'];

  // Get categories count
  $categories_query = "SELECT COUNT(DISTINCT item_type) as total FROM items";
  $categories_result = $conn->query($categories_query);
  $total_categories = $categories_result->fetch_assoc()['total'];

  // Get low stock items count (assuming items with quantity less than 10 are low stock)
  $low_stock_query = "SELECT COUNT(*) as total FROM items WHERE order_batch_number < 10";
  $low_stock_result = $conn->query($low_stock_query);
  $low_stock_items = $low_stock_result->fetch_assoc()['total'];

  // Get recent activities (last 5 updates)
  $recent_activities_query = "SELECT * FROM items ORDER BY date_added DESC LIMIT 5";
  $recent_activities_result = $conn->query($recent_activities_query);
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
      .stats-card {
        transition: transform 0.3s;
      }
      .stats-card:hover {
        transform: translateY(-5px);
      }
      .stats-icon {
        font-size: 2.5rem;
        opacity: 0.8;
      }
    </style>
  </head>
  <body>
    <div class="wrapper d-flex align-items-stretch">
      <?php include 'admin_nav.php';?>
      <div id="content" class="p-2 pt-5">
        <div class="card mb-4">
          <div class="card-header">
            <h3>Admin Dashboard</h3>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page"><a href="admin_dashboard.php">Dashboard</a></li>
              </ol>
            </nav>
          </div>
          <div class="card-body">
            <!-- Statistics Cards -->
            <div class="row mb-4">
              <div class="col-md-3">
                <div class="card stats-card bg-primary text-white">
                  <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                      <div>
                        <h6 class="text-uppercase mb-1">Total Items</h6>
                        <h3 class="mb-0"><?php echo $total_items; ?></h3>
                      </div>
                      <div class="stats-icon">
                        <i class="fa fa-archive"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="card stats-card bg-success text-white">
                  <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                      <div>
                        <h6 class="text-uppercase mb-1">Categories</h6>
                        <h3 class="mb-0"><?php echo $total_categories; ?></h3>
                      </div>
                      <div class="stats-icon">
                        <i class="fa fa-tags"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="card stats-card bg-warning text-white">
                  <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                      <div>
                        <h6 class="text-uppercase mb-1">Low Stock Items</h6>
                        <h3 class="mb-0"><?php echo $low_stock_items; ?></h3>
                      </div>
                      <div class="stats-icon">
                        <i class="fa fa-exclamation-triangle"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="card stats-card bg-info text-white">
                  <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                      <div>
                        <h6 class="text-uppercase mb-1">Today's Date</h6>
                        <h3 class="mb-0"><?php echo date('d M Y'); ?></h3>
                      </div>
                      <div class="stats-icon">
                        <i class="fa fa-calendar"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Charts Row -->
            <div class="row mb-4">
              <div class="col-md-8">
                <div class="card">
                  <div class="card-header">
                    <h5>Inventory Overview</h5>
                  </div>
                  <div class="card-body">
                    <canvas id="inventoryChart"></canvas>
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="card">
                  <div class="card-header">
                    <h5>Recent Activities</h5>
                  </div>
                  <div class="card-body">
                    <div class="list-group">
                      <?php while($activity = $recent_activities_result->fetch_assoc()): ?>
                        <div class="list-group-item">
                          <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1"><?php echo $activity['item_name']; ?></h6>
                            <small><?php echo date('d M', strtotime($activity['date_added'])); ?></small>
                          </div>
                          <p class="mb-1">Quantity: <?php echo $activity['order_batch_number']; ?></p>
                        </div>
                      <?php endwhile; ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script>
      // Sample data for the chart
      const ctx = document.getElementById('inventoryChart').getContext('2d');
      new Chart(ctx, {
        type: 'line',
        data: {
          labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
          datasets: [{
            label: 'Total Items',
            data: [65, 59, 80, 81, 56, <?php echo $total_items; ?>],
            borderColor: 'rgb(75, 192, 192)',
            tension: 0.1
          }]
        },
        options: {
          responsive: true,
          scales: {
            y: {
              beginAtZero: true
            }
          }
        }
      });
    </script>
  </body>
</html>

<?php
  $conn->close();
?>