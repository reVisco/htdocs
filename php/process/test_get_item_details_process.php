<?php
require 'test_db_connect.php';

if (isset($_POST['item_id'])) {
    $itemId = $_POST['item_id'];
    
    // Process custom filters if they exist
    $additionalWhere = '';
    $additionalParams = [];
    $additionalTypes = '';
    
    if (isset($_POST['custom_filters']) && !empty($_POST['custom_filters'])) {
        $filters = explode(',', $_POST['custom_filters']);
        
        // Define column mappings for user-friendly filter names
        $columnMap = [
            'brand' => 'b.brand_name',
            'type' => 'it.item_type_name',
            'item' => 'i.item_details',
            'property' => 'p.property_name',
            'location' => 'l.location_name',
            'department' => 'd.department_name',
            'supplier' => 's.supplier_name',
            'po' => 'po.po_number',
            'pr' => 'pr.pr_number',
            'invoice' => 'inv.invoice_number',
            'done_or_no_pr' => 'pr.done_or_no_pr'
        ];
        
        foreach ($filters as $filter) {
            if (strpos($filter, ':') !== false) {
                list($col, $val) = array_map('trim', explode(':', $filter, 2));
                
                // Map the column name if it exists in our mapping
                $dbColumn = isset($columnMap[$col]) ? $columnMap[$col] : $col;
                
                // Add to the WHERE clause
                if (!empty($additionalWhere)) {
                    $additionalWhere .= ' AND ';
                }
                
                $additionalWhere .= "$dbColumn LIKE ?";
                $additionalParams[] = "%$val%";
                $additionalTypes .= 's';
            }
        }
    }
    
    // Modify the base query if we have additional filters
    $baseQuery = "
        SELECT 
            i.item_id, i.item_details, i.serial_number, i.status,
            b.brand_name, it.item_type_name, p.property_name, l.location_name,
            ip.unit_price, ip.total_amount, ip.delivery_date, ip.remarks, 
            per.person_name AS received_by_name,
            iss.issued_by, iss.issued_to, iss.date_issued, d.department_name,
            per_issued_by.person_name AS issued_by_name, 
            per_issued_to.person_name AS issued_to_name,
            w.warranty_ends, w.warranty_slip_no, po.po_number, po.po_date, 
            s.supplier_name, pr.pr_number, pr.done_or_no_pr, inv.invoice_number
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
        WHERE i.item_id = ?
        " . (!empty($additionalWhere) ? " AND " . $additionalWhere : "") . "
    ";
    
    $stmt = $conn->prepare($baseQuery);
    
    // Bind parameters
    if (!empty($additionalParams)) {
        // Combine the types and create the parameter references
        $types = "i" . $additionalTypes;
        $params = array_merge([$itemId], $additionalParams);
        
        // Create references array for bind_param
        $bindParams = array($types);
        foreach ($params as $key => $value) {
            $bindParams[] = &$params[$key];
        }
        
        // Use call_user_func_array to bind all parameters
        call_user_func_array([$stmt, 'bind_param'], $bindParams);
    } else {
        $stmt->bind_param("i", $itemId);
    }
}

$conn->close();
?>