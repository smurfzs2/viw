<?php 

require_once('viel_connection.php');

$query = "SELECT * FROM tbl_viel"; 
$requestData= $_REQUEST;
$sqlData = isset($requestData['query']) ? $requestData['query'] : "";

$data = array();
$num=0;

$sql= $sqlData;
$queryData = $conn->query($sql) or die ($conn->error);
$totalRecords = $queryData->num_rows;

$sql= $sqlData;
$sql .= " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
$queryData = $conn->query($sql) or die ($conn->error);

if($queryData AND $queryData->num_rows > 0)
{
    while($resultData = $queryData->fetch_assoc())
    {
        $id = $resultData['id'];
        $firstName = $resultData['firstName'];
        $lastName = $resultData['lastName'];
        $gender = $resultData['gender'] == 0 ? "Male":"Female";
        $address = $resultData['address'];
        $birthday = date("F d, Y", strtotime($resultData['birthday']));
        $departmentId = $resultData['departmentName']; 
        
        $button="";
        $button.= "<a class='btn btn-outline-info btn-sm' href='viel_update.php?id=" . $resultData['id'] . "' name='update'><i class='fas fa-edit'>Update</i></a>";
        $button.= "<a class='btn btn-outline-danger btn-sm' href='viel_delete.php?id=" . $resultData['id'] . "' name='delete'><i class='delete fas fa-trash'>Delete</i></a>";

        $nestedData = Array();
        $nestedData[] = $_REQUEST['start']+=1;  
        //$nestedData[] = $id; 
        $nestedData[] = $firstName; 
        $nestedData[] = $lastName; 
        $nestedData[] = $gender;
        $nestedData[] = $address; 
        $nestedData[] = $birthday; 
        $nestedData[] = $departmentId; 
        $nestedData[] = $button; 

        $data[] = $nestedData;
    }
}



$json_data = array(
    "draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
    "recordsTotal"    => intval( $totalRecords ),  // total number of records
    "recordsFiltered" => intval( $totalRecords ), // total number of records after searching, if there is no searching then totalFiltered = totalData
    "data"            => $data   // total data array
);

echo json_encode($json_data);  // send data as json format

?>

