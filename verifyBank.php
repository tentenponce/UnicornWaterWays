<?php
	include("android/db.php");

	$accNumber = $_GET['accNumber'];
	$pinNumber = $_GET['pinNumber'];

	$sql = "SELECT * FROM bank WHERE accnum='$accNumber' AND pin='$pinNumber'";
	$result = mysqli_query($conn, $sql);

	if (mysqli_num_rows($result) == 1) {
		echo "1";
	} else {
		echo "0";
	}
?>