<?php include 'includes/session.php'; 

    $empid = $_GET['id'];  
    $status = $_GET['status'];
    $sql = "UPDATE employees SET status='$status' WHERE id = '$empid'";
    $query = $conn->query($sql);
    header('location:employee.php');



?>