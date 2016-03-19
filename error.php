<?php session_start(); //start session?>
<html lang="en">
	<head>
	  	<title>Unicorn Water Ways</title>
	    <meta charset="utf-8">
	    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
	    <link rel="stylesheet" href="css/unicornic.css">
	    <link rel="stylesheet" href="css/error.css">
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
			        	<li><a href="#about">About Us</a></li>
			      	</ul>
			      	<ul class="nav navbar-nav navbar-right">
			        	<li class="active">
			        		<a href="signup.html"><span class="glyphicon glyphicon-user"></span> Sign Up</a>
			        	</li>
			        	<li>
			        		<a href="login.html"><span class="glyphicon glyphicon-log-in"></span> Login</a>
			        	</li>
			      	</ul>
		    	</div>
		 	</div>
		</nav>
		<!--End of Navigation Bar-->
		<!--Success Info-->
		<div class="card panel panel-default text-center panel-danger">
			<h3 class="panel-heading">
				<?php
					//retreive the error header
					if (!empty($_SESSION['errorHeader'])) {
						echo $_SESSION['errorHeader'];
					}
				?>
			</h3>
			<div class="panel-body">
				<strong>
					<?php 
						//get the error description
						if (!empty($_SESSION['errorDesc'])) {
							echo $_SESSION['errorDesc'];
						}
					?> 
				</strong>
				<br>
				<br>
				<a href="index.php#home">Return to Home Page</a>
			</div>
		</div> <!--Success Info-->
		<!--Footer-->
		<footer class="footer-basic-centered">
			<p class="footer-company-motto">The company motto.</p>
			<p class="footer-links" id="scroll-effect">
				<a href="index.php#home">Home</a>
				·
				<a href="">About</a>
				·
				<a href="#">Sign Up</a>
				·
				<a href="login.html">Login</a>
			</p>
			<p class="footer-company-name">Unicorn Water Ways &copy; 2015</p>
		</footer>
		<!--End of Footer-->
	</body>
</HTML>