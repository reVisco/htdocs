<?php
require 'test_db_connect.php';

header('Content-Type: application/json');
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

ob_start();

try {
    if (!$conn) {
        throw new Exception("Database connection failed");
    }

    // Handle different actions (data fetch or item details)
    $action = isset($_POST['action']) ? $_POST['action'] : 'data';

    if ($action === 'get') {
        // Fetch single item details for editing
        if (isset($_POST['item_id'])) {
            $itemId = intval($_POST['item_id']);
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
                WHERE i.item_id = ?");
            if (!$stmt) {
                throw new Exception("Failed to prepare statement: " . $conn->error);
            }

            $stmt->bind_param("i", $itemId);
            if (!$stmt->execute()) {
                throw new Exception("Failed to execute statement: " . $stmt->error);
            }

            $result = $stmt->get_result();
            $item = $result->fetch_assoc();

            if (!$item) {
                throw new Exception("Item not found");
            }

            ob_end_clean();
            echo json_encode($item);
            exit;
        }
    }

    // Get total records without filtering
    $totalRecordsStmt = $conn->prepare("SELECT COUNT(*) as total FROM Items");
    if (!$totalRecordsStmt) {
        throw new Exception("Failed to prepare total records statement: " . $conn->error);
    }

    if (!$totalRecordsStmt->execute()) {
        throw new Exception("Failed to execute total records statement: " . $totalRecordsStmt->error);
    }

    $totalRecords = $totalRecordsStmt->get_result()->fetch_assoc()['total'];

    // Handle search and filter
    $searchValue = isset($_POST['search']['value']) ? $_POST['search']['value'] : '';
    $filterColumn = isset($_POST['filterColumn']) ? $_POST['filterColumn'] : 'item_id';

    $validColumns = ['item_id', 'item_details', 'status'];
    $filterColumn = in_array($filterColumn, $validColumns) ? $filterColumn : 'item_id';

    $where = "WHERE 1=1";
    $params = [];
    $types = '';

    if (!empty($searchValue)) {
        $where .= " AND i.$filterColumn LIKE ?";
        $params[] = "%$searchValue%";
        $types .= 's';
    }

    // Get filtered records count
    $filteredStmt = $conn->prepare("SELECT COUNT(*) as total FROM Items i $where");
    if (!$filteredStmt) {
        throw new Exception("Failed to prepare filtered count statement: " . $conn->error);
    }

    if (!empty($params)) {
        $filteredStmt->bind_param($types, ...$params);
    }

    if (!$filteredStmt->execute()) {
        throw new Exception("Failed to execute filtered count statement: " . $filteredStmt->error);
    }

    $totalFilteredRecords = $filteredStmt->get_result()->fetch_assoc()['total'];

    // Get paginated data
    $start = isset($_POST['start']) ? intval($_POST['start']) : 0;
    $length = isset($_POST['length']) ? intval($_POST['length']) : 10;

    $query = "SELECT i.item_id, i.item_details, i.serial_number, i.status,
        b.brand_name, it.item_type_name, p.property_name, l.location_name,
        ip.unit_price, ip.total_amount, ip.delivery_date, per.person_name AS received_by_name,
        per_issued_by.person_name AS issued_by_name, per_issued_to.person_name AS issued_to_name,
        d.department_name, iss.date_issued, w.warranty_ends,
        po.po_number, s.supplier_name, pr.pr_number, inv.invoice_number
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
        $where LIMIT ?, ?";

    $stmt = $conn->prepare($query);
    if (!$stmt) {
        throw new Exception("Failed to prepare main query statement: " . $conn->error);
    }

    $params[] = $start;
    $params[] = $length;
    $types .= 'ii';

    $stmt->bind_param($types, ...$params);
    if (!$stmt->execute()) {
        throw new Exception("Failed to execute main query statement: " . $stmt->error);
    }

    $result = $stmt->get_result();
    $data = [];

    while ($row = $result->fetch_assoc()) {
        $data[] = array_map('utf8_encode', $row);
    }

    $response = [
        "draw" => isset($_POST['draw']) ? intval($_POST['draw']) : 1,
        "recordsTotal" => $totalRecords,
        "recordsFiltered" => $totalFilteredRecords,
        "data" => $data
    ];

    ob_end_clean();
    echo json_encode($response);

} catch (Exception $e) {
    ob_end_clean();
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
} finally {
    if (isset($stmt)) $stmt->close();
    if (isset($conn)) $conn->close();
}
?>