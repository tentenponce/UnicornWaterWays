<?php
	include('db.php');
	
	$sql = "SELECT motto FROM motto";
	$result = mysqli_query($conn, $sql);

	while($row = mysqli_fetch_assoc($result)) { //get Company Motto
		$motto = $row['motto'];
	}
?>