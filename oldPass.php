<?php
	include("android/db.php"); //no echoess
	session_start();

	$id = $_SESSION['userID']; ///get the login user
	$oldPass = $_GET['oldPass'];

	$sql = "SELECT * FROM clients WHERE id=$id AND Password='$oldPass'";
	$result = mysqli_query($conn, $sql);

	if (mysqli_num_rows($result) == 1) {
		echo "1";
	} else {
		echo "0";
	}
?>