<?php
	include("db.php");
	session_start();

	$sql = "SELECT motto FROM motto";
	$result = mysqli_query($conn, $sql);

	while($row = mysqli_fetch_assoc($result)) { //get Company Motto
		$motto = $row['motto'];
	}
?>
<html lang="en">
  	<head>
	  	<title>Feedbacks</title>
	    <meta charset="utf-8">
	    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
	    <link rel="stylesheet" href="css/unicornic.css">
	    <link rel="stylesheet" href="css/feedback.css">
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
        					echo "<li class='active'><a href='#'>Feedbacks</a></li>";
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
	<!--Feedback-->
	<div class="card panel panel-default text-center panel-info">
		<h3 class="panel-heading">Client Feedbacks</h3>
		<div class="panel-body">
			<?php
				$sql = "SELECT userID, FirstName, LastName, Email, msg, date, love FROM feedback LEFT JOIN clients ON userID=id";
				$result = mysqli_query($conn, $sql);

				$counter = 0; //for index of buttons

				while($row = mysqli_fetch_assoc($result)) {
					$userID = $row['userID'];
					$clientName = $row['FirstName'] . " " . $row['LastName'];
					$msg = $row['msg'];
					$email = $row['Email'];
					$date = date_create($row['date']);
					$love = $row['love'];

					$todayDate = date_format($date, 'M d, Y');
					$todayDay = date_format($date, 'D');
					$todayTime = date_format($date, 'g:i A');
				
					$concatDate = $todayDate . " " . $todayTime;
					
					echo "<div class='clientComment'><br>";
					echo "<h4 id='clientName'><strong>" . $clientName . "</strong></h4>";
					echo "<p><small>$email</small></p>";
					echo "<hr>";
					echo "<p>" . $msg . "</p>";
					echo "<hr>";
						echo "<div class='row'>";
							echo "<p class='col-xs-6 text-left'>";
								if ($love == 0) {
									echo "<button class='btn btn-default love-button' onclick='processLove($counter, $userID);'>";
								} else {
									echo "<button class='btn btn-danger love-button' onclick='processLove($counter, $userID);'>";
								}
								echo "<span class='glyphicon glyphicon-heart'></span></button>";
							echo "</p>";
							echo "<p class='col-xs-6 text-right'><small>$concatDate</small></p>";
						echo "</div>";
					echo "</div>";
					echo "<br>";

					$counter += 1;
				}
			?>
		</div>
	</div>
	<!--Feedback-->	
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
	<script src="js/feedback.js"></script>
</html>
