<?php
	include 'includes/session.php';

	if(isset($_POST['add'])){
		$employee = $_POST['employee'];
		$date = $_POST['date'];
		$hours = $_POST['hours'] + ($_POST['mins']/60);
		$rate = $_POST['rate'];
		$total_ot = $_POST['total_ot'];
		$sql = "SELECT * FROM employees WHERE employee_id = '$employee'";
		$query = $conn->query($sql);
		if($query->num_rows < 1){
			$_SESSION['error'] = 'Employee not found';
		}
		else{
			$row = $query->fetch_assoc();
			$employee_id = $row['id'];
			$sql = "INSERT INTO overtime (employee_id, date_overtime, hours, rate, total_ot) VALUES ('$employee_id', '$date', '$hours', '$rate', '$total_ot')";
			if($conn->query($sql)){
				$_SESSION['success'] = 'Overtime added successfully';
			}
			else{
				$_SESSION['error'] = $conn->error;
			}
		}
	}	
	else{
		$_SESSION['error'] = 'Fill up add form first';
	}

	header('location: overtime.php');

?>