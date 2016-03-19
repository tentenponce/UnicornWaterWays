<?php 
	include("db.php");
	session_start();
	$id = $_SESSION['userID']; //get the id of the logged in user
	
	//get current feedback
	$sql = "SELECT msg FROM feedback WHERE userID=$id";
	$result = mysqli_query($conn, $sql);
	$msg = "";
	
	while($row = mysqli_fetch_assoc($result)) {
		$msg = $row['msg'];
	}

	//get current fname, lname, and pass of the user
	$sql = "SELECT FirstName, LastName, Password FROM clients WHERE id=$id";
	$result= mysqli_query($conn, $sql);

	while ($row = mysqli_fetch_assoc($result)) {
		$fname = $row['FirstName'];
		$lname = $row['LastName'];
		$pass = $row['Password'];
	}

	date_default_timezone_set('Asia/Manila');
	$dateToday = date('Y-m-d'); //get the date today to compare
	$dateToday = $dateToday . "T" . date('H:i'); //add T between for correct format when comparing
	$dateToday = date_create($dateToday); //parse to date

	//default is no filter
	$finishFilter = false;
	$currentFilter = false;
	$futureFilter = false;

	$futureBtn = false;
	$expiredBtn = false;

	if(!isset($_SESSION['isDateFilter'])) { //setup if not set
		$_SESSION['isDateFilter'] = false;
	}

	if(isset($_POST['futureBtn'])) {
		$futureBtn = true;
	}

	if(isset($_POST['expiredBtn'])) {
		$expiredBtn = true;
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

	$totalExpense = 0; //default
?>
<html lang="en">
  	<head>
	  	<title><?php echo $fname . " " . $lname; ?></title>
	    <meta charset="utf-8">
	    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
	    <link rel="stylesheet" href="css/unicornic.css">
	    <link rel="stylesheet" href="css/user.css">
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
	        				echo "<li><a href='reservations.php'>Reservations</a></li>";
        					echo "<li><a href='offers.php'>Special Offers</a></li>";
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
					        <li class='active'>
					        	<a href='#'>
					        		<span class='glyphicon glyphicon-user'></span> 
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
		<!--User Form-->
		<div class="card panel panel-default text-center panel-info">
			<h3 class="panel-heading">Welcome! <?php echo $_SESSION['fname'] . " " . $_SESSION['lname'];?></h3>
			<div class="panel-body">
				<?php
					if(isset($_SESSION['feedbackSuccess'])) {
						if ($_SESSION['feedbackSuccess'] == 1) {
							echo "
							<div class='alert alert-success fade hide' id='successAlert'>
 								<strong>Changes Successfully Saved.</strong>
							</div>";
							$_SESSION['feedbackSuccess'] = 0;
						}
					}
				?>
				<div class="alert alert-danger fade hide" id="limitAlert">
 					<strong>Error. </strong><font id="limitText"> Something error</font>
				</div>
				<div class="settings">
					<h4><strong>Transaction History</strong></h4>
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
					<form method="post" 
					action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
						<div class="row">
							<div class="col-xs-6">
								<button class="btn btn-info" name="futureBtn">
									<span class='glyphicon glyphicon-filter'></span>
									Future/On Going
								</button>
							</div>
							<div class="col-xs-6">
								<button class="btn btn-danger" name="expiredBtn">
									<span class='glyphicon glyphicon-filter'></span>
									Expired/Claimed
								</button>
							</div>
						</div>
						<br>
						<?php
							//check if there's filter. if true, add remove filter button
							if ($futureBtn || $expiredBtn) { 
								echo "<p class='text-left'>";
								if ($expiredBtn) {	
									echo "Current Filter: ";
									echo "<button class='btn btn-danger' disabled='true'>";
									echo "<span class='glyphicon glyphicon-check'></span>";
									echo "<small> Expired/Claimed</small>";
									echo "</button>";
								} else if ($futureBtn) {
									echo "Current Filter: ";
									echo "<button class='btn btn-info' disabled='true'>";
									echo "<span class='glyphicon glyphicon-edit'></span>";
									echo "<small> Future/On Going</small>";
									echo "</button>";	
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
					<br>
					<div id="transaction">
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
								</tr>
							</thead>
							<tbody>
								<?php
									$sql = "SELECT date, code, room, checkIn, checkOut, 
										stayPrice, adultCount, adultPrice, childCount, 
										childPrice, totalPrice
										FROM reservation where clientID = $id";

									$result = mysqli_query($conn, $sql);

									if (mysqli_num_rows($result) == 0) {
										echo "<tr><td  colspan='13'>
										<big>No Transactions.<big>
										</td></tr>";
									}

									$counter = 0; //atleast 5 transactions to show only

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
											!$futureBtn) { //check if filter is off
											echo "<tr class='bg-danger'>";
										} else if ($dateToday < $checkInParse &&
											!$expiredBtn) {
											echo "<tr class='bg-info'>";
										} else if ($dateToday < $checkOutParse && $dateToday > $checkInParse &&
											!$expiredBtn) {
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
										echo "</tr>";

										$totalExpense += $row['totalPrice'];
									}
								?>
							</tbody>
						</table>
					</div>
					<br>
					<p class="text-right">
						<h4>Total Expense(s): &#8369;
							<strong>
								<?php echo number_format($totalExpense, 2, '.', ','); ?>
							</strong></h4>
					</p>
				</div>
				<br>
				<div class="settings">
					<h4><strong>Account Settings</strong></h4>
					<hr>
					<div>
						<form method="post" action="saveUser.php" onsubmit="return isNameValid()">
							<div class="row">
								<div class="col-xs-4">
									<input class="form-control" placeholder="First Name"
									required="true" id="fname" name="fname" value=
									<?php
										echo $fname;
									?>>
								</div>
								<div class="col-xs-4">
									<input class="form-control" placeholder="Last Name"
									required="true" id="lname" name="lname" value=
									<?php
										echo $lname;
									?>>
								</div>
								<div class="col-xs-4">
									<button class="btn btn-success" id="changeNameButton"
										name="changeNameButton">
										Change Name
										<span class="glyphicon glyphicon-share-alt"></span>
									</button>
								</div>
							</div>
						</form>
						<hr>	
						<form method="post" action="saveUser.php" onsubmit="return isPassValid()"
							id="passwordForm">					
							<input class="form-control" placeholder="Old Password"
								id="oldPass" name="oldPass" maxLength="12" type="password" required="true">
							<br>
							<input class="form-control" placeholder="New Password"
									id="newPass" name="newPass" maxLength="12" type="password" 
									required="true">
							<br>
							<input class="form-control" placeholder="Confirm New Password"
									id="confirmNewPass" name="confirmNewPass" maxLength="12" type="password" 
									required="true">
							<br>
							<button class="btn btn-success" id="changePassButton">
								Change Password
								<span class="glyphicon glyphicon-share-alt"></span>
							</button>
						</form>
					</div>
				</div>
				<br>
				<form class="settings" method="post" action="saveUser.php">
					<h4><strong>Submit Review</strong></h4>
					<hr>
					<textarea rows="5" class="form-control" id="feedback" name="feedback" 
					placeholder="Comments/Feedback/Suggestions" size="50" 
					required="true"><?php echo $msg;?></textarea>
					<br>
					<button class="btn btn-success" id="feedbackButton"
						name="feedbackButton">
						Submit/Update Feedback
						<span class="glyphicon glyphicon-share-alt"></span>
					</button>
				</form>
			</div>
		</div>
		<!--User Form-->
		<!--End of Navigation Bar-->
		<footer class="footer-basic-centered">
			<p class="footer-company-motto">The company motto.</p>
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
	<script src="js/user.js"></script>
</html>