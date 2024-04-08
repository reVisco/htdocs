<?php
/* Attempt MySQL server connection. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
require '../process/db_connect.php';
 
if(isset($_REQUEST["term"])){
    // Prepare a select statement
    $sql = "SELECT * 
    FROM items 
    WHERE 
    item_id LIKE ?
    or item_name LIKE ?
    or property LIKE ?
    or order_batch_number LIKE ?
    or model_number LIKE ?
    or serial_number LIKE ?
    or warranty_coverage LIKE ?
    or brand LIKE ?
    or item_type LIKE ?
    or item_details LIKE ?
    or status_description LIKE ?
    or unit_price LIKE ?
    or justification_of_purchase LIKE ?
    or delivery_date LIKE ?
    or supplier_name LIKE ?
    or po_number LIKE ?
    or po_date LIKE ?
    or pr_number LIKE ?
    or invoice_no LIKE ?
    or delivery_receipt LIKE ?
    or items_received_by LIKE ?
    or remarks LIKE ?";
    
    if($stmt = $conn->prepare($sql)){
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("ssssssssssssssssssssss", $param_term, $param_term, $param_term, $param_term, $param_term, $param_term, $param_term, $param_term, $param_term, $param_term, $param_term, $param_term, $param_term, $param_term, $param_term, $param_term, $param_term, $param_term, $param_term, $param_term, $param_term, $param_term);
        
        // Set parameters
        $param_term = $_REQUEST["term"] . '%';
        
        // Attempt to execute the prepared statement
        if($stmt->execute()){
            $result = $stmt->get_result();
            
            // Check number of rows in the result set
            if($result->num_rows > 0){
                // Fetch result rows as an associative array
                while($row = $result->fetch_array(MYSQLI_ASSOC)){
                    echo "<p>" . $row["item_id"] . "-" .$row["item_name"] . "</p>";
                }
            } else{
                echo "<p>No matches found</p>";
            }
        } else{
            echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
        }
    }
     
    // Close statement
    $stmt->close();
}
 
// Close connection
$conn->close();
?>