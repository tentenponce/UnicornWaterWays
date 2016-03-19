<?php
	include("android/db.php");
	$clientID = $_GET['clientID'];

	$sql = "SELECT love FROM feedback WHERE userID=$clientID";
	$result = mysqli_query($conn, $sql);

	$currentLove = -1;

	while ($row = mysqli_fetch_assoc($result)) {
 		$currentLove = $row['love']; //get love
	}

	//update love
	if ($currentLove == 0) {
		$sql = "UPDATE feedback SET love=1 WHERE userID=$clientID";
		$updateLove = 1;
	} else if ($currentLove == 1){
		$sql = "UPDATE feedback SET love=0 WHERE userID=$clientID";
		$updateLove = 0;
	}

	mysqli_query($conn, $sql);

	echo $updateLove;
?>