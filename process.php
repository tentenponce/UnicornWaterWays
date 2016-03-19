<?php
	include("db.php");
	session_start();

	//retreive datas to insert to database.
	$roomType = $_SESSION['room'];
	$checkIn = $_SESSION['checkIn'];
	$checkOut = $_SESSION['checkOut'];
	$datePrice = $_SESSION['stayPrice'];
	$adultCount = $_SESSION['adultCount'];
	$adultPrice = $_SESSION['adultPrice'];
	$childCount = $_SESSION['childCount'];
	$childPrice = $_SESSION['childPrice'];
	$totalPrice = $_SESSION['totalPrice'];
	$clientID = $_SESSION['userID'];

	// get date today
	date_default_timezone_set('Asia/Manila');
	$dateToday = date('Y-m-d'); //get the date today to compare
	$dateToday = $dateToday . "T" . date('H:i'); //add T between for correct format when comparing

	//remove - and : to make it like reservation code
	$code = str_replace("-", "", $checkIn); 
	$code = str_replace(":", "", $code);
	$code = $code . substr($roomType, 0, 1);

	/* Check if there's available room at the chosen sched */
	$sql = "SELECT count FROM amenities WHERE name='$roomType'";
	$result = mysqli_query($conn, $sql);
	while($row = mysqli_fetch_assoc($result)) {
		$roomCount = $row["count"]; //get room count
	}

	$sql = "SELECT checkIn, checkOut FROM reservation WHERE room='$roomType'";
	$result = mysqli_query($conn, $sql);

	$checkInParse = date_create($checkIn); //temporary variable. parsed date
	$checkOutParse = date_create($checkOut); //temp var. parsed date.
	$roomUse = 0; //default, no room is being used.

	while($row = mysqli_fetch_assoc($result)) { //compare each date and check if the date is being used.
		$startDate = date_create($row["checkIn"]);
		$endDate = date_create($row["checkOut"]);

		//check conflict
		if($startDate == $checkInParse //if check in date is equal
			|| $endDate == $checkOutParse //if check out date is equal
			|| ($checkInParse > $startDate && $checkInParse < $endDate) //if check in is between checkin and out of another reservation
			|| ($checkOutParse > $startDate && $checkOutParse < $endDate) //if check out is between checkin and out of another reservation
			|| ($startDate > $checkInParse && $startDate < $checkOutParse)
			|| ($endDate > $checkInParse && $endDate < $checkOutParse)) { //vice versa
			$startDateConflict = date_create($row["checkIn"]); //get the conflicted date
			$endDateConflict = date_create($row["checkOut"]); //get the conflicted date
			$roomUse = $roomUse + 1;
		}
	}

	$roomAvailable = $roomCount - $roomUse; //compute available rooms
	$code = $code . $roomAvailable; //add the room number
	/* Check date conflict */

	if ($roomAvailable <= 0) { //if theres no room available
		//format date for proper viewing
		$checkInDate = date_format($startDateConflict, 'F d, Y');
		$checkInDay = date_format($startDateConflict, 'l');
		$checkInTime = date_format($startDateConflict, 'g:i A');

		$checkOutDate = date_format($endDateConflict, 'F d, Y');
		$checkOutDay = date_format($endDateConflict, 'l');
		$checkOutTime = date_format($endDateConflict, 'g:i A');

		$_SESSION['errorHeader'] = "Room Unavailable";
		$_SESSION['errorDesc'] = "<p>Sorry, all rooms are being used/reserved at your chosen schedule.</p>";
		echo "<script>window.location.href = 'error.php';</script>";
	} else { //otherwise, reserve.
		$sql = "INSERT INTO reservation VALUES('$code', '$roomType', '$checkIn', '$checkOut', $datePrice,
			$adultCount, $adultPrice, $childCount, $childPrice, $totalPrice, $clientID, '$dateToday')";
		mysqli_query($conn, $sql);

		$remainMoney = $_SESSION['money'] - ($totalPrice / 2);
		$sql = "UPDATE clients SET money = " . $remainMoney . " WHERE id=" . $clientID;
		mysqli_query($conn, $sql);

		//===PREPARE EMAIL===//
		require_once    ('PHPMailer-master/class.phpmailer.php');
		require_once    ('PHPMailer-master/class.smtp.php');

		$email = $_SESSION['email'];

		$mail               = new PHPMailer();
		$body               = "
		<h3>Reservation Code:</h3>
		<h1>$code</h1>
		<p>Please keep this reservation code as this will serve as your
		confirmation on your reservation at Unicorn Water Ways.
		Thank you.</p>";
		$mail->IsSMTP();                                        // telling the class to use SMTP
		$mail->SMTPDebug    = 0;                                // enables SMTP debug information (for testing)
		$mail->SMTPAuth     = true;                             // enable SMTP authentication
		$mail->SMTPSecure   = "tls";                            // sets the prefix to the servier
		$mail->Host         = "smtp.gmail.com";                 // sets GMAIL as the SMTP server
		$mail->Port         = 587;                              // set the SMTP port for the GMAIL server

		$mail->Username     = "unicornwaterways@gmail.com"  ;           // GMAIL username
		$mail->Password     = 'unicornic' ;           // GMAIL password

		$mail->SetFrom('unicornwaterways@gmail.com', 'Unicorn WaterWays');
		$mail->Subject    = "Unicorn Water Ways";
		$mail->MsgHTML($body);
		$address = $email;
		$mail->AddAddress($address, "Client");

		// $mail->AddAttachment("images/phpmailer.gif");        // attachment
		// $mail->AddAttachment("images/phpmailer_mini.gif");   // attachment

		if(!$mail->Send()) {
		    //echo "<script>alert('Mailer Error: " . $mail->ErrorInfo . " email: $email');</script>";
		    $isSend = false;
		} 
		else {
		   //echo "Message sent!";
			$isSend = true;
		}
		//===END OF EMAIL===//
		
		//redirect to reserve success site
		echo "<script>window.location.href = 'reserveSuccess.php?code=$code&isSend=$isSend';</script>";
	}
?>