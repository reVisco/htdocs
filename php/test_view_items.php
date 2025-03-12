<?php
require 'process/session_start_process.php';
require 'process/test_db_connect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Inventory Management</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/admin.css">
    <style>
        .modal-dialog { max-width: 80%; }
        .dataTables_filter { margin-bottom: 15px; }
        .action-buttons { white-space: nowrap; }
        #inventoryTable { font-size: 14px; }
        #inventoryTable th, #inventoryTable td { white-space: nowrap; }
        .form-control {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            background-color: #fff;
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
        .form-control:hover {
            background-color: #fff;
            border-color: #ced4da;
        }
        .card { box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .card-header {
            background-color: #f1f4f6;
            border-bottom: 2px solid #e3e6f0;
        }
        .form-label { font-weight: 500; color: #495057; }
        .form-group { position: relative; }
        textarea.form-control { min-height: 100px; }
    </style>
</head>
<body>
    <div class="wrapper d-flex align-items-stretch">
        <?php include 'test_admin_nav.php'; ?>
        <div id="content" class="p-2 pt-5">
            <div class="card">
                <div class="card-header">
                    <h3>Inventory Management</h3>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="admin_dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Inventory</li>
                        </ol>
                    </nav>
                </div>
                
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col">
                            <input type="text" id="customSearch" class="form-control" placeholder="e.g., item_id:515, brand:Lenovo">
                        </div>
                        <div class="col">
                            <button id="applyCustomSearch" class="btn btn-primary">Apply Filter</button>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <button id="downloadQR" class="btn btn-success" disabled>Download QR Code(s)</button>
                            <button id="deleteItems" class="btn btn-danger" disabled>Delete Item(s)</button>
                        </div>
                    </div>
                    <table id="inventoryTable" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="selectAll"> Select All</th>
                                <th>Actions</th>
                                <th>Item ID</th>
                                <th>Delivery Date</th>
                                <th>Item Details</th>
                                <th>Serial Number</th>
                                <th>Brand</th>
                                <th>Item Type</th>
                                <th>Property</th>
                                <th>Location</th>
                                <th>Status</th>
                                <th>Unit Price</th>
                                <th>Total Amount</th>
                                <th>Received By</th>
                                <th>Issued By</th>
                                <th>Issued To</th>
                                <th>Department</th>
                                <th>Date Issued</th>
                                <th>Warranty Ends</th>
                                <th>PO Number</th>
                                <th>Supplier</th>
                                <th>PR Number</th>
                                <th>Invoice Number</th>
                                <th>Done or No PR</th> <!-- Added Done or No PR column -->
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm">
                        <input type="hidden" id="editItemId">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0">Common Item Details</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group mb-3">
                                                <label for="editItemDetails" class="form-label">Item Details</label>
                                                <input type="text" class="form-control" id="editItemDetails" required>
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="editSerialNumber" class="form-label">Serial Number</label>
                                                <input type="text" class="form-control" id="editSerialNumber">
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="editBrandName" class="form-label">Brand</label>
                                                <input type="text" class="form-control" id="editBrandName">
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="editItemTypeName" class="form-label">Item Type</label>
                                                <input type="text" class="form-control" id="editItemTypeName">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0">Location & Status</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group mb-3">
                                                <label for="editPropertyName" class="form-label">Property</label>
                                                <input type="text" class="form-control" id="editPropertyName">
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="editLocationName" class="form-label">Location</label>
                                                <input type="text" class="form-control" id="editLocationName">
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="editStatus" class="form-label">Status</label>
                                                <input type="text" class="form-control" id="editStatus">
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="editDepartmentName" class="form-label">Department</label>
                                                <input type="text" class="form-control" id="editDepartmentName">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0">Financial Details</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group mb-3">
                                                <label for="editUnitPrice" class="form-label">Unit Price</label>
                                                <input type="number" step="0.01" class="form-control" id="editUnitPrice">
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="editTotalAmount" class="form-label">Total Amount</label>
                                                <input type="number" step="0.01" class="form-control" id="editTotalAmount">
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="editPoNumber" class="form-label">PO Number</label>
                                                <input type="text" class="form-control" id="editPoNumber">
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="editPoDate" class="form-label">PO Date</label>
                                                <input type="date" class="form-control" id="editPoDate">
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="editPrNumber" class="form-label">PR Number</label>
                                                <input type="text" class="form-control" id="editPrNumber">
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="editDoneOrNoPr" class="form-label">Done or No PR</label> <!-- Added field -->
                                                <input type="text" class="form-control" id="editDoneOrNoPr">
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="editInvoiceNumber" class="form-label">Invoice Number</label>
                                                <input type="text" class="form-control" id="editInvoiceNumber">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0">Warranty & Delivery</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group mb-3">
                                                <label for="editDeliveryDate" class="form-label">Delivery Date</label>
                                                <input type="date" class="form-control" id="editDeliveryDate">
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="editWarrantyEnds" class="form-label">Warranty Ends</label>
                                                <input type="date" class="form-control" id="editWarrantyEnds">
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="editWarrantySlipNo" class="form-label">Warranty Slip No</label>
                                                <input type="text" class="form-control" id="editWarrantySlipNo">
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="editSupplierName" class="form-label">Supplier</label>
                                                <input type="text" class="form-control" id="editSupplierName">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 mb-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0">Assignment Details</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="editReceivedByName" class="form-label">Received By</label>
                                                        <input type="text" class="form-control" id="editReceivedByName">
                                                    </div>
                                                    <div class="form-group mb-3">
                                                        <label for="editIssuedByName" class="form-label">Issued By</label>
                                                        <input type="text" class="form-control" id="editIssuedByName">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="editIssuedToName" class="form-label">Issued To</label>
                                                        <input type="text" class="form-control" id="editIssuedToName">
                                                    </div>
                                                    <div class="form-group mb-3">
                                                        <label for="editDateIssued" class="form-label">Date Issued</label>
                                                        <input type="date" class="form-control" id="editDateIssued">
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group mb-3">
                                                        <label for="editRemarks" class="form-label">Remarks</label>
                                                        <textarea class="form-control" id="editRemarks" rows="3"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 text-end">
                                    <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
    <script>
        // Define column mappings for user-friendly filter names
        const columnMap = {
            'brand': 'brand_name',
            'type': 'item_type_name',
            'item': 'item_details',
            'property': 'property_name',
            'location': 'location_name',
            'department': 'department_name',
            'supplier': 'supplier_name',
            'po': 'po_number',
            'pr': 'pr_number',
            'invoice': 'invoice_number',
            'done_or_no_pr': 'done_or_no_pr' // Added for custom search
        };

        // Define column indices based on DataTable columns array (0-based index)
        const columnIndices = {
            'item_id': 2,
            'delivery_date': 3,
            'item_details': 4,
            'serial_number': 5,
            'brand_name': 6,
            'item_type_name': 7,
            'property_name': 8,
            'location_name': 9,
            'status': 10,
            'unit_price': 11,
            'total_amount': 12,
            'received_by_name': 13,
            'issued_by_name': 14,
            'issued_to_name': 15,
            'department_name': 16,
            'date_issued': 17,
            'warranty_ends': 18,
            'po_number': 19,
            'supplier_name': 20,
            'pr_number': 21,
            'invoice_number': 22,
            'done_or_no_pr': 23 // Added index for new column
        };

        // Columns requiring exact matches (e.g., numbers, dates)
        const exactMatchColumns = ['item_id', 'unit_price', 'total_amount', 'delivery_date', 'date_issued', 'warranty_ends'];

        // Function to apply custom search filters
        function applyCustomSearch() {
            const searchStr = $('#customSearch').val().trim();
            const table = $('#inventoryTable').DataTable();

            if (searchStr === '') {
                table.search('').columns().search('').draw();
                return;
            }

            const filters = searchStr.split(',').map(filter => filter.trim());
            table.search('').columns().search('');

            filters.forEach(filter => {
                if (filter.includes(':')) {
                    const [col, val] = filter.split(':', 2).map(s => s.trim());
                    const mappedCol = columnMap[col] || col;
                    if (columnIndices[mappedCol] !== undefined) {
                        const columnIndex = columnIndices[mappedCol];
                        if (exactMatchColumns.includes(mappedCol)) {
                            table.column(columnIndex).search('^' + val + '$', true, false);
                        } else {
                            table.column(columnIndex).search(val);
                        }
                    }
                } else {
                    table.search(filter);
                }
            });

            table.draw();
        }

        // Bind the function to the button click
        $('#applyCustomSearch').on('click', applyCustomSearch);

        $(document).ready(function() {
            let table = $('#inventoryTable').DataTable({
                processing: true,
                serverSide: false,
                ajax: {
                    url: 'process/test_view_items_process.php',
                    type: 'POST',
                    data: function(d) {
                        d.searchValue = $('#searchInput').val() || '';
                        d.filterColumn = $('#filterColumn').val() || 'item_id';
                        return d;
                    }
                },
                columns: [
                    { data: null, render: function(data, type, row) {
                        return `<input type="checkbox" class="itemCheckbox" value="${data.item_id || ''}">`;
                    }},
                    { data: null, render: function(data, type, row) {
                        return `<button class="btn btn-info btn-sm editBtn" data-id="${data.item_id || ''}">Edit</button>`;
                    }},
                    { data: 'item_id' },
                    { data: 'delivery_date' },
                    { data: 'item_details' },
                    { data: 'serial_number' },
                    { data: 'brand_name' },
                    { data: 'item_type_name' },
                    { data: 'property_name' },
                    { data: 'location_name' },
                    { data: 'status' },
                    { data: 'unit_price' },
                    { data: 'total_amount' },
                    { data: 'received_by_name' },
                    { data: 'issued_by_name' },
                    { data: 'issued_to_name' },
                    { data: 'department_name' },
                    { data: 'date_issued' },
                    { data: 'warranty_ends' },
                    { data: 'po_number' },
                    { data: 'supplier_name' },
                    { data: 'pr_number' },
                    { data: 'invoice_number' },
                    { data: 'done_or_no_pr' } // Added new column
                ],
                pageLength: 10,
                lengthMenu: [10, 25, 50, 100],
                scrollX: true,
                dom: '<"row"<"col-sm"f><"col-sm"l>>rtip'
            });

            table.ajax.reload();

            $('#searchInput').on('keypress', function(e) {
                if (e.which === 13) {
                    table.ajax.reload();
                }
            });

            $('#selectAll').click(function() {
                $('.itemCheckbox').prop('checked', this.checked);
                updateButtonState();
            });

            $(document).on('click', '.itemCheckbox', function() {
                updateButtonState();
            });

            function updateButtonState() {
                let checkedCount = $('.itemCheckbox:checked').length;
                $('#downloadQR').prop('disabled', checkedCount === 0);
                $('#deleteItems').prop('disabled', checkedCount === 0);
            }

            $('#downloadQR').click(function() {
                let selectedIds = $('.itemCheckbox:checked').map(function() {
                    return $(this).val();
                }).get();
                if (selectedIds.length > 0) {
                    $.ajax({
                        url: 'process/test_download_qr_codes_process.php',
                        type: 'POST',
                        data: { item_ids: selectedIds },
                        xhrFields: { responseType: 'blob' },
                        success: function(data, status, xhr) {
                            let blob = new Blob([data], { type: 'application/zip' });
                            let link = document.createElement('a');
                            link.href = window.URL.createObjectURL(blob);
                            link.download = 'qr_codes.zip';
                            document.body.appendChild(link);
                            link.click();
                            document.body.removeChild(link);
                        },
                        error: function(xhr, error, thrown) {
                            console.log('Download Error:', xhr.responseText, error, thrown);
                            alert('Failed to download QR codes.');
                        }
                    });
                }
            });

            $('#deleteItems').click(function() {
                if (confirm('Are you sure you want to delete the selected items and their related data?')) {
                    let selectedIds = $('.itemCheckbox:checked').map(function() {
                        return $(this).val();
                    }).get();
                    if (selectedIds.length > 0) {
                        $.ajax({
                            url: 'process/test_delete_items_process.php',
                            type: 'POST',
                            data: { item_ids: selectedIds },
                            success: function(response) {
                                alert(response);
                                table.ajax.reload();
                            },
                            error: function(xhr, error, thrown) {
                                console.log('Delete Error:', xhr.responseText, error, thrown);
                                alert('Failed to delete items.');
                            }
                        });
                    }
                }
            });

            $(document).on('click', '.editBtn', function() {
                let itemId = $(this).data('id');
                // Get current custom search filters
                let customFilters = $('#customSearch').val().trim();
                
                $.ajax({
                    url: 'process/test_get_item_details_process.php',
                    type: 'POST',
                    data: { 
                        item_id: itemId, 
                        action: 'get',
                        custom_filters: customFilters // Pass the custom filters
                    },
                    success: function(response) {
                        let item = JSON.parse(response);
                        if (item && item.item_id) {
                            $('#editItemId').val(item.item_id);
                            $('#editItemDetails').val(item.item_details || '');
                            $('#editSerialNumber').val(item.serial_number || '');
                            $('#editBrandName').val(item.brand_name || '');
                            $('#editItemTypeName').val(item.item_type_name || '');
                            $('#editPropertyName').val(item.property_name || '');
                            $('#editLocationName').val(item.location_name || '');
                            $('#editStatus').val(item.status || '');
                            $('#editUnitPrice').val(item.unit_price || '');
                            $('#editTotalAmount').val(item.total_amount || '');
                            $('#editDeliveryDate').val(item.delivery_date || '');
                            $('#editReceivedByName').val(item.received_by_name || '');
                            $('#editRemarks').val(item.remarks || '');
                            $('#editIssuedByName').val(item.issued_by_name || '');
                            $('#editIssuedToName').val(item.issued_to_name || '');
                            $('#editDepartmentName').val(item.department_name || '');
                            $('#editDateIssued').val(item.date_issued || '');
                            $('#editWarrantyEnds').val(item.warranty_ends || '');
                            $('#editWarrantySlipNo').val(item.warranty_slip_no || '');
                            $('#editPoNumber').val(item.po_number || '');
                            $('#editPoDate').val(item.po_date || '');
                            $('#editSupplierName').val(item.supplier_name || '');
                            $('#editPrNumber').val(item.pr_number || '');
                            $('#editDoneOrNoPr').val(item.done_or_no_pr || '');
                            $('#editInvoiceNumber').val(item.invoice_number || '');
                            $('#editModal').modal('show');
                        } else {
                            alert('Item not found.');
                        }
                    },
                    error: function(xhr, error, thrown) {
                        console.log('Edit Fetch Error:', xhr.responseText, error, thrown);
                        alert('Failed to load item details.');
                    }
                });
            });

            $('#editForm').submit(function(e) {
                e.preventDefault();
                let formData = {
                    item_id: $('#editItemId').val(),
                    item_details: $('#editItemDetails').val(),
                    serial_number: $('#editSerialNumber').val(),
                    brand_name: $('#editBrandName').val(),
                    item_type_name: $('#editItemTypeName').val(),
                    property_name: $('#editPropertyName').val(),
                    location_name: $('#editLocationName').val(),
                    status: $('#editStatus').val(),
                    unit_price: $('#editUnitPrice').val(),
                    total_amount: $('#editTotalAmount').val(),
                    delivery_date: $('#editDeliveryDate').val(),
                    received_by_name: $('#editReceivedByName').val(),
                    remarks: $('#editRemarks').val(),
                    issued_by_name: $('#editIssuedByName').val(),
                    issued_to_name: $('#editIssuedToName').val(),
                    department_name: $('#editDepartmentName').val(),
                    date_issued: $('#editDateIssued').val(),
                    warranty_ends: $('#editWarrantyEnds').val(),
                    warranty_slip_no: $('#editWarrantySlipNo').val(),
                    po_number: $('#editPoNumber').val(),
                    po_date: $('#editPoDate').val(),
                    supplier_name: $('#editSupplierName').val(),
                    pr_number: $('#editPrNumber').val(),
                    done_or_no_pr: $('#editDoneOrNoPr').val(),
                    invoice_number: $('#editInvoiceNumber').val()
                };

                $.ajax({
                    url: 'process/update_item.php',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        alert(response);
                        $('#editModal').modal('hide');
                        table.ajax.reload();
                    },
                    error: function(xhr, error, thrown) {
                        console.log('Update Error:', xhr.responseText, error, thrown);
                        alert('Failed to update item.');
                    }
                });
            });
        });
    </script>
</body>
</html>

<?php $conn->close(); ?>