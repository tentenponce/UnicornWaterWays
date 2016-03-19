<?php
	include("amenities.php"); //get room/amenities info, session also started here
	include("motto.php"); //get the company motto specially for footer

	//default is none/0
	$past = 0;
	$present = 0;
	$future = 0;

	$pastMoney = 0;
	$presentMoney = 0;
	$futureMoney = 0;

	$pastCot = 0;
	$pastReg = 0;
	$pastGrand = 0;
	$pastSup = 0;

	$presentCot = 0;
	$presentReg = 0;
	$presentGrand = 0;
	$presentSup = 0;

	$futureCot = 0;
	$futureReg = 0;
	$futureGrand = 0;
	$futureSup = 0;

	//filter setup
	$isRDateFilter = false;

	if(isset($_POST['filterDate'])) {
		$isRDateFilter = true;
		$fromRDate = $_POST['fromRDate'];
		$toRDate = $_POST['toRDate'];
	}

	if (isset($_POST['removeFilterDate'])) {
		$isRDateFilter = false;
	}

	//query to count past, present, future
	//query to count room
	$sql = "SELECT checkIn, checkOut, totalPrice, room FROM reservation";
	$result = mysqli_query($conn, $sql);

	while($row = mysqli_fetch_assoc($result)) {
		/* Format Date */
		$checkInParse = date_create($row['checkIn']);
		$checkOutParse = date_create($row['checkOut']);
		$totalPrice = $row['totalPrice'];
		$room = $row['room'];

		if ($isRDateFilter) { //check if filtered
			//parse date for comparing
			$fromRDateParse = date_create($fromRDate);
			$toRDateParse = date_create($toRDate);

			if (!($checkInParse >= $fromRDateParse 
				&& $checkInParse <= $toRDateParse) &&
				!($checkOutParse >= $fromRDateParse &&
					$checkOutParse <= $toRDateParse)) { //query all inside
				continue; //skip
			}
		}

		if ($dateToday > $checkOutParse) { //if past
			$past += 1;
			$pastMoney += $totalPrice;

			if ($room == "Cottages") {
				$pastCot += 1;
			} else if ($room == "Regular Rooms") {
				$pastReg += 1;
			} else if ($room == "Grande Rooms") {
				$pastGrand += 1;
			} else if ($room == "Supreme Rooms") {
				$pastSup += 1;
			}

		} else if ($dateToday < $checkInParse) { //else if future
			$future += 1;
			$futureMoney += $totalPrice;

			if ($room == "Cottages") {
				$futureCot += 1;
			} else if ($room == "Regular Rooms") {
				$futureReg += 1;
			} else if ($room == "Grande Rooms") {
				$futureGrand += 1;
			} else if ($room == "Supreme Rooms") {
				$futureSup += 1;
			}
		} else if ($dateToday < $checkOutParse && $dateToday > $checkInParse) { //present
			$present += 1;
			$presentMoney += $totalPrice;

			if ($room == "Cottages") {
				$presentCot += 1;
			} else if ($room == "Regular Rooms") {
				$presentReg += 1;
			} else if ($room == "Grande Rooms") {
				$presentGrand += 1;
			} else if ($room == "Supreme Rooms") {
				$presentSup += 1;
			}
		}
	}

	$totalReservation = $past + $present + $future;

	//compute total here because number format make it string
	$totalMoney = $presentMoney + $pastMoney + $futureMoney;
	$totalMoneyTemp = $totalMoney; //hold temporarily for computation purposes
	$totalMoney = number_format($totalMoney, 2, '.', ',');

	$pastMoney = number_format($pastMoney, 2, '.', ',');
	$futureMoney = number_format($futureMoney, 2, '.', ',');
	$presentMoney = number_format($presentMoney, 2, '.', ',');

	//count clients
	$sql = "SELECT count(*) AS clientCount FROM clients";
	$result = mysqli_query($conn, $sql);

	while ($row = mysqli_fetch_assoc($result)) {
		$clientCount = $row['clientCount'];
	}

	//get the most used room
	if (!$isRDateFilter) {
		$sql = "SELECT room, MAX(mostUse) AS maxRoomCount FROM 
		(SELECT room, COUNT(room) As mostUse FROM reservation GROUP BY room) AS subTable";
		$result = mysqli_query($conn, $sql);

		while ($row = mysqli_fetch_assoc($result)) {
			$mostUseRoom = $row['room'] . "(" . $row['maxRoomCount'] . ")";
		}	
	} else {
		$sql = "SELECT room, date FROM reservation";
		$result = mysqli_query($conn, $sql); 
 
 		//default
		$rooms = array(0, 0, 0, 0);
		$largestRooms = array(false, false, false, false);
		$roomName = array("Cottages", "Regular Rooms", "Grande Rooms", "Supreme Rooms");

		while ($row = mysqli_fetch_assoc($result)) {
			$room = $row['room'];
			$rDate = date_create($row['date']);

			if (!($rDate > $fromRDateParse && $rDate < $toRDateParse)) { //check if not inside
				continue; //skip if not inside
			}

			//check what room and add counter
			if ($room == "Cottages") {
				$rooms[0] += 1;
			} else if ($room == "Regular Rooms") {
				$rooms[1] += 1;
			} else if ($room == "Grande Rooms") {
				$rooms[2] += 1;
			} else if ($room == "Supreme Rooms") {
				$rooms[3] += 1;
			}
		}

		$mostUseRoomCount = 0; //default
		$nullRoom = "No client(s) reserve at this date span.";
		$mostUseRoom = $nullRoom; //default

		for ($i = 0; $i < 4; $i ++) {
			if ($rooms[$i] >= $mostUseRoomCount) { //compare if largest is smaller
				$mostUseRoomCount = $rooms[$i]; //change largest to the more larger haha
				$largestIndex = $i; //get the index to compare later multiple largest
			}
		}

		if ($mostUseRoomCount != 0) { //check if not 0, (nonsense if 0, meaning no reservation)
			$largestRooms[$largestIndex] = true; //largest is this room

			//check if there's something room count equal
			for ($i = 0; $i < 4; $i ++) {
				if ($rooms[$i] == $mostUseRoomCount) {
					$largestRooms[$i] = true; //mark the other largest
				}
			}

			for ($i = 0; $i < 4; $i ++) {
				if ($largestRooms[$i] && $mostUseRoom != $nullRoom) {
					$mostUseRoom .= ", " . $roomName[$i] . "(" . $rooms[$i] . ")";;
				} else if($largestRooms[$i]){
					$mostUseRoom = $roomName[$i] . "(" . $rooms[$i] . ")";
				}
			}
		}
	}
	

	//get percentage of reservation per day
	$sql = "SELECT date FROM reservation";
	$result = mysqli_query($conn, $sql);
	$firstDate = ""; //initially set null

	while ($row = mysqli_fetch_assoc($result)) { //get the lowest/earliest reservation date
		$reserveDate = date_create($row['date']);

		if ($firstDate ==  "") {
			$firstDate = $reserveDate;
		}

		if ($reserveDate < $firstDate) { //if the reserve date is smaller, put it on firstdate
			$firstDate = $reserveDate;
		}
	}

	if (!$isRDateFilter) { //if not filter, from the beginning to today
		$date1 = date_format($firstDate, 'Y-m-d') . "T" . date_format($firstDate, 'H:i');
		$date2 = date_format($dateToday, 'Y-m-d') . "T" . date_format($dateToday, 'H:i');	
	} else { //else, get the filtered date
		$date1 = date_format($fromRDateParse, 'Y-m-d') . "T" . date_format($fromRDateParse, 'H:i');
		$date2 = date_format($toRDateParse, 'Y-m-d') . "T" . date_format($toRDateParse, 'H:i');
	}
	
	$interval = strtotime($date2) - strtotime($date1);
	$interval = ceil($interval / 86400); //as stackoverflow said, divide it

	$averageRoomUse = number_format(abs($totalReservation / $interval), 2, '.', '');
	// echo "<script>console.log('$totalMoney');</script>";
	$averageIncome = number_format($totalMoneyTemp / $interval, 2, '.', ',');


?>
<html lang="en">
  	<head>
	  	<title>Company Statistics</title>
	    <meta charset="utf-8">
	    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
	    <link rel="stylesheet" href="css/unicornic.css">
	    <link rel="stylesheet" href="css/reports.css">
	    <script src="bootstrap/jquery/jquery.min.js"></script>
		<script src="bootstrap/js/bootstrap.min.js"></script>
	</head>
  	<body>
  	<!--Navigation Bar-->
  	<nav id="nav" class="navbar navbar-inverse navbar-fixed-top">
	  	<div class="container-fluid">
		    <div class="navbar-header">
		    	<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
			        <span class="icon-bar"></span>
			        <span class="icon-bar"></span>
			        <span class="icon-bar"></span>
		      	</button>
	    		<a href="index.php#home" class="navbar-brand">
	    			<img src="img/unicornLogo2.png" height="40px" width="40px"></img>
				</a>
		    </div>
		    <div class="collapse navbar-collapse" id="myNavbar">
		      	<ul class="nav navbar-nav">
		      		<li><a href="index.php#home">Home</a></li>
	        		<li class="dropdown">
	        			<a class="dropdown-toggle" data-toggle="dropdown" href="#">
	        				Rooms<span class="caret"></span>
	        			</a>
	        			<ul class="dropdown-menu">
		            		<li><a href="index.php#cottage">Cottages</a></li>
		            		<li><a href="index.php#regroom">Regular Rooms</a></li>
		            		<li><a href="index.php#grandroom">Grande Rooms</a></li> 
		            		<li><a href="index.php#suproom">Supreme Rooms</a></li> 
		          		</ul>
	        		</li>
	        		<li><a href="reviews.php">Reviews</a></li>
	        		<?php
	        			if(!empty($_SESSION['admin'])) {
	        				echo "<li><a href='reservations.php'>Reservations</a></li>";
        					echo "<li><a href='offers.php'>Special Offers</a></li>";
        					echo "<li class='active'><a href='#'>Statistics</a></li>";
        					echo "<li><a href='feedback.php'>Feedbacks</a></li>";
        					echo "<li><a href='settings.php'>Settings</a></li>";
	        			}
	        		?>
		      	</ul>

		      	<?php
		      	if (empty($_SESSION['login'])) { //check if not logged in.
		      		//show login and sign up tab if not logged in.
			      	echo "
			      	<ul class='nav navbar-nav navbar-right'>
				        <li>
				        	<a href='signup.html'>
				        		<span class='glyphicon glyphicon-user'></span>
				        		Sign Up
				        	</a>
				        </li>
				        <li>
				        	<a href='login.html'>
				        		<span class='glyphicon glyphicon-log-in'></span>
				        		Login
			        		</a>
				        </li>
				    </ul>";
				} else { //otherwise, show name of the person logged in.
 					echo "
			      	<ul class='nav navbar-nav navbar-right'>
				        <li>";
				        	if (isset($_SESSION['admin'])) {
				        		echo "<a href='#'>";
				        	}else {
				        		echo "<a href='user.php'>";
				        	}
				        		echo "<span class='glyphicon glyphicon-user'></span> 
				        		" . $_SESSION['fname'] . " " . $_SESSION['lname'] . "
				        	</a>
				        </li>
				        <li>
				        	<a href='logout.php'>
				        		<span class='glyphicon glyphicon-log-in'></span>
				        		Logout
			        		</a>
				        </li>
				    </ul>";
				}
			    ?>
		    </div>
	  	</div>
	</nav>
	<!--End of Navigation Bar-->
	<!--Reports-->
	<div class="card panel panel-default text-center panel-info">
		<h3 class="panel-heading">Company Statistics</h3>
		<div class="panel-body">
			<p>
				<!--Get Date Today-->
				<small>
					(As of : 
					<?php
						if ($isRDateFilter) {
							$fromDate = date_format($fromRDateParse, 'M d, Y');
							$fromDay = date_format($fromRDateParse, 'D');
							$fromTime = date_format($fromRDateParse, 'g:i A');

							$toDate = date_format($toRDateParse, 'M d, Y');
							$toDay = date_format($toRDateParse, 'D');
							$toTime = date_format($toRDateParse, 'g:i A');

							$asOfToday = $fromDate . " " . $fromTime . ", " . $fromDay;
							$asOfToday .= " - " . $toDate . " " . $toTime . ", " . $toDay;
						} else {
							// $begDate = date_format($firstDate, 'M d, Y');
							// $begDay = date_format($firstDate, 'D');
							// $begTime = date_format($firstDate, 'g:i A');

							// $todayDate = date_format($dateToday, 'M d, Y');
							// $todayDay = date_format($dateToday, 'D');
							// $todayTime = date_format($dateToday, 'g:i A');

							// $asOfToday = $begDate . " " . $begTime . ", " . $begDay;
							// $asOfToday .= " - " . $todayDate . " " . $todayTime . ", " . $todayDay;
							$asOfToday = "All Records";
						}

						echo $asOfToday;
					?>
					)
				</small>
				<!--End of Get Date Today-->
			</p>
			<form class="form-inline" method="post" 
			action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
				<label for="fromRDate">From: </label>
				<input oninput="fromChange()" onchange="fromChange()" id="fromRDate" 
				name="fromRDate" class="form-control" type="date" required="true" 
				value=
				<?php
					if ($isRDateFilter) {
						echo $fromRDate; //if there's filter, put the value here
					}
				?>></input>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<label for="toRDate">To: </label>
				<input oninput="toChange()" onchange="toChange()" id="toRDate" 
				name="toRDate" class="form-control" type="date" required="true" 
				value=
				<?php
					if ($isRDateFilter) {
						echo $toRDate; //if there's filter, put the value here
					}
				?>></input>
				<br>
				<br>
				<?php
					if($isRDateFilter) {
						echo 
						"<button class='btn btn-default' name='filterDate'>
							<span class='glyphicon glyphicon-filter'></span>
							 Filter
						</button>";
						echo "&nbsp;";
						echo 
						"<button class='btn btn-danger' name='removeFilterDate'>
							<span class='glyphicon glyphicon-filter'></span>
							 Remove Filter
						</button>";
					} else {
						echo 
						"<button class='btn btn-default' name='filterDate'>
							<span class='glyphicon glyphicon-filter'></span>
							 Filter
						</button>";
					}
				?>
			</form>
			<!--Reservation Report-->
			<div class="reports">
				<h3>
					<p><strong>Reservation</strong></p>
				</h3>
				<hr>
				<div class="table-responsive">
					<table class="table table-striped table-bordered">
						<thead>
							<tr>
								<th>Reservation Type</th>
								<th>Reservation Count</th>
								<th>Reservation Income</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><span class="green-circle"></span> Finished Reservation</td>
								<td><?php echo $past;?></td>
								<td>&#8369; <?php echo $pastMoney;?></td>
							</tr>
							<tr>
								<td><span class="blue-circle"></span> Present Reservation</td>
								<td><?php echo $present;?></td>
								<td>&#8369; <?php echo $presentMoney;?></td>
							</tr>
							<tr>
								<td><span class="yellow-circle"></span> Future Reservation</td>
								<td><?php echo $future;?></td>
								<td>&#8369; <?php echo $futureMoney;?></td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="row overallInfo">
					<div class="col-xs-6">
						Total Reservation: 
						<strong>
							<?php
								echo $totalReservation;
							?>
						</strong>
					</div>
					<div class="col-xs-6">	
						Total Income: &#8369; 
						<strong>
							<?php
								echo $totalMoney;
							?>
						</strong>
					</div>
				</div>
				<br>
				<br>
				<p>
					<small>
						(Note: <b>Check In</b> 
						AND <b>Check Out</b> of the reservation must
					be inside of the filter or else it will not be included/counted.
					Otherwise, it has no filter).
					</small>
				</p>
			</div><!--Reservation Report-->
			<br>
			<!--Amenities Report-->
			<div class="reports">
				<h3><strong>Amenities</strong></h3>
				<hr>
				<!--Past Amenities-->
				<h4><span class="green-circle"></span> <strong>Finished Reservation</strong></h4>
				<div class="table-responsive">
					<table class="table table-striped table-bordered">
						<thead>
							<tr>
								<th>Room Type</th>
								<th>Reservation Count</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Cottages</td>
								<td><?php echo $pastCot; ?></td>
							</tr>
							<tr>
								<td>Regular Rooms</td>
								<td><?php echo $pastReg; ?></td>
							</tr>
							<tr>
								<td>Grande Rooms</td>
								<td><?php echo $pastGrand; ?></td>
							</tr>
							<tr>
								<td>Supreme Rooms</td>
								<td><?php echo $pastSup; ?></td>
							</tr>
						</tbody>
					</table>
				</div><!--Past Amenities-->
				<br>
				<!--Present Amenities-->
				<h4><span class="blue-circle"></span> <strong>Present Reservation</strong></h4>
				<div class="table-responsive">
					<table class="table table-striped table-bordered">
						<thead>
							<tr>
								<th>Room Type</th>
								<th>Reservation Count</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Cottages</td>
								<td><?php echo $presentCot; ?></td>
							</tr>
							<tr>
								<td>Regular Rooms</td>
								<td><?php echo $presentReg; ?></td>
							</tr>
							<tr>
								<td>Grande Rooms</td>
								<td><?php echo $presentGrand; ?></td>
							</tr>
							<tr>
								<td>Supreme Rooms</td>
								<td><?php echo $presentSup; ?></td>
							</tr>
						</tbody>
					</table>
				</div><!--Present Amenities-->
				<br>
				<!--Future Amenities-->
				<h4><span class="yellow-circle"></span> <strong>Future Reservation</strong></h4>
				<div class="table-responsive">
					<table class="table table-striped table-bordered">
						<thead>
							<tr>
								<th>Room Type</th>
								<th>Reservation Count</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Cottages</td>
								<td><?php echo $futureCot; ?></td>
							</tr>
							<tr>
								<td>Regular Rooms</td>
								<td><?php echo $futureReg; ?></td>
							</tr>
							<tr>
								<td>Grande Rooms</td>
								<td><?php echo $futureGrand; ?></td>
							</tr>
							<tr>
								<td>Supreme Rooms</td>
								<td><?php echo $futureSup; ?></td>
							</tr>
						</tbody>
					</table>
				</div><!--Future Amenities-->
			</div><!--Amenities Report-->
			<br>
			<!--General Report-->
			<div class="reports">
				<h3><strong>General Statistics</strong></h3>
				<hr>
					<h4>
						<span class="glyphicon glyphicon-user"></span> 
						Total Number of Registered Clients: 
						<strong>
							<?php echo $clientCount; ?>
						</strong>
					</h4>
					<h4>Most Reserved Room: <strong><?php echo $mostUseRoom; ?></strong></h4>
					<h4>Average Room(s) Use Per Day: <strong><?php echo $averageRoomUse; ?></strong></h4>
					<h4>Average Income Per Day: <strong>&#8369; <?php echo $averageIncome; ?></strong></h4>
			</div><!--General Report-->
		</div>
	</div>
	<!--Reports-->
	<footer class="footer-basic-centered">
		<p class="footer-company-motto"><?php echo $motto;?></p>
		<p class="footer-links" id="scroll-effect">
			<a href="#home">Home</a>
			·
			<a href="">About</a>
			·
			<a href="signup.html">Sign Up</a>
			·
			<a href="login.html">Login</a>
		</p>
		<p class="footer-company-name">Unicorn Water Ways &copy; 2015</p>
	</footer>
	</body>
</html>