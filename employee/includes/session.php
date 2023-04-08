<?php
	session_start();
	include 'includes/conn.php';

	if(!isset($_SESSION['employees']) || trim($_SESSION['employees']) == ''){
		header('location: index.php');
	}

	$sql = "SELECT * FROM employees WHERE id = '".$_SESSION['employees']."'";
	$query = $conn->query($sql);
	$user = $query->fetch_assoc();
	
?>