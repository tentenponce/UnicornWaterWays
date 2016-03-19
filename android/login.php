<?php
	include("db.php");
	
	if (isset($_POST['Email']) && isset($_POST['Password'])) {
		$email = $_POST['Email'];
		$pass = $_POST['Password'];
		
		$sql = "SELECT Email, Password FROM clients WHERE Email='$email' AND Password='$pass'";
		$result = mysqli_query($conn, $sql);
		
		$res = array();
		
		if (mysqli_num_rows($result) == 1) {
			while ($row = mysqli_fetch_assoc($result)) {
				array_push($res, 
					array('Email'=>$row['Email'],
						'Password'=>$row['Password']
					)
				);
			}
			echo "Success";
		} else {
			echo "Failed";
		}
	}
	
	echo json_encode(array("result"=>$res));
?>