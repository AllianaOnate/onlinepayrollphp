<?php
	include 'includes/session.php';

	if(isset($_POST['delete'])){
		$row = $_POST['id'];
		$sql = "DELETE FROM payroll WHERE id = '$row'";
		if($conn->query($sql)){
			$_SESSION['success'] = 'Position deleted successfully';
		}
		else{
			$_SESSION['error'] = $conn->error;
		}
	}
	else{
		$_SESSION['error'] = 'Select item to delete first';
	}

	header('location: payroll.php');
	
?>