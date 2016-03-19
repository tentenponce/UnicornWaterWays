<?php
	include("db.php");
	session_start();

	$sql = "SELECT * FROM amenities WHERE id=1";
	$result = mysqli_query($conn, $sql);	

	while($row = mysqli_fetch_assoc($result)) { //retreive cottage info
		$room1name = $row["name"];
		$room1dayPrice = number_format($row["dayPrice"], 2, '.', '');
		$room1nightPrice = number_format($row["nightPrice"], 2, '.', '');
		$room1minPerson = $row["minPerson"];
		$room1maxPerson = $row["maxPerson"];
		$room1count = $row["count"];
	}

	$sql = "SELECT * FROM amenities WHERE id=2";
	$result = mysqli_query($conn, $sql);	

	while($row = mysqli_fetch_assoc($result)) { //retreive regular room info
		$room2name = $row["name"];
		$room2dayPrice = number_format($row["dayPrice"], 2, '.', '');
		$room2nightPrice = number_format($row["nightPrice"], 2, '.', '');
		$room2minPerson = $row["minPerson"];
		$room2maxPerson = $row["maxPerson"];
		$room2count = $row["count"];
	}

	$sql = "SELECT * FROM amenities WHERE id=3";
	$result = mysqli_query($conn, $sql);	

	while($row = mysqli_fetch_assoc($result)) { //retreive grande room info
		$room3name = $row["name"];
		$room3dayPrice = number_format($row["dayPrice"], 2, '.', '');
		$room3nightPrice = number_format($row["nightPrice"], 2, '.', '');
		$room3minPerson = $row["minPerson"];
		$room3maxPerson = $row["maxPerson"];
		$room3count = $row["count"];
	}

	$sql = "SELECT * FROM amenities WHERE id=4";
	$result = mysqli_query($conn, $sql);	

	while($row = mysqli_fetch_assoc($result)) { //retreive grande room info
		$room4name = $row["name"];
		$room4dayPrice = number_format($row["dayPrice"], 2, '.', '');
		$room4nightPrice = number_format($row["nightPrice"], 2, '.', '');
		$room4minPerson = $row["minPerson"];
		$room4maxPerson = $row["maxPerson"];
		$room4count = $row["count"];
	}

	date_default_timezone_set('Asia/Manila');
	$dateToday = date('Y-m-d'); //get the date today to compare
	$dateToday = $dateToday . "T" . date('H:i'); //add T between for correct format when comparing
	$dateToday = date_create($dateToday); //parse to date

	/* Cottages */
	$sql = "SELECT checkIn, checkOut FROM reservation WHERE room='$room1name'";
	$result = mysqli_query($conn, $sql);

	$roomUse = 0; //default, no room is being used.

	while($row = mysqli_fetch_assoc($result)) { //compare each date and check if the date is being used.
		$startDate = date_create($row["checkIn"]);
		$endDate = date_create($row["checkOut"]);

		if($startDate == $dateToday //if check in date is equal
			|| ($dateToday > $startDate && $dateToday < $endDate)) { //if check in is between checkin and out of another reservation
			$startDateConflict = date_create($row["checkIn"]); //get the conflicted date
			$endDateConflict = date_create($row["checkOut"]); //get the conflicted date
			$roomUse = $roomUse + 1;
		}
	}

	$room1available = $room1count - $roomUse; //compute room available for cottages

	/* Regular Rooms */
	$sql = "SELECT checkIn, checkOut FROM reservation WHERE room='$room2name'";
	$result = mysqli_query($conn, $sql);

	$roomUse = 0; //default, no room is being used.

	while($row = mysqli_fetch_assoc($result)) { //compare each date and check if the date is being used.
		$startDate = date_create($row["checkIn"]);
		$endDate = date_create($row["checkOut"]);

		if($startDate == $dateToday //if check in date is equal
			|| ($dateToday > $startDate && $dateToday < $endDate)) { //if check in is between checkin and out of another reservation
			$startDateConflict = date_create($row["checkIn"]); //get the conflicted date
			$endDateConflict = date_create($row["checkOut"]); //get the conflicted date
			$roomUse = $roomUse + 1;
		}
	}

	$room2available = $room2count - $roomUse; //compute room available for regular rooms

	/* Grande Rooms */
	$sql = "SELECT checkIn, checkOut FROM reservation WHERE room='$room3name'";
	$result = mysqli_query($conn, $sql);

	$roomUse = 0; //default, no room is being used.

	while($row = mysqli_fetch_assoc($result)) { //compare each date and check if the date is being used.
		$startDate = date_create($row["checkIn"]);
		$endDate = date_create($row["checkOut"]);

		if($startDate == $dateToday //if check in date is equal
			|| ($dateToday > $startDate && $dateToday < $endDate)) { //if check in is between checkin and out of another reservation
			$startDateConflict = date_create($row["checkIn"]); //get the conflicted date
			$endDateConflict = date_create($row["checkOut"]); //get the conflicted date
			$roomUse = $roomUse + 1;
		}
	}

	$room3available = $room3count - $roomUse; //compute room available for grande rooms

	/* Supreme Rooms */
	$sql = "SELECT checkIn, checkOut FROM reservation WHERE room='$room4name'";
	$result = mysqli_query($conn, $sql);

	$roomUse = 0; //default, no room is being used.

	while($row = mysqli_fetch_assoc($result)) { //compare each date and check if the date is being used.
		$startDate = date_create($row["checkIn"]);
		$endDate = date_create($row["checkOut"]);

		if($startDate == $dateToday //if check in date is equal
			|| ($dateToday > $startDate && $dateToday < $endDate)) { //if check in is between checkin and out of another reservation
			$roomUse = $roomUse + 1;
		}
	}

	$room4available = $room4count - $roomUse; //compute room available for supreme rooms
?>