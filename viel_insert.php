<?php include 'viel_connection.php';
if(isset($_POST['proceed'])){
$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$gender = $_POST['gender'];
$address = $_POST['address'];
$birthday = $_POST['birthday'];
$departmentId = $_POST['departmentId'];

    $sql = "INSERT INTO tbl_viel (firstName, lastName, gender, address, birthday, departmentId)
    values ('$firstName','$lastName','$gender','$address','$birthday', '$departmentId')";

    if ($conn->query($sql)){
        header('location: viel_thankyou.php');
    }
    else{
    echo "Error: ". $sql ."
    ". $conn->error;
    }
    
    $conn->close();
}
?>