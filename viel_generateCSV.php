<?php 

// Load the database configuration file 
include_once 'viel_connection.php'; 

// Fetch records from database 
$select=($_GET['sqlData']!='')?$_GET['sqlData']:'SELECT * FROM tbl_viel';
$result = $conn->query($select);

if($result->num_rows > 0){ 
    $delimiter = ","; 
    $filename = "members-data_" . date('Y-m-d') . ".csv"; 
    
    // Create a file pointer 
    $f = fopen('php://memory', 'w'); 
    
    // Set column headers 
    $fields = array('ID', 'FIRST NAME', 'LAST NAME', 'GENDER', 'ADDRESS', 'BIRTHDAY', 'DEPARTMENT'); 
    fputcsv($f, $fields, $delimiter); 
    
    // Output each row of the data, format line as csv and write to file pointer 
    while($row = $result->fetch_assoc()){ 
        $status = ($row['status'] == 1)?'Active':'Inactive'; 
        $lineData = array($row['id'], $row['firstName'], $row['lastName'],  $row['gender'] == 0 ? "Male" : "Female" , $row['address'], $row['birthday'],$row['departmentName']); 
        fputcsv($f, $lineData, $delimiter); 
    } 
    
    // Move back to beginning of file 
    fseek($f, 0); 
    
    // Set headers to download file rather than displayed 
    header('Content-Type: text/csv'); 
    header('Content-Disposition: attachment; filename="' . $filename . '";'); 
    
    //output all remaining data on a file pointer 
    fpassthru($f); 
} 
exit; 

?>