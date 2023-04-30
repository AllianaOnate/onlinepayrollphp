<?php
	include 'includes/session.php';

	if(isset($_POST['edit'])){
		$id = $_POST['id'];
		$date = $_POST['date'];
		$hours = $_POST['hours'] + ($_POST['mins']/60);
		$rate = $_POST['rate'];
		$total_ot = $_POST['total_ot'];

		$sql = "UPDATE overtime SET hours = '$hours', rate = '$rate', total_ot = '$total_ot', date_overtime = '$date' WHERE id = '$id'";
		if($conn->query($sql)){
			$_SESSION['success'] = 'Overtime updated successfully';
		}
		else{
			$_SESSION['error'] = $conn->error;
		}
	}
	else{
		$_SESSION['error'] = 'Fill up edit form first';
	}

	header('location:overtime.php');

?>