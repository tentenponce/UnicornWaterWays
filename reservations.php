<?php 
	include("db.php");
	session_start();

	include("motto.php");

	date_default_timezone_set('Asia/Manila');
	$dateToday = date('Y-m-d'); //get the date today to compare
	$dateToday = $dateToday . "T" . date('H:i'); //add T between for correct format when comparing
	$dateToday = date_create($dateToday); //parse to date

	//default is no filter
	$finishFilter = false;
	$currentFilter = false;
	$futureFilter = false;

	//check if what filter is selected
	if (!isset($_POST['offFilterBtn'])) { //check if filter off is not on (huh haha)
		if (isset($_POST['finishBtn'])) {
			$finishFilter = true;
		} else if (isset($_POST['currentBtn'])) {
			$currentFilter = true;
		} else if (isset($_POST['futureBtn'])) {
			$futureFilter = true;
		}
	}

	$isSearchCode = false; //default

	if (isset($_POST['searchCodeBtn'])) { //check if searching for code
		$searchCode = $_POST['reserveCode'];
		$isSearchCode = true;
	}

	/* 
	dates are save in session to allow multiple filters
	eg: date filter with finish reservation
	eg: date filter with code
	(note: only date filter can combine to the other filters,
		future/finish/current reservation and code cannot be combined)
	 */
	if(!isset($_SESSION['isDateFilter'])) { //setup if not set
		$_SESSION['isDateFilter'] = false;
	}

	if(isset($_POST['filterDate'])) { //check if filter is activated
		$_SESSION['fromDate'] = $_POST['fromDate'];
		$_SESSION['toDate'] = $_POST['toDate'];
		$_SESSION['isDateFilter'] = true;
	}

	if ($_SESSION['isDateFilter']) { //if filter activated, get dates from session
		$fromDate = $_SESSION['fromDate'];
		$toDate = $_SESSION['toDate'];
	}

	if(isset($_POST['removeFilterDate'])) { //remove filter
		$_SESSION['fromDate'] = "";
		$_SESSION['toDate'] = "";
		$_SESSION['isDateFilter'] = false;
	}

?>
<html lang="en">
  	<head>
	  	<title>Reservations</title>
	    <meta charset="utf-8">
	    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
	    <link rel="stylesheet" href="css/unicornic.css">
	    <link rel="stylesheet" href="css/reservations.css">
	    <script src="bootstrap/jquery/jquery.min.js"></script>
		<script src="bootstrap/js/bootstrap.min.js"></script>
	</head>
  	<body data-spy="scroll" data-target=".navbar" data-offset="80">
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
	        				echo "<li class='active'><a href='#'>Reservations</a></li>";
        					echo "<li><a href='offers.php'>Special Offers</a></li>";
        					echo "<li><a href='reports.php'>Statistics</a></li>";
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
		<!--Reservation List-->
		<div class="card panel panel-default text-center panel-info" id="reservations">
			<h3 class="panel-heading">Reservation List</h3>
			<div class="panel-body">
				<div id="filter-setting">
					<h4><strong>Filter Settings</strong></h4>
					<hr>
					<form class="form-inline" method="post" 
					action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
						<label for="fromDate">From: </label>
						<input id="fromDate" name="fromDate" class="form-control" 
						type="date" required="true" value=
						<?php
							if ($_SESSION['isDateFilter']) {
								echo $fromDate; //if there's filter, put the value here
							}
						?>></input>
						&nbsp;&nbsp;&nbsp;&nbsp;
						<label for="toDate">To: </label>
						<input id="toDate" name="toDate" class="form-control" 
						type="date" required="true" value=
						<?php
							if ($_SESSION['isDateFilter']) {
								echo $toDate; //if there's filter, put the value here
							}
						?>></input>
						<br>
						<br>
						<?php
							if($_SESSION['isDateFilter']) {
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
					<hr>
					<form class="form-inline" method="post" 
					action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
						<label for="reserveCode">Reservation Code: </label>
						<input onchange="validateCode()" oninput="validateCode()" 
						id="reserveCode" name="reserveCode" class="form-control" required="true"></input>
						<button class="btn btn-default" name="searchCodeBtn">
							<span class="glyphicon glyphicon-search"></span>
							 Search
						</button>
					</form>
					<hr>
					<form  method="post" 
					action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
						<div class="row">
							<div class="col-xs-4">
								<button class="btn btn-success" name="finishBtn">
									<span class="glyphicon glyphicon-check"></span>
									<small>Finished Reservation</small>
								</button>
							</div>
							<div class="col-xs-4">
								<button class="btn btn-info" name="currentBtn">
									<span class="glyphicon glyphicon-edit"></span>
									<small>Current Reservation</small>
								</button>
							</div>
							<div class="col-xs-4">
								<button class="btn btn-warning" name="futureBtn">
									<span class="glyphicon glyphicon-share"></span>
									<small>Future Reservation</small>
								</button>
							</div>
						</div>
						<br>
						<?php
							//check if there's filter. if true, add remove filter button
							if ($finishFilter || $futureFilter || $currentFilter
								|| $isSearchCode) { 
								echo "<p class='text-left'>";
								if ($finishFilter) {	
									echo "Current Filter: ";
									echo "<button class='btn btn-success' disabled='true'>";
									echo "<span class='glyphicon glyphicon-check'></span>";
									echo "<small> Finished Reservation</small>";
									echo "</button>";	
								} else if ($futureFilter) {
									echo "Current Filter: ";
									echo "<button class='btn btn-warning' disabled='true'>";
									echo "<span class='glyphicon glyphicon-share'></span>";
									echo "<small> Future Reservation</small>";
									echo "</button>";	
								} else if ($currentFilter) {
									echo "Current Filter: ";
									echo "<button class='btn btn-info' disabled='true'>";
									echo "<span class='glyphicon glyphicon-edit'></span>";
									echo "<small> Current Reservation</small>";
									echo "</button>";	
								} else if ($isSearchCode) {
									echo "<strong>Searching For: </strong>" . strtoupper($searchCode);
								}

								echo "&nbsp;&nbsp;"; //gap
								echo "<button class='btn btn-danger' name='offFilterBtn'>";
								echo "<span class='glyphicon glyphicon-remove-sign'></span>";
								echo "<small> Disable Filter</small>";
								echo "</button>";
								echo "</p>";
							}
						?>
					</form>
				</div>
				<br>
				<div class="table-responsive">
					<table class="table table-condensed table-bordered">
						<thead>
							<tr>
								<th>Reservation Date</th>
								<th>Reservation Code</th>
								<th>Reserved Room</th>
								<th>Check In</th>
								<th>Check Out</th>
								<th>Reservation Price</th>
								<th>Adult</th>
								<th>Adult Price</th>
								<th>Child</th>
								<th>Child Price</th>
								<th>Total Price</th>
								<th>Client ID</th>
								<th>Client Name</th>
							</tr>
						</thead>
						<tbody>
							<?php
								$sql = "SELECT date, code, room, checkIn, checkOut, 
									stayPrice, adultCount, adultPrice, childCount, 
									childPrice, totalPrice, clientID, 
									concat(LastName, ', ' , FirstName) AS name 
									FROM reservation, clients where ID=clientID";

								if ($isSearchCode) {
									$sql .= " AND code='$searchCode'";
								}

								$result = mysqli_query($conn, $sql);

								if (mysqli_num_rows($result) == 0) {
									echo "<tr><td  colspan='13'>
									<big>No Results Found.<big>
									</td></tr>";
								}

								while($row = mysqli_fetch_assoc($result)) { //retreive all reservations

									/* Format Date */
									$reservePrase = date_create($row['date']);
									$checkInParse = date_create($row['checkIn']);
									$checkOutParse = date_create($row['checkOut']);

									$reserveDate = date_format($reservePrase, 'M d, Y');
									$reserveDay = date_format($reservePrase, 'D');
									$reserveTime = date_format($reservePrase, 'g:i A');

									$checkInDate = date_format($checkInParse, 'M d, Y');
									$checkInDay = date_format($checkInParse, 'D');
									$checkInTime = date_format($checkInParse, 'g:i A');

									$checkOutDate = date_format($checkOutParse, 'M d, Y');
									$checkOutDay = date_format($checkOutParse, 'D');
									$checkOutTime = date_format($checkOutParse, 'g:i A');

									$reserveDate = $reserveDate . " " . $reserveTime . ", " . $reserveDay;
									$checkIn = $checkInDate . " " . $checkInTime . ", " . $checkInDay;
									$checkOut = $checkOutDate . " " . $checkOutTime . ", " . $checkOutDay;



									if($_SESSION['isDateFilter']) {
										//parse here for faster condition.
										//parse here because string is needed for value on input
										$fromDate = date_create($_SESSION['fromDate']);
										$toDate = date_create($_SESSION['toDate']);
										//check if check in OR check out is inside filter
										if (!($checkInParse >= $fromDate && 
											$checkInParse <= $toDate) ||
											!($checkOutParse >= $fromDate &&
												$checkOutParse <= $toDate)) {
											continue;
										}
									}

									if ($dateToday > $checkOutParse &&
										!$currentFilter && !$futureFilter) { //check if filter is off
										echo "<tr class='bg-success'>";
									} else if ($dateToday < $checkInParse &&
										!$currentFilter && !$finishFilter) {
										echo "<tr class='bg-warning'>";
									} else if ($dateToday < $checkOutParse && $dateToday > $checkInParse &&
										!$finishFilter && !$futureFilter) {
										echo "<tr class='bg-info'>";
									} else { //if filtered, skip if not true
										continue; //skip loop
									}

									
									echo "<td>".$reserveDate."</td>";
									echo "<td>".$row['code']."</td>";
				                    echo "<td>".$row['room']."</td>";
				                    echo "<td>".$checkIn."</td>";
				                    echo "<td>".$checkOut."</td>";
				                    echo "<td>&#8369; ".$row['stayPrice']."</td>";
				                    echo "<td>
				                    		<span class='glyphicon glyphicon-user'></span> " 
				                    		.$row['adultCount'].
				                    	"</td>";
				                    echo "<td>&#8369; ".$row['adultPrice']."</td>";
				                    echo "<td>
				                    		<span class='glyphicon glyphicon-user'></span> "
				                    		.$row['childCount'].
				                    	"</td>";
				                    echo "<td>&#8369; ".$row['childPrice']."</td>";
				                    echo "<td><b>&#8369; ".$row['totalPrice']."</b></td>";
				                    echo "<td>".$row['clientID']."</td>";
				                    echo "<td>".$row['name']."</td>";
									echo "</tr>";
								}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>		
		<!--End of Reservation List-->
		<footer class="footer-basic-centered">
			<p class="footer-company-motto"><?php echo $motto;?></p>
			<p class="footer-links" id="scroll-effect">
				<a href="index.php#home">Home</a>
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