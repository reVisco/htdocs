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
    <!-- Add Bootstrap Modal CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
                                    <th>Full Name</th>
                                    <th>Email</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            <?php
                            while($row = $result->fetch_assoc()){
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row["user_id"])?></td>
                                <td><?php echo htmlspecialchars($row["first_name"] . ' ' . $row["last_name"])?></td>
                                <td><?php echo htmlspecialchars($row["email"])?></td>
                                <td>
                                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editProfileModal" 
                                        data-userid="<?php echo htmlspecialchars($row["user_id"])?>"
                                        data-firstname="<?php echo htmlspecialchars($row["first_name"])?>"
                                        data-lastname="<?php echo htmlspecialchars($row["last_name"])?>">
                                        Edit Profile
                                    </button>
                                </td>
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

    <!-- Edit Profile Modal -->
    <div class="modal fade" id="editProfileModal" tabindex="-1" role="dialog" aria-labelledby="editProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editProfileForm" action="process/update_profile_process.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="user_id" id="editUserId">
                        <div class="form-group">
                            <label for="firstName">First Name</label>
                            <input type="text" class="form-control" id="firstName" name="first_name" required>
                        </div>
                        <div class="form-group">
                            <label for="lastName">Last Name</label>
                            <input type="text" class="form-control" id="lastName" name="last_name" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add necessary JavaScript -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#editProfileModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var userId = button.data('userid');
            var firstName = button.data('firstname');
            var lastName = button.data('lastname');
            
            var modal = $(this);
            modal.find('#editUserId').val(userId);
            modal.find('#firstName').val(firstName);
            modal.find('#lastName').val(lastName);
        });
    });
    </script>
    </body>
</html>