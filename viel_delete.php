<?php
include_once 'viel_connection.php';
if (isset($_GET['id'])) {  
    $id = $_GET['id'];  
    $query = "DELETE FROM `tbl_viel` WHERE id ='$id'";  
    $run = mysqli_query($conn,$query);  
    if ($run) {
            header('location:viel_quickTable.php'); 
    }
    else{  
        echo "Error 404: ".mysqli_error($conn);  
    }  
}  
?>