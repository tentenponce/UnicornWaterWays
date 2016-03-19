<?php 
	include("amenities.php");

	$room1dayPrice = number_format($room1dayPrice, 0, '', '');
	$room2dayPrice = number_format($room2dayPrice, 0, '', '');
	$room3dayPrice = number_format($room3dayPrice, 0, '', '');
	$room4dayPrice = number_format($room4dayPrice, 0, '', '');

	$room1nightPrice = number_format($room1nightPrice, 0, '', '');
	$room2nightPrice = number_format($room2nightPrice, 0, '', '');
	$room3nightPrice = number_format($room3nightPrice, 0, '', '');
	$room4nightPrice = number_format($room4nightPrice, 0, '', '');

	include("motto.php");
?>
<html lang="en">
  	<head>
	  	<title>Settings</title>
	    <meta charset="utf-8"> 
	    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
	    <link rel="stylesheet" href="css/unicornic.css">
	    <link rel="stylesheet" href="css/settings.css">
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
	        					echo "<li><a href='reports.php'>Statistics</a></li>";
	        					echo "<li><a href='feedback.php'>Feedbacks</a></li>";
	        					echo "<li class='active'><a href='#'>Settings</a></li>";
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
		<!--Settings-->
		<div class="card panel panel-default text-center panel-info settings">
			<h3 class="panel-heading">System Settings</h3>
			<form class="panel-body" action="saveSettings.php" method="post">
				<?php
					if(isset($_GET['success'])) {
						echo "<p class='bg-success'><strong>Settings updated Successfully.</strong></p>";
					}
				?>
				<div class="setting-card">
					<h4><strong>Room Settings</strong></h4>
					<hr>
					<div class="table-responsive">
						<table class="table table-condensed table-bordered table-striped form-inline">
							<thead>
								<th class="text-center">Room Name</th>
								<th class="text-center">Morning Price</th>
								<th class="text-center">Night Price</th>
							</thead>
							<tbody>
								<tr class="text-center">
									<td><?php echo $room1name; ?></td>
									<td>
										<label for="morningCPrice">&#8369;</label>
										<input onchange="priceChange(0)" oninput="priceChange(0)" class="form-control price-input" name="morningCPrice" id="morningCPrice"
											type="number" min="0" value="<?php echo $room1dayPrice; ?>"></input>
									</td>
									<td>
										<label for="nightCPrice">&#8369;</label>
										<input onchange="priceChange(1)" oninput="priceChange(1)" class="form-control price-input" name="nightCPrice" id="nightCPrice"
											type="number" min="0" value="<?php echo $room1nightPrice; ?>"></input>
									</td>
								</tr>
								<tr class="text-center">
									<td><?php echo $room2name; ?></td>
									<td>
										<label for="morningRPrice">&#8369;</label>
										<input onchange="priceChange(2)" oninput="priceChange(2)" class="form-control price-input" name="morningRPrice" id="morningRPrice"
											type="number" min="0" value="<?php echo $room2dayPrice; ?>"></input>
									</td>
									<td>
										<label for="nightRPrice">&#8369;</label>
										<input onchange="priceChange(3)" oninput="priceChange(3)" class="form-control price-input" name="nightRPrice" id="nightRPrice"
											type="number" min="0" value="<?php echo $room2nightPrice; ?>"></input>
									</td>
								</tr>
								<tr class="text-center">
									<td><?php echo $room3name; ?></td>
									<td>
										<label for="morningGPrice">&#8369;</label>
										<input onchange="priceChange(4)" oninput="priceChange(4)" class="form-control price-input" name="morningGPrice" id="morningGPrice"
											type="number" min="0" value="<?php echo $room3dayPrice; ?>"></input>
									</td>
									<td>
										<label for="nightGPrice">&#8369;</label>
										<input onchange="priceChange(5)" oninput="priceChange(5)" class="form-control price-input" name="nightGPrice" id="nightGPrice"
											type="number" min="0" value="<?php echo $room3nightPrice; ?>"></input>
									</td>
								</tr>
								<tr class="text-center">
									<td><?php echo $room4name; ?></td>
									<td colspan="2">
										<label for="sPrice">(Whole Day) &#8369;</label>
										<input onchange="priceChange(6)" oninput="priceChange(6)" class="form-control price-input" name="sPrice" id="sPrice"
											type="number" min="0" value="<?php echo $room4dayPrice + $room4nightPrice; ?>"></input>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<br>
				<div class="setting-card">
					<h4><strong>Company Motto</strong></h4>
					<hr>
					<textarea rows="5" class="form-control" id="motto" name="motto" 
					size="50" required="true"><?php echo $motto;?></textarea>
					<br>
				</div>
				<br>
				<button class="btn btn-success">
					Save Changes
					<span class="glyphicon glyphicon-share-alt"></span>
				</button>
			</form>
		</div>
		<!--End of Settings-->
		<!--Footer-->
		<footer class="footer-basic-centered">
			<p class="footer-company-motto"><?php echo $motto;?></p>
			<p class="footer-links" id="scroll-effect">
				<a href="index.php#home">Home</a>
				·
				<a href="">About</a>
				·
				<a href="signup.html">Sign Up</a>
				·
				<a href="">Login</a>
			</p>
			<p class="footer-company-name">Unicorn Water Ways &copy; 2015</p>
		</footer>
		<!--End of Footer-->
	</body>
	<script src="js/settings.js"></script>
</html>