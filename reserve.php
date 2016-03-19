<?php
	include("db.php");
	session_start();
	
	if(!empty($_POST['roomType'])) { //check if it came from home
		$roomType = $_POST['roomType'];
		
		$checkIn = $_POST['checkIn'];
		$checkOut = $_POST['checkOut'];

		$datePrice = str_replace("PHP", "", $_POST['datePrice']); //substitute php to "". (remove)

		$adult = $_POST['adult'];
		$child = $_POST['child'];
	} else { //otherwise, get it from session 
		$roomType = $_SESSION['room'];
		
		$checkIn = $_SESSION['checkIn'];
		$checkOut = $_SESSION['checkOut'];

		$datePrice = str_replace("PHP", "", $_SESSION['stayPrice']); //substitute php to "". (remove)

		$adult = $_SESSION['adultCount'];
		$child = $_SESSION['childCount'];
	}

	$datePrice = number_format((int) $datePrice, 2, '.', ''); //convert to integer and add two decimal

	$sql = "SELECT * FROM prices";
	$result = mysqli_query($conn, $sql);

	while($row = mysqli_fetch_assoc($result)) { //retreive adult price and child discount
		$price = $row["adult"];
		$childDiscount = $row["discount"] / 100;
	}

	$adultPrice = $adult * $price; //total price of adult
	$childPrice = $child * ($childDiscount * $price); //total price of child

	$adultPrice = number_format($adultPrice, 2, '.', ''); //add two decimal places
	$childPrice = number_format($childPrice, 2, '.', ''); //add two decimal places

	$totalPrice = $datePrice + $adultPrice + $childPrice; //computes total price
	$totalPrice = number_format($totalPrice, 2, '.', ''); //add two decimal places

	/* Format Date */
	$checkInParse = date_create($checkIn);
	$checkOutParse = date_create($checkOut);

	$checkInDate = date_format($checkInParse, 'F d, Y');
	$checkInDay = date_format($checkInParse, 'l');
	$checkInTime = date_format($checkInParse, 'g:i A');

	$checkOutDate = date_format($checkOutParse, 'F d, Y');
	$checkOutDay = date_format($checkOutParse, 'l');
	$checkOutTime = date_format($checkOutParse, 'g:i A');
	
	// store temporarily on session to be passed to another page and insert to database.
	$_SESSION['room'] =  $roomType;
	$_SESSION['checkIn'] = $checkIn;
	$_SESSION['checkOut'] = $checkOut;
	$_SESSION['stayPrice'] =  $datePrice;
	$_SESSION['adultCount'] = $adult;
	$_SESSION['adultPrice'] = $adultPrice;
	$_SESSION['childCount'] = $child;
	$_SESSION['childPrice'] = $childPrice;
	$_SESSION['totalPrice'] = $totalPrice;
?>
<html lang="en">
  	<head>
	  	<title>Reservation</title>
	    <meta charset="utf-8">
	    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
	    <link rel="stylesheet" href="css/unicornic.css">
		<link rel="stylesheet" href="css/reserve.css">
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
		    		<a href="#home" class="navbar-brand">
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
						        <li>
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
		<!--End of Navigation Bar-->
		<!--MONEY SYSTEM REMOVED
		<?php
			if(!empty($_SESSION['login'])) {
				echo "
				<div id='reserve' class='card panel panel-default text-center panel-success'>
					<h3 class='panel-heading'>Welcome, " . $_SESSION['fname'] . " " . $_SESSION['lname'] . "</h3>
					<div class='panel-body'>
						<h4>Your Remaining Balance: <strong>&#8369; " . $_SESSION['money'] . "</strong></h4>
					</div>
				</div>
				";
			}
		?>
		MONEY SYSTEM REMOVED-->
		<!--Reserve Info-->
		<div class="card panel panel-default panel-info text-center" id="reserveInfo">
			<h3 class="panel-heading">
				Reservation Information
			</h3>
			<div class="panel-body">
				<h4>
					<!--Reserve room-->
					<strong>Reserved Room:</strong>
					<br>
					<br>
					<p><?php echo $roomType; ?></p>
					<!--End of Reserve room-->
					<!--Check In Check Out-->
					<hr>
					<div class="row">
						<div class="col-xs-6">
							<strong>Check In: </strong>
							<br>
							<br>
							<p>
								<?php
									echo "<p>" . $checkInDate . ", " . $checkInDay . "</p>";
									echo $checkInTime;
								?>
							</p>
						</div>
						<div class="col-xs-6">
							<strong>Check Out: </strong>
							<br>
							<br>
							<p>
								<?php
									echo "<p>" . $checkOutDate . ", " . $checkOutDay . "</p>";
									echo $checkOutTime;
								?>
							</p>
						</div>
						<p>&#8369; <?php echo $datePrice; ?></p>
					</div>
					<!--End of Check In Check Out-->
					<!--Adult and Child-->
					<hr>
					<?php 
						if ($adult > 0) {
							echo "<p><strong>Adults: </strong>$adult</p>";
						} else {
							echo "<p><strong>Adult: </strong>$adult</p>";
						}

						echo "<p>&#8369; " . $adultPrice . "</p>";
					?>
					<br>
					<?php
						if ($child > 0) {
							echo "<p><strong>Child: </strong>$child</p>";
						} else {
							echo "<p><strong>Children: </strong>$child</p>";
						}

						echo "<p>&#8369; " . $childPrice . "</p>";
					?>
					<!--Adult and Child-->
					<hr>
					<p><b>Total Price: </b></p>
					<p id="totalPrice">&#8369; <?php echo $totalPrice; ?></p>
					<hr>
					<div id="payment-details" class="panel panel-default">
						<div class="panel-heading">
							<div class="row">
								<div class="col-xs-6">
									<h4 class="text-left"><strong>Payment Details</strong></h4>
								</div>
								<div class="col-xs-6">
									<img class="img-responsive pull-right" 
										src="img/paymentCard.png">
								</div>
							</div>
						</div>
						<div class="panel-body">
							<div class="alert alert-danger fade hide" id="errorBank">
				 				<p>
									<small>Account Number and pin Code did not match.</small>
								</p>
							</div>
							<div class="alert alert-success fade hide" id="successBank">
				 				<p>
									<small>Account Verified Successfully. Submitting Reservation...</small>
								</p>
							</div>
							<div class="row">
								<div class="col-xs-7">
									<input class="form-control" id="accNumber" 
									placeholder="Account Number" required="true">
								</div>
								<div class="col-xs-5">
									<input class="form-control" type="password" id="pinNumber"
									placeholder="Pin Code" required="true">
								</div>
							</div>
						</div>
					</div>
					<hr>
					<?php
						if(!empty($_SESSION['login'])) { //check if login
							if($_SESSION['money'] >= ($totalPrice / 2) || true) { //check if sufficient balance
								echo "
								<button class='btn btn-success' id='submitReserve' 
									onclick='verifyBankAcc();'>
									Submit Reservation
									<span class='glyphicon glyphicon-share-alt'></span>
								</button>
							";	
							} else {
								echo "
								<button class='btn btn-success disabled' id='submitReserve'>
									Submit Reservation
									<span class='glyphicon glyphicon-share-alt'></span>
								</button>
								<br>
								<br>
								<p class='bg-danger'><small><b>Insufficient Balance</b></small></p>
							";
							}
							
						} else { //else disabled button
							echo "
							<button class='btn btn-success' id='submitReserve' disabled='true'>
								Submit Reservation
								<span class='glyphicon glyphicon-share-alt'></span>
							</button>
							<br>
							<br>
							<p><small>Please Login Below.</small></p>";
						}
					?>
					<br>
					<br>
					<p><small>Half of the total payment will be deducted from your account,</small></p>
					<p><small>Pay the other half during or after you claim your reservation on Unicorn Water Ways.</small></p>
				</h4>
			</div>
		</div> <!--Reserve Info-->
		<?php
			if(empty($_SESSION['login'])) { //check if customer is logged in
				//Login Form		
				echo "
				<form role='form' id='reserve' class='card panel panel-default text-center panel-warning' method='post' action='login.php?reserve=1'>
					<h3 class='panel-heading'>Login Form</h3>
					<div class='panel-body'>
						<div class='form-group'>
							<input type='text' class='form-control' id='username' name='email' placeholder='Email Address'>
						</div>
						<div class='form-group'>
							<input type='password' class='form-control' id='pass' name='password' placeholder='Password'>
						</div>
						<button class='btn btn-success'>
							Login
							<span class='glyphicon glyphicon-share-alt'></span>
						</button>
					</div>
				</form>
				";
		}
		?>
		<!--Footer-->
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
		<!--End of Footer-->
	</body>
	<script src="js/reserve.js"></script>
</html>