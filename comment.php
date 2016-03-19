<?php
	include("db.php");
	session_start();
	
	$comment = $_POST['feedback']; //get comment
	$id = $_SESSION['userID']; ///get the login user

	$sql = "SELECT * FROM feedback WHERE userID=$id";
	$result = mysqli_query($conn, $sql);

	if(mysqli_num_rows($result) == 1) { //check if the user has already record
		$sql = "UPDATE feedback SET msg='$comment' WHERE userID=$id";
		mysqli_query($conn, $sql);
	} else { //otherwise insert new comment
		$sql = "INSERT INTO feedback VALUES($id, '$comment')";
		mysqli_query($conn, $sql);
	}

	//redirect to user.php
	echo "<script>window.location.href = 'user.php';</script>";
	$_SESSION['feedbackSuccess'] = 1;
?>