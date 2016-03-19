<?php
	session_start();
	$isSend = $_GET['isSend'];
	$code = $_GET['code'];
?>
<html lang="en">
  	<head>
	  	<title>Finish Reservation</title>
	    <meta charset="utf-8"> 
	    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
	    <link rel="stylesheet" href="css/unicornic.css">	    
	    <link rel="stylesheet" href="css/reserveSuccess.css">
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
		    		<a href="#home" class="navbar-brand">
		    			<img src="img/unicornLogo2.png" height="40px" width="40px" style="margin-top: -9px"></img>
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
		<!--Login-->
		<div id="success" class="card panel panel-default text-center panel-info">
			<h3 class="panel-heading">Reserve Successful</h3>
			<div class="panel-body">
				<h4><strong>Confirmation Code:</strong></h4>
				<h4 class="bg-success">
					<?php 
						echo $code;
					?>
				</h4>
				<?php
					if($isSend) {
						echo "<p><small>Confirmation Code has been sent to your email.</small></p>";
					} else {
						echo "<p><small>Confirmation code failed to send to your email, Please click 
								<a href='resend.php?code=$code'>here</a> to resend to your email.</small></p>";
					}
				?>
				<br>
				<p><b>Important! </b></p>
				<p>
					This will serve as your validation when you claim your reservation at Unicorn Water Ways.
				</p>

				<a href="index.php#home">Return Home</a>
			</div>
		</div>
		<!--End of Login-->
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
				<a href="">Login</a>
			</p>
			<p class="footer-company-name">Unicorn Water Ways &copy; 2015</p>
		</footer>
		<!--End of Footer-->
	</body>
	<script src="js/unicornic.js"></script>
</html>