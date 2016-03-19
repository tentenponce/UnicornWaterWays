<?php
	include('db.php');

	$morningCPrice = $_POST['morningCPrice'];
	$nightCPrice = $_POST['nightCPrice'];

	$sql = "UPDATE amenities SET dayPrice=$morningCPrice, nightPrice=$nightCPrice
			WHERE id=1";
	mysqli_query($conn, $sql);		

	$morningRPrice = $_POST['morningRPrice'];
	$nightRPrice = $_POST['nightRPrice'];

	$sql = "UPDATE amenities SET dayPrice=$morningRPrice, nightPrice=$nightRPrice
			WHERE id=2";
	mysqli_query($conn, $sql);	

	$morningGPrice = $_POST['morningGPrice'];
	$nightGPrice = $_POST['nightGPrice'];

	$sql = "UPDATE amenities SET dayPrice=$morningGPrice, nightPrice=$nightGPrice
			WHERE id=3";
	mysqli_query($conn, $sql);	

	$sPrice = $_POST['sPrice'] / 2; //divide to 2
	$sql = "UPDATE amenities SET dayPrice=$sPrice, nightPrice=$sPrice
			WHERE id=4";
	mysqli_query($conn, $sql);	

	//change motto
	$motto = $_POST['motto'];

	$sql = "UPDATE motto SET motto='$motto'";
	mysqli_query($conn, $sql);

	//redirect back to settings tab
	echo "<script>window.location.href ='settings.php?success=1';</script>";
?>