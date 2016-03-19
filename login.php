<?php
	include("db.php");
	session_start();

	$email = $_POST['email'];
	$password = $_POST['password'];

	if($email == "admin" && $password == "12345") {
		$_SESSION['admin'] = 1;
		$_SESSION['login'] = 1;
		$_SESSION['fname'] = "Administrator";
		$_SESSION['lname'] = "";

		//fill the success information
		$_SESSION['successHeader'] = "Login Successful"; //header
		$_SESSION['successDesc'] = "Welcome Back, " . 
					$_SESSION['fname'] . " " . $_SESSION['lname']; //description

		//redirect to success.php to indicate successfully registered.
		echo "<script>window.location.href = 'success.php';</script>";
	}

	if ($conn) {
		$sql = "SELECT id, FirstName, LastName, money, Email FROM clients WHERE email='$email' AND password='$password'";
		$result = mysqli_query($conn, $sql);

		if(mysqli_num_rows($result) == 1){
			while($row = mysqli_fetch_assoc($result)) { //retreive client info
				$_SESSION['userID'] = $row['id'];
				$_SESSION['fname'] = $row["FirstName"];
				$_SESSION['lname'] = $row["LastName"];
				$_SESSION['money'] = $row["money"];
				$_SESSION['email'] = $row["Email"];
			}

			//fill the success information
			$_SESSION['successHeader'] = "Login Successful"; //header
			$_SESSION['successDesc'] = "Welcome Back, " . 
						$_SESSION['fname'] . " " . $_SESSION['lname']; //description

			//automatically login
			$_SESSION['login'] = 1;

			if(!empty($_GET['reserve'])) {
				if($_GET['reserve'] == 1) { //check if it came from reservation to redirect back.
					//redirect to success with reserve to return reservation info
					echo "<script>window.location.href = 'success.php?reserve=1';</script>";
				}
			}
			
			//redirect to success.php to indicate successfully registered.
			echo "<script>window.location.href = 'success.php';</script>";
		} else {
			$_SESSION['errorHeader'] = "Incorrect Email or Password";
			$_SESSION['errorDesc'] = "Email and Password you entered did not match. 
				Please <a href='signup.html'>Sign Up</a> if you are not registered.";
			echo "<script>window.location.href = 'error.php';</script>";
		}
	}
?>