<?php
	include('db.php');
	if (isset($_POST['roomName'])) {
		$roomName = $_POST['roomName'];
	}
	
	
	$sql = "SELECT dayPrice, nightPrice FROM amenities WHERE name='$roomName'";
	$result = mysqli_query($conn, $sql);
	
	$res = array();
	
	while ($row = mysqli_fetch_assoc($result)) {	
		array_push($res,
			array('dayPrice'=>$row['dayPrice'],
				'nightPrice'=>$row['nightPrice']
			)
		);
	}
	
	echo json_encode(array("result"=>$res));
?>