<?php
require 'process/session_start_process.php';
require 'process/db_connect.php';
?>

<!DOCTYPE html>
<html>

<head>
    <title>View Items</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">


    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/admin.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
    $(document).ready(function(){
        $('.search-box input[type="text"]').on("keyup input", function(){
            /* Get input value on change */
            var inputVal = $(this).val();
            var resultDropdown = $(this).siblings(".result");
            if(inputVal.length){
                $.get("backend-search.php", {term: inputVal}).done(function(data){
                    // Display the returned data in browser
                    resultDropdown.html(data);
                });
            } else{
                resultDropdown.empty();
            }
        });
        
        // Set search input value on click of result item
        $(document).on("click", ".result p", function(){
            $(this).parents(".search-box").find('input[type="text"]').val($(this).text());
            $(this).parent(".result").empty();
        });
    });
    </script>
</head>

<body>
    <div class="wrapper d-flex align-items-stretch">
        <?php include 'admin_nav.php'; ?>
        <div id="content" class="p-2 pt-5">
            <div class="card">
                <div class="card-header">
                    <h3>View Items</h3>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="admin_dashboard.php">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">
                                <a href="inventory.php">Inventory</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                View Items
                            </li>
                        </ol>
                    </nav>
                    <div class="search-box">
                        <input type="text" autocomplete="off" placeholder="Search item..." />
                        <div class="result"></div>
                    </div>
                </div>
                <div class="card-body">
                    <?php

                    // Dropdown Options
                    $itemsPerPageOptions = [5, 10, 20, 50];

                    // Pagination settings (Get items per page from dropdown if available)
                    $itemsPerPage = isset($_GET['items_per_page']) ? (int) $_GET['items_per_page'] : $itemsPerPageOptions[0]; // Default to the first option
                    $currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1;
                    $offset = ($currentPage - 1) * $itemsPerPage;

                    // Count total number of items
                    $countQuery = $conn->prepare("SELECT COUNT(*) AS total_items FROM items");
                    $countQuery->execute();
                    // Before the error line, add:
                    $countResult = $countQuery->get_result();

                    // Now your original line can be fixed:
                    $total_items = $countResult->fetch_assoc()['total_items'];

                    // Modified SQL query with pagination
                    $stmt = $conn->prepare("SELECT * FROM items LIMIT $offset, $itemsPerPage");
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($result->num_rows > 0) {
                        ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover">
                                <div class="card">
                                    <div class="card-header">
                                        <form method="GET">
                                            <select name="items_per_page" onchange="this.form.submit()">
                                                <?php foreach ($itemsPerPageOptions as $option): ?>
                                                    <option value="<?= $option ?>" <?= ($option == $itemsPerPage) ? 'selected' : '' ?>>
                                                        <?= $option ?> items per page
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </form>

                                        <?php
                                        // Pagination links
                                        
                                        $total_pages = ceil($total_items / $itemsPerPage);

                                        echo "<nav>";
                                        echo "<ul class='pagination'>";
                                        // First page link
                                        if ($currentPage > 1) {
                                            echo "<li class='page_item'><a href='?page=1' class='page-link'>First</a></li>";
                                        }
                                        // Previous page link
                                        if ($currentPage > 1) {
                                            $prevPage = $currentPage - 1;
                                            echo "<li class='page_item'><a href='?page=$prevPage' class='page-link'>Previous</a></li>";
                                        }
                                        // Numbered page links
                                        for ($i = max(1, $currentPage - 5); $i <= min($currentPage + 5, $total_pages); $i++) {
                                            if ($i == $currentPage) {
                                                echo "<li class='page_item active'><a href='?page=$i' class='page-link'>$i</a></li>";
                                            } else {
                                                echo "<li class='page_item'><a href='?page=$i' class='page-link'>$i</a></li>";
                                            }
                                        }


                                        // Next page link
                                        if ($currentPage < $total_pages) {
                                            $nextPage = $currentPage + 1;
                                            echo "<li class='page_item'><a href='?page=$nextPage' class='page-link'>Next</a></li>";
                                        }
                                        // Last page link
                                        if ($currentPage < $total_pages) {
                                            echo "<li class='page_item'><a href='?page=$total_pages' class='page-link'>Last</a></li>";
                                        }

                                        echo "</ul>";
                                        echo "</nav>";

                                        ?>
                                    </div>
                                    <div class="card-body">
                                    <thead class="thead-dark">
                                    <tr>
                                        <th>Edit Item</th>
                                        <th>Item ID</th>
                                        <th>Item Name</th>
                                        <th>Property</th>
                                        <th>Serial Number</th>
                                        <th>Warranty Coverage</th>
                                        <th>Brand</th>
                                        <th>Item Type</th>
                                        <th>Item Details</th>
                                        <th>Status</th>
                                        <th>Unit Price</th>
                                        <th>Justification of Purchase</th>
                                        <th>Delivery Date</th>
                                        <th>Supplier Name</th>
                                        <th>PO Number</th>
                                        <th>PO Date</th>
                                        <th>PR Number</th>
                                        <th>Invoice Number</th>
                                        <th>Delivery Receipt</th>
                                        <th>Items Received By</th>
                                        <th>Remarks</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    <?php
                                    while ($row = $result->fetch_assoc()) {
                                        ?>
                                        <tr>
                                            <td><a href="edit_item.php?id=<?= htmlspecialchars($row["item_id"]) ?>"><button
                                                        type="button"
                                                        class="form-control btn btn-primary rounded px-3">Edit</button></a></td>
                                            <td>
                                                <?php echo htmlspecialchars($row["item_id"]) ?>
                                            </td>
                                            <td>
                                                <?php echo htmlspecialchars($row["item_name"]) ?>
                                            </td>
                                            <td>
                                                <?php echo htmlspecialchars($row["property"]) ?>
                                            </td>
                                            <td>
                                                <?php echo htmlspecialchars($row["serial_number"]) ?>
                                            </td>
                                            <td>
                                                <?php echo htmlspecialchars($row["warranty_coverage"]) ?>
                                            </td>
                                            <td>
                                                <?php echo htmlspecialchars($row["brand"]) ?>
                                            </td>
                                            <td>
                                                <?php echo htmlspecialchars($row["item_type"]) ?>
                                            </td>
                                            <td>
                                                <?php echo htmlspecialchars($row["item_details"]) ?>
                                            </td>
                                            <td>
                                                <?php echo htmlspecialchars($row["status_description"]) ?>
                                            </td>
                                            <td>
                                                <?php echo htmlspecialchars($row["unit_price"]) ?>
                                            </td>
                                            <td>
                                                <?php echo htmlspecialchars($row["justification_of_purchase"]) ?>
                                            </td>
                                            <td>
                                                <?php echo htmlspecialchars($row["delivery_date"]) ?>
                                            </td>
                                            <td>
                                                <?php echo htmlspecialchars($row["supplier_name"]) ?>
                                            </td>
                                            <td>
                                                <?php echo htmlspecialchars($row["po_number"]) ?>
                                            </td>
                                            <td>
                                                <?php echo htmlspecialchars($row["po_date"]) ?>
                                            </td>
                                            <td>
                                                <?php echo htmlspecialchars($row["pr_number"]) ?>
                                            </td>
                                            <td>
                                                <?php echo htmlspecialchars($row["invoice_no"]) ?>
                                            </td>
                                            <td>
                                                <?php echo htmlspecialchars($row["delivery_receipt"]) ?>
                                            </td>
                                            <td>
                                                <?php echo htmlspecialchars($row["items_received_by"]) ?>
                                            </td>
                                            <td>
                                                <?php echo htmlspecialchars($row["remarks"]) ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                                    </div>
                                </div>

                                
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

                    ?>


                </div>
            </div>

        </div>
    </div>

</body>

</html>