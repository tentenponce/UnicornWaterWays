<?php 
	include("db.php");
	include("amenities.php");
	include("motto.php"); //get the company motto specially for footer
?>
<html lang="en">
  	<head>
	  	<title>Special Offers</title>
	    <meta charset="utf-8">
	    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
	    <link rel="stylesheet" href="css/unicornic.css">
	    <link rel="stylesheet" href="css/offers.css">
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
        					echo "<li class='active'><a href='#'>Special Offers</a></li>";
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
		<!--Discount-->
		<div class="row" id="specialOffers">
			<div class="card panel panel-default text-center panel-info offers">
				<h3 class="panel-heading">Discount</h3>
				<form class="panel-body" method="POST" action="processOffer.php">
					<div class="form-group">
						<select class="form-control" id="roomtype" name="roomType">
						    <option><?php echo $room1name; ?></option>
						    <option><?php echo $room2name; ?></option>
						    <option><?php echo $room3name; ?></option>
						    <option><?php echo $room4name; ?></option>
						</select>
					</div>
					<div class="form-group">	
						<input class="form-control" type="number" name="discount" placeholder="Discount Percentage" onChange="discountPercentage()" onInput="discountPercentage()" id="discount" min="0" max="100" value="0">
					</div>
					<button class="btn btn-success" id="discountButton">
						Add Discount Offer
						<span class="glyphicon glyphicon-share-alt"></span>
					</button>
					<div>
						<hr>
						<p><h4><strong>Current Offers:</strong></h4></p>
						<table class="table table-striped table-bordered">
							<thead>
								<tr>
									<th>Room Type</th>
									<th>Discount</th>
								</tr>
							</thead>
							<tbody>
							<?php
								$sql = "SELECT name, percent FROM discount LEFT JOIN amenities ON roomID=id";
								$result = mysqli_query($conn, $sql);

								if (mysqli_num_rows($result) == 0) {
									echo "<tr><td colspan='2'>None</td></tr>";
								} else {
									while($row = mysqli_fetch_assoc($result)) { 
										echo "<tr>";
										echo "<td>" . $row['name'] . "</td>";
										echo "<td>" . $row['percent'] . "%</td>";
										echo "</tr>";			
									}
								}
							?>
							</tbody>
						</table>
						<p><small><b>Note: </b>To remove a Special Offer, put 0 on Discount Percentage.</small></p>
					</div>
				</form>
			</div>
		</div>
		<!--Discount-->
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
	<script src="js/discount.js"></script>
</html>