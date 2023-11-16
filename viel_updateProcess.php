<?php
include 'viel_connection.php';

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $birthday = $_POST['birthday'];
    $departmentId = $_POST['departmentId'];

    $sql = "UPDATE tbl_viel SET firstName='$firstName', lastName='$lastName', gender='$gender', address='$address', birthday='$birthday', departmentId='$departmentId' WHERE id=$id";

    if ($conn->query($sql)) {
        header('Location: viel_quickTable.php');
    } else {
        echo "Error updating record: " . $conn->error;
    }

    $conn->close();
}
?>