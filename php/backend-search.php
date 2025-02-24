<?php
require 'process/db_connect.php';

if(isset($_GET['term'])){
    $search = $_GET['term'];
    
    // Prepare the SQL query to search across multiple columns
    $sql = "SELECT * FROM items WHERE 
           item_name LIKE ? OR 
           property LIKE ? OR 
           serial_number LIKE ? OR 
           brand LIKE ? OR 
           item_type LIKE ? OR 
           status_description LIKE ? OR 
           supplier_name LIKE ? OR 
           po_number LIKE ? OR 
           pr_number LIKE ? OR 
           invoice_no LIKE ? OR 
           delivery_receipt LIKE ?
           LIMIT 10";
    
    if($stmt = $conn->prepare($sql)){
        $search_term = "%{$search}%";
        $stmt->bind_param("sssssssssss", 
            $search_term, $search_term, $search_term, $search_term, 
            $search_term, $search_term, $search_term, $search_term, 
            $search_term, $search_term, $search_term);
        
        if($stmt->execute()){
            $result = $stmt->get_result();
            
            if($result->num_rows > 0){
                echo "<div class='table-responsive'>";
                echo "<table class='table table-bordered table-striped table-hover'>";
                echo "<thead class='thead-dark'>";
                echo "<tr>";
                echo "<th>Item ID</th>";
                echo "<th>Item Name</th>";
                echo "<th>Property</th>";
                echo "<th>Serial Number</th>";
                echo "<th>Brand</th>";
                echo "<th>Item Type</th>";
                echo "<th>Status</th>";
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";
                
                while($row = $result->fetch_assoc()){
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['item_id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['item_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['property']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['serial_number']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['brand']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['item_type']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['status_description']) . "</td>";
                    echo "</tr>";
                }
                
                echo "</tbody>";
                echo "</table>";
                echo "</div>";
            } else{
                echo "<p>No matches found</p>";
            }
        } else{
            echo "<p>ERROR: Could not execute query.</p>";
        }
        $stmt->close();
    } else{
        echo "<p>ERROR: Could not prepare query.</p>";
    }
    
    $conn->close();
}
?>