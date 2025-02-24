<?php
require_once 'process/db_connect.php';

// Start the session
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Initialize variables for sorting and pagination
$sort_column = isset($_GET['sort']) ? $_GET['sort'] : 'item_id';
$sort_order = isset($_GET['order']) ? $_GET['order'] : 'ASC';
$search = isset($_GET['search']) ? $_GET['search'] : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$items_per_page = isset($_GET['items_per_page']) ? (int)$_GET['items_per_page'] : 10;
$offset = ($page - 1) * $items_per_page;

// Base query
$base_query = "SELECT * FROM items";
$count_query = "SELECT COUNT(*) as total FROM items";
$where_clause = '';
$params = [];
$types = '';

// Add search condition if search term exists
if (!empty($search)) {
    $where_clause = " WHERE (item_id LIKE ? OR item_name LIKE ? OR property LIKE ? OR order_batch_number LIKE ? OR model_number LIKE ? OR serial_number LIKE ? OR warranty_coverage LIKE ? OR brand LIKE ? OR item_type LIKE ? OR item_details LIKE ? OR status_description LIKE ? OR unit_price LIKE ? OR justification_of_purchase LIKE ? OR delivery_date LIKE ? OR supplier_name LIKE ? OR po_number LIKE ? OR po_date LIKE ? OR pr_number LIKE ? OR invoice_no LIKE ? OR delivery_receipt LIKE ? OR items_received_by LIKE ? OR remarks LIKE ?)";
    $search_param = "%$search%";
    $params = array_fill(0, 22, $search_param);
    $types = str_repeat('s', 22);
}

// Get total records for pagination
$count_stmt = $conn->prepare($count_query . $where_clause);

if ($count_stmt === false) {
    die('Error in prepare statement: ' . $conn->error);
}
if (!empty($params)) {
    $count_stmt->bind_param($types, ...$params);
}
$count_stmt->execute();
$total_records = $count_stmt->get_result()->fetch_assoc()['total'];
$total_pages = ceil($total_records / $items_per_page);
?>
<html>
<head>
    <title>Test View Items</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/admin.css">
    <style>
        .sort-icon {
            font-size: 0.8em;
            margin-left: 5px;
        }
        .search-container {
            position: relative;
            margin-bottom: 1.5rem;
        }
        #searchInput {
            padding-right: 30px;
        }
        .clear-search {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #999;
        }
        .clear-search:hover {
            color: #333;
        }
        .container.mt-4 {
            margin-top: 2rem !important;
            margin-bottom: 2rem !important;
            max-width: 95%;
        }
        .table {
            margin-bottom: 2rem;
        }
        .table th,
        .table td {
            padding: 1rem 0.75rem;
            vertical-align: middle;
        }
        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
            white-space: nowrap;
        }
        .table-responsive {
            margin-top: 1rem;
            margin-bottom: 2rem;
            border-radius: 0.25rem;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .pagination {
            margin-top: 2rem;
        }
        .modal .form-group {
            margin-bottom: 1.5rem;
        }
        .modal .form-label {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }
        .modal .form-control,
        .modal .form-select {
            border: 1px solid #cbd5e0;
            padding: 0.625rem;
            border-radius: 0.375rem;
            background-color: #f8fafc;
            transition: all 0.3s ease;
        }
        .modal .form-control:focus,
        .modal .form-select:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            background-color: #ffffff;
        }
        .modal .card {
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        .modal .card-header {
            background-color: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            padding: 1rem;
        }
        .modal .card-header h6 {
            font-weight: 600;
            color: #1a365d;
        }
        .modal .card-body {
            padding: 1.5rem;
        }
    </style>
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
                        <li class="breadcrumb-item"><a href="admin_dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="admin_dashboard.php">Inventory</a></li>
                        <li class="breadcrumb-item active" aria-current="page">View Items</li>
                    </ol>
                </nav>
            </div>
            <div class="card-body">
<?php
// Prepare and execute the main query
$query = $base_query . $where_clause . " ORDER BY $sort_column $sort_order LIMIT ?, ?";
$stmt = $conn->prepare($query);

if ($stmt === false) {
    die('Error in prepare statement: ' . $conn->error);
}

// Add limit parameters
$params[] = $offset;
$params[] = $items_per_page;
$types .= 'ii';

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>

    <div class="container mt-4">
        <?php
        // Fetch users for dropdown
        $users_query = "SELECT username FROM users";
        $users_result = $conn->query($users_query);
        $users = [];
        while ($user = $users_result->fetch_assoc()) {
            $users[] = $user['username'];
        }
        ?>
        
        <!-- Search and Items per page controls -->
        <div class="row mb-3">
            <div class="col-md-4">
                <div class="search-container">
                    <input type="text" id="searchInput" class="form-control" placeholder="Search items..." value="<?php echo htmlspecialchars($search); ?>">
                    <?php if (!empty($search)): ?>
                        <span class="clear-search" onclick="clearSearch()">&times;</span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-4">
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center mb-0">
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>">
                                <a class="page-link" href="javascript:void(0)" onclick="goToPage(<?php echo $i; ?>)">
                                    <?php echo $i; ?>
                                </a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            </div>
            <div class="col-md-4 text-end">
                <select id="itemsPerPage" class="form-select d-inline-block w-auto" onchange="changeItemsPerPage(this.value)">
                    <option value="10" <?php echo $items_per_page == 10 ? 'selected' : ''; ?>>10 items</option>
                    <option value="25" <?php echo $items_per_page == 25 ? 'selected' : ''; ?>>25 items</option>
                    <option value="50" <?php echo $items_per_page == 50 ? 'selected' : ''; ?>>50 items</option>
                </select>
            </div>
        </div>

        <!-- Add Bootstrap Bundle JS for modal functionality -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Items Table -->
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <?php
                        $columns = [
                            'item_id' => 'ID',
                            'po_date' => 'PO Date',
                            'po_number' => 'PO Number',
                            'item_name' => 'Item Name',
                            'property' => 'Property',
                            'order_batch_number' => 'Order Batch',
                            'model_number' => 'Model Number',
                            'serial_number' => 'Serial Number',
                            'warranty_coverage' => 'Warranty',
                            'brand' => 'Brand',
                            'item_type' => 'Type',
                            'item_details' => 'Description',
                            'status_description' => 'Status',
                            'unit_price' => 'Unit Price',
                            'justification_of_purchase' => 'Purchase Justification',
                            'delivery_date' => 'Delivery Date',
                            'supplier_name' => 'Supplier',
                            'pr_number' => 'PR Number',
                            'invoice_no' => 'Invoice No',
                            'delivery_receipt' => 'Delivery Receipt',
                            'items_received_by' => 'Received By',
                            'remarks' => 'Remarks'
                        ];

                        foreach ($columns as $column => $label) {
                            $current_order = ($sort_column === $column) ? $sort_order : 'ASC';
                            $new_order = ($current_order === 'ASC') ? 'DESC' : 'ASC';
                            $sort_icon = '';
                            
                            if ($sort_column === $column) {
                                $sort_icon = ($sort_order === 'ASC') ? 
                                    '<i class="fa fa-sort-up sort-icon"></i>' : 
                                    '<i class="fa fa-sort-down sort-icon"></i>';
                            }

                            echo "<th>";
                            echo "<a href='javascript:void(0)' onclick='sort(\"$column\", \"$new_order\")' ";
                            echo "class='text-dark text-decoration-none'>";
                            echo "$label $sort_icon";
                            echo "</a>";
                            echo "</th>";
                        }
                        ?>
                    </tr>
                </thead>
                <tbody id="itemsTableBody">
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <?php echo htmlspecialchars($row['item_id']); ?>
                                <button type="button" class="btn btn-sm btn-primary" onclick="editItem(<?php echo htmlspecialchars(json_encode($row)); ?>)">
                                    <i class="fa fa-edit"></i> Edit
                                </button>
                            </td>
                            <td><?php echo htmlspecialchars($row['po_date']); ?></td>
                            <td><?php echo htmlspecialchars($row['po_number']); ?></td>
                            <td><?php echo htmlspecialchars($row['item_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['property']); ?></td>
                            <td><?php echo htmlspecialchars($row['order_batch_number']); ?></td>
                            <td><?php echo htmlspecialchars($row['model_number']); ?></td>
                            <td><?php echo htmlspecialchars($row['serial_number']); ?></td>
                            <td><?php echo htmlspecialchars($row['warranty_coverage']); ?></td>
                            <td><?php echo htmlspecialchars($row['brand']); ?></td>
                            <td><?php echo htmlspecialchars($row['item_type']); ?></td>
                            <td><?php echo htmlspecialchars($row['item_details']); ?></td>
                            <td><?php echo htmlspecialchars($row['status_description']); ?></td>
                            <td><?php echo htmlspecialchars($row['unit_price']); ?></td>
                            <td><?php echo htmlspecialchars($row['justification_of_purchase']); ?></td>
                            <td><?php echo htmlspecialchars($row['delivery_date']); ?></td>
                            <td><?php echo htmlspecialchars($row['supplier_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['pr_number']); ?></td>
                            <td><?php echo htmlspecialchars($row['invoice_no']); ?></td>
                            <td><?php echo htmlspecialchars($row['delivery_receipt']); ?></td>
                            <td><?php echo htmlspecialchars($row['items_received_by']); ?></td>
                            <td><?php echo htmlspecialchars($row['remarks']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>">
                        <a class="page-link" href="javascript:void(0)" onclick="goToPage(<?php echo $i; ?>)">
                            <?php echo $i; ?>
                        </a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        let searchTimeout;
        const searchDelay = 500; // Delay in milliseconds

        // Function to update URL parameters
        function updateUrlParams(params) {
            const url = new URL(window.location.href);
            Object.keys(params).forEach(key => {
                if (params[key] !== null && params[key] !== '') {
                    url.searchParams.set(key, params[key]);
                } else {
                    url.searchParams.delete(key);
                }
            });
            history.pushState({}, '', url);
            return url.search;
        }

        // Function to reload the page with current parameters
        function reloadWithParams() {
            location.reload();
        }

        // Search functionality
        document.getElementById('searchInput').addEventListener('input', function(e) {
            clearTimeout(searchTimeout);
            const searchTerm = e.target.value;
            
            searchTimeout = setTimeout(() => {
                updateUrlParams({ search: searchTerm, page: '1' });
                reloadWithParams();
            }, searchDelay);
        });

        // Clear search
        function clearSearch() {
            updateUrlParams({ search: null, page: '1' });
            reloadWithParams();
        }

        // Sorting functionality
        function sort(column, order) {
            updateUrlParams({ sort: column, order: order });
            reloadWithParams();
        }

        // Pagination functionality
        function goToPage(page) {
            updateUrlParams({ page: page });
            reloadWithParams();
        }

        // Items per page functionality
        function changeItemsPerPage(value) {
            updateUrlParams({ items_per_page: value, page: '1' });
            reloadWithParams();
        }
    </script>
    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Item</h5>
                    <button type="button" class="fa fa-times" data-bs-dismiss="modal" aria-label="Close"></button>
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
                                                <select class="form-select" id="edit_items_received_by" name="items_received_by">
                                                    <?php foreach ($users as $user): ?>
                                                        <option value="<?php echo htmlspecialchars($user); ?>"><?php echo htmlspecialchars($user); ?></option>
                                                    <?php endforeach; ?>
                                                </select>
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

    <script>
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
            alert('Error updating item');
        });
    }
    </script>
</body>
</html>