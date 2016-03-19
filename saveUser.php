<?php
	include("db.php");
	session_start();

	$id = $_SESSION['userID']; ///get the login user

	//===Change Name====//
	if(isset($_POST['changeNameButton'])) {
		$fname = $_POST['fname'];
		$lname = $_POST['lname'];

		$_SESSION['fname'] = $fname;
		$_SESSION['lname'] = $lname;

		$sql = "UPDATE clients SET FirstName='$fname', LastName='$lname'
	 		WHERE id=$id";
		mysqli_query($conn, $sql);

		$_SESSION['feedbackSuccess'] = 1;
	}
	//===End of Change Name===//

	//===Change Password===//
	if(isset($_POST['changePassButton'])) {
		$newPass = $_POST['newPass'];

		$sql = "UPDATE clients SET Password='$newPass' 
	 		WHERE id=$id";
		mysqli_query($conn, $sql);

		$_SESSION['feedbackSuccess'] = 1;
	}
	//===End of Change Password===//

	//===Feedback===//
	if(isset($_POST['feedbackButton'])) {
		$comment = $_POST['feedback']; //get comment

		$sql = "SELECT * FROM feedback WHERE userID=$id";
		$result = mysqli_query($conn, $sql);

		// get date today
		date_default_timezone_set('Asia/Manila');
		$dateToday = date('Y-m-d'); //get the date today to compare
		$dateToday = $dateToday . "T" . date('H:i'); //add T between for correct format when comparing	

		if(mysqli_num_rows($result) == 1) { //check if the user has already record
			$sql = "UPDATE feedback SET msg='$comment', date='$dateToday', love=0 WHERE userID=$id";
			mysqli_query($conn, $sql);
		} else { //otherwise insert new comment
			$sql = "INSERT INTO feedback VALUES($id, '$comment', '$dateToday', 0)";
			mysqli_query($conn, $sql);
		}

		$_SESSION['feedbackSuccess'] = 1;
	}
	//===End of Feedback===//

	//redirect to user.php with success
	echo "<script>window.location.href = 'user.php';</script>";
?>