<?php

include_once 'viel_connection.php';

$query = "SELECT * FROM tbl_viel"; 

$result = mysqli_query($conn, $sql);

// Fetch and format data for DataTables response
$data = array();
while ($row = mysqli_fetch_assoc($result)) {
    // Format data as needed
    $data[] = array(
        $row['id'],
        $row['firstName'],
        // ...
    );
}

// Build response JSON
$response = array(
    "draw" => intval($draw),
    "recordsTotal" => $totalRecords,
    "recordsFiltered" => $filteredRecords,
    "data" => $data
);

// Return JSON response
echo json_encode($response);
?>