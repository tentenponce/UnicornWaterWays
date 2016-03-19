<?php 
	include("amenities.php"); //db.php is included on amenities.php

	$sql = "SELECT motto FROM motto";
	$result = mysqli_query($conn, $sql);

	while($row = mysqli_fetch_assoc($result)) { //get Company Motto
		$motto = $row['motto'];
	}

	$sql = "SELECT * FROM prices";
	$result = mysqli_query($conn, $sql);

	while($row = mysqli_fetch_assoc($result)) { //retreive adult price and child discount
		$adultPrice = $row["adult"];
		$childDiscount = $row["discount"];
	}

	$sql = "SELECT roomID, name, percent FROM discount LEFT JOIN amenities ON id=roomID";
	$result = mysqli_query($conn, $sql);
	$isSpecialOffer = false;

	if (mysqli_num_rows($result) > 0) {
		$isSpecialOffer = true;
	}

	$cottageOffer = false;
	$regOffer = false;
	$grandOffer = false;
	$supOffer = false;

	if ($isSpecialOffer) { //check if theres atleast 1 discount
		while ($row = mysqli_fetch_assoc($result)) {
			/* Check what room has offer */
			$roomID = $row['roomID'];

			if ($roomID == 1) {
				$cottageOffer = true;
				$cottagePercent = $row['percent'];
			} else if ($roomID == 2) {
				$regOffer = true;
				$regPercent = $row['percent'];
			} else if ($roomID == 3) {
				$grandOffer = true;
				$grandPercent = $row['percent'];
			} else if ($roomID == 4) {
				$supOffer = true;
				$supPercent = $row['percent'];
			}
		}	
	}
?>

<html lang="en">
	<head>
	  	<title>Unicorn Water Ways</title>
	    <meta charset="utf-8">
	    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
	    <link rel="stylesheet" href="css/unicornic.css">
	    <link rel="stylesheet" href="css/index.css">
	    <script src="bootstrap/jquery/jquery.min.js"></script>
		<script src="bootstrap/js/bootstrap.min.js"></script>
	</head>
	<body>
		<div data-ride="carousel" class="carousel slide container-fluid text-center" id="home">
			<div id="dim">
				<!-- Indicators -->
				<ol class="carousel-indicators">
				    <li data-target="#home" data-slide-to="0" class="active"></li>
				    <li data-target="#home" data-slide-to="1"></li>
				    <li data-target="#home" data-slide-to="2"></li>
				    <li data-target="#home" data-slide-to="3"></li>
				    <li data-target="#home" data-slide-to="4"></li>
				    <li data-target="#home" data-slide-to="5"></li>
				    <li data-target="#home" data-slide-to="6"></li>
				</ol>
				<div class="carousel-inner" role="listbox">
				    <div class="item active">
				      <img src="img/suproom1.png" alt="Supreme Rooms">
				    </div>

				    <div class="item">
				      <img src="img/suproom2.png" alt="Supreme Rooms">
				    </div>

				    <div class="item">
				      <img src="img/suproom3.png" alt="Supreme Rooms">
				    </div>

				    <div class="item">
				      <img src="img/bg.png" alt="Main Entrance">
				    </div>

				    <div class="item">
				      <img src="img/regroom.png" alt="Regular Rooms">
				    </div>

				    <div class="item">
				      <img src="img/grandroom.png" alt="Grande Rooms">
				    </div>

				    <div class="item">
				      <img src="img/cottage.png" alt="Cottages">
				    </div>
				</div>
			</div>
			<div id="absolutePos">
				<img src="img/unicornLogoNoBg.png" height="250px" width="250px" class="img-responsive"></img>
				<p><?php echo $motto;?></p>
				<nav id="link-effect" class="cl-effect-14">
					<a href="#reserve"><span>Reserve Now!</span></a>
				</nav>
			</div>
		</div>
		<!--End of Home Page-->
		<!--Reservation Form-->
		<form onSubmit="return isValidForm()" role="form" id="reserve" class="card panel panel-default text-center panel-info" method="post" action="reserve.php">
			<h3 class="panel-heading">Reservation Form</h3>
			<div class="panel-body">
				<div class="form-group">
					<label for="roomtype" class="control-label">Type of Room: </label> 
					<select class="form-control" id="roomtype" name="roomType" onchange="roomTypeChange()">
					    <option><?php echo $room1name; ?></option>
					    <option><?php echo $room2name; ?></option>
					    <option><?php echo $room3name; ?></option>
					    <option><?php echo $room4name; ?></option>
					</select>
				</div>
				<div class="row">
					<div class="col-xs-6">
						<div id="checkInLayout" class="form-group">
							<label for="checkIn">Check In: </label>
							<input required="true" onchange="computeDatePrice()" oninput="computeDatePrice()" type="datetime-local" class="form-control" id="checkIn" name="checkIn" value="0" min="0">
						</div>
					</div>
					<div class="col-xs-6">
						<div id="checkOutLayout" class="form-group">
							<label for="checkOut">Check Out: </label> 
							<input required="true" onchange="computeDatePrice()" oninput="computeDatePrice()" type="datetime-local" class="form-control" id="checkOut" name="checkOut" value="0" min="0">
						</div>
					</div>
					<p>Room Price:</p>
					<p class="bg-info">
						<input id="datePrice" name="datePrice" value="0.00 PHP" readonly="true"></input>
					</p>
					<div class="alert alert-warning fade hide col-xs-12" id="dateAlert">
	 					<strong>Date Error.</strong>
	 					<font>Check In must be smaller than Check Out.</font>
					</div>
					<div class="col-xs-6">
						<div id="adultLayout" class="form-group">
							<label for="adult">Adult Count: </label>
							<input required="true" onchange="adultChange()" oninput="adultChange()" type="number" class="form-control" id="adult" name="adult" value="0" min="0">
						</div>
					</div>
					<div class="col-xs-6">
						<div id="childLayout" class="form-group">
							<label for="child">Child Count: </label> 
							<input required="true" onchange="childChange()" oninput="childChange()" type="number" class="form-control" id="child" name="child" value="0" min="0">
						</div>
					</div>
				</div>
				<div class="alert alert-warning fade hide" id="limitAlert">
	 				<strong>Warning</strong><font id="limitText"> has been reached</font>
				</div>
				<div>
					<p>Total Person(s):</p>
					<p id="totalperson" class="bg-info">0.00 PHP</p>
					<p>Total Price:</p>
					<p id="totalprice" class="bg-success">0.00 PHP</p>
				</div>
				<?php
					if(empty($_SESSION['admin'])) {
						echo "<button class='btn btn-success btn-lg'>
							<span class='glyphicon glyphicon-shopping-cart'></span>
							Proceed to Payment
						</button>";
					} else {
						echo "<button class='btn btn-success btn-lg disabled'>
							<span class='glyphicon glyphicon-shopping-cart'></span>
							Proceed to Payment
						</button>";
					}
				?>
			</div>
		</form>
		<!--Reservation Form End-->
		<!--Supreme Carousel-->
		<div class="card text-center panel panel-default" id="suproom">
			<h1 class="room-title panel-heading">
				<?php 
					echo $room4name . 
						" <small>($room4minPerson-$room4maxPerson Persons)</small>";
				?> 
			</h1>
			<div id="myCarousel" class="carousel slide" data-ride="carousel">
			  <!-- Indicators -->
			  <ol class="carousel-indicators">
			    <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
			    <li data-target="#myCarousel" data-slide-to="1"></li>
			    <li data-target="#myCarousel" data-slide-to="2"></li>
			  </ol>
			  <!-- Wrapper for slides -->
			  <div class="carousel-inner" role="listbox">
			    <div class="item active">
			      <img src="img/suproom1.png" alt="Supreme Rooms">
			    </div>

			    <div class="item">
			      <img src="img/suproom2.png" alt="Supreme Rooms">
			    </div>

			    <div class="item">
			      <img src="img/suproom3.png" alt="Supreme Rooms">
			    </div>
			  </div>

			  <!-- Left and right controls -->
			  <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
			    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
			    <span class="sr-only">Previous</span>
			  </a>
			  <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
			    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
			    <span class="sr-only">Next</span>
			  </a>
			</div>
			<br>
			<div class="room-info">
				<p class="">
					<b>Swimming:</b>
				 	Free
				 	<span class="glyphicon glyphicon-ok-sign"></span>
				</p>
				<p class="">
					<b>Food:</b>
					Free (Lunch OR Dinner)
					<span class="glyphicon glyphicon-ok-sign"></span>
				</p>
				<p class="">
					<b>Videoke:</b> 
					Free
					<span class="glyphicon glyphicon-ok-sign"></span>
				</p>
				<hr>
				<p class=""><b>Whole Day:</b></p>
				<?php
					if($supOffer) {
						echo "<p>";
						echo "<strike>&#8369; " . number_format(($room4dayPrice + $room4nightPrice), 2, '.', '') . "</strike>";
						echo "</p>";
						echo "<p class='price'>&#8369; ";
						echo "<font id='supPrice'>";
						//calculate room price - discount
						echo number_format(($room4dayPrice + $room4nightPrice) - 
							(($room4dayPrice + $room4nightPrice) * ($supPercent / 100)), 2, '.', '');
						echo "</p>";
					} else {
						echo "<p class='price'>&#8369; ";
						echo "<font id='supPrice'>" . number_format(($room4dayPrice + $room4nightPrice), 2, '.', '') . "</font>";
						echo "</p>";
					}
				?>
				<p>
					<a href="#reserve" onclick="changeSelRoom(3)" class="btn btn-success btn-lg">
						Reserve Supreme Room
						<span class="glyphicon glyphicon-share-alt"></span>
					</a>
				</p>
				<p>
					<?php
						if ($room4available == 0) {
							$roomAvailability = "<div class='red-circle'></div>";
						} else if ($room1available <= 3) {
							$roomAvailability = "<div class='yellow-circle'></div>";						
						} else {
							$roomAvailability = "<div class='green-circle'></div>";
						}

						$roomAvailability = $roomAvailability . 
							" <strong>Room Available:</strong> $room4available";

						echo $roomAvailability;
					?>
				</p>
			</div>
		</div>
		<!--Supreme Carousel End-->
		<!--Rooms and Promos (2 Column)-->
		<div class="row">
			<!--First Column-->
			<div class="col-sm-3" id="roomPromo">
				<!--Special Offers-->
				<div class="card text-center panel panel-default">
					<?php
						if(mysqli_num_rows($result) > 0) { //add glow effect if theres offers
							echo "<h4 class='panel-heading glow-offer'><b>Special Offers</b></h4>";
						} else {
							echo "<h4 class='panel-heading'><b>Special Offers</b></h4>";						
						}
					?>
					<div class="panel-body">
						<?php
							if ($isSpecialOffer) { //check if theres atleast 1 discount
								$sql = "SELECT roomID, name, percent FROM discount LEFT JOIN amenities ON id=roomID";
								$result = mysqli_query($conn, $sql);
								
								while ($row = mysqli_fetch_assoc($result)) {
									echo "<p><big>" . $row['name'] . ": <strong>" . $row['percent'] . "%</strong> discount</big></p>";
									echo "<hr>";
								}	
							} else {
								echo "<p>None</p>";
							}
						?>
					</div>
				</div>
				<!--End of Special Offers-->
				<!--Payment Information-->
				<div class="card text-center panel panel-default">
					<h4 class="panel-heading"><b>Payment Information</b></h4>
					<div class="panel-body">
						<p>
							<b>Adult (6 yrs old above):</b> 
							&#8369; 
							<font id="adultPrice"><?php echo $adultPrice; ?></font>
						</p>
						<p class="bg-success">
							<b>Child (6 yrs old below):</b> 
							<font id="childDiscount"><?php echo $childDiscount; ?></font>% 
							discount
						</p>
						<p><b>Day Time:</b> 6:00 AM - 7:00 PM</p>
						<p><b>Night Time:</b> 7:00 PM - 6:00 AM</p>
						<p><b>Upon Reservation:</b> Half of Total Payment</p>
						<p><b>During or Before End of Service:</b> The Other Half of Total Payment</p>
					</div>
				</div>
				<!--End of Payment Information-->
			</div>
			<!--End of First Column-->
			<!--Second Column Column-->
			<div class="col-sm-9">
				<!--First Card, Cottage-->
				<div id="cottage" class="card text-center roomInfo panel panel-default">
					<h2 class="room-title panel-heading">
						<?php 
							echo $room1name . 
								" <small>($room1minPerson-$room1maxPerson Persons)</small>";
						?> 
					</h2>
					<img class="img-responsive room-img" src="img/cottage.png"></img>
					<br>
					<div class="room-info">
						<p>
							<b>Swimming:</b>
							Free
							<span class="glyphicon glyphicon-ok-sign"></span>
						</p>
						<p class="">
							<b>Food:</b> 
							To be Purchased Inside
							<span class="glyphicon glyphicon-info-sign"></span>
						</p>
						<p class="">
							<b>Videoke:</b> 
							Not Available
							<span class="glyphicon glyphicon-remove-sign"></span>
						</p>
						<hr>
						<p class=""><b>Morning:</b></p>
						<?php
							if($cottageOffer) {
								echo "<p>";
								echo "<strike>&#8369; " . $room1dayPrice . "</strike>";
								echo "</p>";
								echo "<p class='price'>&#8369; ";
								echo "<font id='cottageDayPrice'>";
								//calculate room price - discount
								echo number_format($room1dayPrice - ($room1dayPrice * ($cottagePercent / 100)), 2, '.', '');
								echo "</p>";
							} else {
								echo "<p class='price'>&#8369; ";
								echo "<font id='cottageDayPrice'>" . $room1dayPrice . "</font>";
								echo "</p>";
							}
						?>
						<p class=""><b>Night:</b></p>
						<?php
							if($cottageOffer) {
								echo "<p>";
								echo "<strike>&#8369; " . $room1nightPrice . "</strike>";
								echo "</p>";
								echo "<p class='price'>&#8369; ";
								echo "<font id='cottageNightPrice'>";
								//calclate
								echo number_format($room1nightPrice - ($room1nightPrice * ($cottagePercent / 100)), 2, '.', '');
								echo "</p>";
							} else {
								echo "<p class='price'>&#8369; ";
								echo "<font id='cottageNightPrice'>" . $room1nightPrice . "</font>";
								echo "</p>";
							}
						?>
						<p>
							<a href="#reserve" onclick="changeSelRoom(0)" class="btn btn-success btn-lg">
						Reserve Cottage
								<span class="glyphicon glyphicon-share-alt"></span>
							</a>
						</p>
						<p>
							<?php
								if ($room1available == 0) {
									$roomAvailability = "<div class='red-circle'></div>";
								} else if ($room1available <= 3) {
									$roomAvailability = "<div class='yellow-circle'></div>";						
								} else {
									$roomAvailability = "<div class='green-circle'></div>";
								}

								$roomAvailability = $roomAvailability . 
									" <strong>Room Available:</strong> $room1available";

								echo $roomAvailability;
							?>
						</p>
					</div>
				</div>
				<!--End of First Card, Cottage-->
				<!--Second Card, Regular Room-->
				<div id="regroom" class="card text-center roomInfo panel panel-default">
					<h2 class="room-title panel-heading">
						<?php 
							echo $room2name . 
								" <small>($room2minPerson-$room2maxPerson Persons)</small>"; 
						?>
					</h2>
					<img class="img-responsive room-img" src="img/regroom.png"></img>
					<br>
					<div class="room-info">
						<p class="">
							<b>Swimming:</b> 
							&#8369; 100 Per Head
							<span class="glyphicon glyphicon-info-sign"></span>
						</p>
						<p class="">
							<b>Food:</b> 
							To be Purchased Inside
							<span class="glyphicon glyphicon-info-sign"></span>
						</p>
						<p class="">
							<b>Videoke:</b>
							Not Available
							<span class="glyphicon glyphicon-remove-sign"></span>
						</p>
						<hr>
						<p class=""><b>Morning:</b></p>
						<?php
							if($regOffer) {
								echo "<p>";
								echo "<strike>&#8369; " . $room2dayPrice . "</strike>";
								echo "</p>";
								echo "<p class='price'>&#8369; ";
								echo "<font id='regDayPrice'>";
								//calculate room price - discount
								echo number_format($room2dayPrice - ($room2dayPrice * ($regPercent / 100)), 2, '.', '');
								echo "</p>";
							} else {
								echo "<p class='price'>&#8369; ";
								echo "<font id='regDayPrice'>" . $room2dayPrice . "</font>";
								echo "</p>";
							}
						?>
						<p class=""><b>Night:</b></p>
						<?php
							if($regOffer) {
								echo "<p>";
								echo "<strike>&#8369; " . $room2nightPrice . "</strike>";
								echo "</p>";
								echo "<p class='price'>&#8369; ";
								echo "<font id='regNightPrice'>";
								//calculate room price - discount
								echo number_format($room2nightPrice - ($room2dayPrice * ($regPercent / 100)), 2, '.', '');
								echo "</p>";
							} else {
								echo "<p class='price'>&#8369; ";
								echo "<font id='regNightPrice'>" . $room2nightPrice . "</font>";
								echo "</p>";
							}
						?>
						<p>
							<a href="#reserve" onclick="changeSelRoom(1)" class="btn btn-success btn-lg">
								Reserve Regular Room
								<span class="glyphicon glyphicon-share-alt"></span>
							</a>
						</p>
						<p>
							<?php
								if ($room2available == 0) {
									$roomAvailability = "<div class='red-circle'></div>";
								} else if ($room2available <= 3) {
									$roomAvailability = "<div class='yellow-circle'></div>";						
								} else {
									$roomAvailability = "<div class='green-circle'></div>";
								}

								$roomAvailability = $roomAvailability . 
									" <strong>Room Available:</strong> $room2available";

								echo $roomAvailability;
							?>
						</p>
					</div>
				</div>
				<!--End of Second Card, Regular Rooms-->
				<!--Third Card, Grande Room-->
				<div id="grandroom" class="card text-center roomInfo panel panel-default">
					<h2 class="room-title panel-heading">
						<?php 
							echo $room3name . 
								" <small>($room3minPerson-$room3maxPerson Persons)</small>"; 
						?>
					</h2>
					<img class="img-responsive room-img" src="img/grandroom.png"></img>
					<br>
					<div class="room-info">
						<p class="">
							<b>Swimming:</b> 
							&#8369; 100 Per Head
							<span class="glyphicon glyphicon-info-sign"></span>
						</p>
						<p class="">
							<b>Food:</b> 
							To be Purchased Inside
							<span class="glyphicon glyphicon-info-sign"></span>
						</p>
						<p class="">
							<b>Videoke:</b> 
							&#8369; 500
							<span class="glyphicon glyphicon-info-sign"></span>
						</p>
						<hr>
						<p class=""><b>Morning:</b></p>
						<?php
							if($grandOffer) {
								echo "<p>";
								echo "<strike>&#8369; " . $room3dayPrice . "</strike>";
								echo "</p>";
								echo "<p class='price'>&#8369; ";
								echo "<font id='grandDayPrice'>";
								//calculate room price - discount
								echo number_format($room3dayPrice - ($room3dayPrice * ($grandPercent / 100)), 2, '.', '');
								echo "</p>";
							} else {
								echo "<p class='price'>&#8369; ";
								echo "<font id='grandDayPrice'>" . $room3dayPrice . "</font>";
								echo "</p>";
							}
						?>
						<p class=""><b>Night:</b></p>
						<?php
							if($grandOffer) {
								echo "<p>";
								echo "<strike>&#8369; " . $room3nightPrice . "</strike>";
								echo "</p>";
								echo "<p class='price'>&#8369; ";
								echo "<font id='grandNightPrice'>";
								//calculate room price - discount
								echo number_format($room3nightPrice - ($room3nightPrice * ($grandPercent / 100)), 2, '.', '');
								echo "</p>";
							} else {
								echo "<p class='price'>&#8369; ";
								echo "<font id='grandNightPrice'>" . $room3nightPrice . "</font>";
								echo "</p>";
							}
						?>
						<p>
							<a href="#reserve" onclick="changeSelRoom(2)" class="btn btn-success btn-lg">
								Reserve Grande Room
								<span class="glyphicon glyphicon-share-alt"></span>
							</a>
						</p>
						<p>
							<?php
								if ($room3available == 0) {
									$roomAvailability = "<div class='red-circle'></div>";
								} else if ($room3available <= 3) {
									$roomAvailability = "<div class='yellow-circle'></div>";						
								} else {
									$roomAvailability = "<div class='green-circle'></div>";
								}

								$roomAvailability = $roomAvailability . 
									" <strong>Room Available:</strong> $room3available";

								echo $roomAvailability;
							?>
						</p>
					</div>
				</div>
			</div>
			<!--End of Second Column-->
		</div>
	</body>
	<script src="js/main.js"></script>
</html>