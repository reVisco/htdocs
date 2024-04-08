<?php
  require 'process/session_start_process.php';
  require 'process/db_connect.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Profile</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">
 
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/admin.css">
    </head>
        <body>
        <div class="wrapper d-flex align-items-stretch">
            <?php include 'admin_nav.php';?>
            <div id="content"  class="p-2 pt-5">
                <div class="card">
                    <div class="card-header">
                        <h3>User Profile</h3>

                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item" aria-current="page"><a href="admin_dashboard.php">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-currentp="page">
                                    User Profile
                                </li>
                            </ol>
                        </nav>
                    </div>
                    <div class="card-body">
                        <?php
                        if (isset($_SESSION['user_id'])) {
                          $userId = $_SESSION['user_id'];
                        }    
                        // Prepare the statement
                        $stmt = $conn->prepare("SELECT * FROM users where user_id = ?");
                        $stmt->bind_param("i", $userId); // Bind the parameter

                        // Execute the statement
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if($result->num_rows>0){
                        ?>  
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>User ID</th>
                                    <th>User Name</th>
                                    <th>User Email</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            <?php
                            while($row = $result->fetch_assoc()){
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row["user_id"])?></td>
                                <td><?php echo htmlspecialchars($row["username"])?></td>
                                <td><?php echo htmlspecialchars($row["email"])?></td>
                            </tr>
                            <?php
                            }
                            ?>
                            </tbody>
                        </table>
                        </div>
                        <br>
                        <?php
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
                                        echo "<td>Profile not found</td>";
                                        ?>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    <?php
                        }

                        $stmt->close();
                        $conn->close();
                                                    
                    ?>
                    </div>
                </div>
            </div>
        </div>

    </body>
</html>