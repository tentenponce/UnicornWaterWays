<?php
	include('db.php');

	$roomType = $_POST['roomType'];
	$discount = $_POST['discount'];

	/* Get the ID of the chosen room */
	$sql = "SELECT id FROM amenities WHERE name='$roomType'";
	$result = mysqli_query($conn, $sql);

	while($row = mysqli_fetch_assoc($result)) {
		$id = $row['id'];
	}/* GET ID End */

	if($discount <= 0) { //delete the room type from discount if discount is 0
		$sql = "DELETE FROM discount WHERE roomID=$id";
		mysqli_query($conn, $sql);

		//redirect back to special offers tab
		echo "<script>window.location.href ='offers.php';</script>";
		return; //return to exit php
	}

	/* check if roomID is already registered */
	$sql = "SELECT * FROM discount WHERE roomID=$id";
	$result = mysqli_query($conn, $sql);

	$count = 0;
	while($row = mysqli_fetch_assoc($result)) {
		$count++;
	}/* check if roomID is already registered */

	if ($count <= 0) { //if count is 0 then use INSERT INTO statement
		$sql = "INSERT INTO discount VALUES($id, $discount)";
		mysqli_query($conn, $sql);
	} else { //otherwise, use UPDATE statement
		$sql = "UPDATE discount set percent=$discount WHERE roomID=$id";
		mysqli_query($conn, $sql);
	}
	
	//redirect back to special offers tab
	echo "<script>window.location.href ='offers.php';</script>";
?>