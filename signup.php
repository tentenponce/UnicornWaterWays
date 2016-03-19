<?php
	include("db.php");
	session_start();

	$fname = $_POST['fname'];
	$lname = $_POST['lname'];
	$phoneno = $_POST['phoneno'];
	$email = $_POST['email'];
	$password = $_POST['password'];
	$address = $_POST['address'];

	if ($conn) {
		//query email if already registered.
		$sql = "SELECT * FROM clients WHERE email='$email'";
		$result = mysqli_query($conn, $sql);

		if(mysqli_num_rows($result) == 1){ //check if entered email does exists.
			$_SESSION['errorHeader'] = "Already Registered";
			$_SESSION['errorDesc'] = "Entered Email is already registered. Please Login.";
			echo "<script>window.location.href = 'error.php';</script>";
		} else {
			mysqli_query($conn, "INSERT INTO clients(FirstName, LastName, PhoneNo, Email, Password, Address, money) 
			VALUES ('$fname', '$lname', '$phoneno', '$email', '$password', '$address', 10000.00)");

			$sql = "SELECT id, FirstName, LastName, money, email FROM clients WHERE email='$email' AND password='$password'";
			$result = mysqli_query($conn, $sql);
			
			while($row = mysqli_fetch_assoc($result)) { //retreive client info
				$_SESSION['userID'] = $row['id'];
				$_SESSION['fname'] = $row["FirstName"];
				$_SESSION['lname'] = $row["LastName"];
				$_SESSION['money'] = $row["money"];
				$_SESSION['email'] = $row["email"];
			}

			//fill the success information
			$_SESSION['successHeader'] = "Successfully Registered"; //header
			$_SESSION['successDesc'] = "Thank you for Registering, " . 
						$fname . " " . $lname; //description

			//automatically login
			$_SESSION['login'] = 1;

			//redirect to success.php to indicate successfully registered.
			echo "<script>window.location.href = 'success.php';</script>";
		}
	} else {
		$_SESSION['errorHeader'] = "Server Offline";
		$_SESSION['errorDesc'] = "Sorry, Server is offline, Try again later.";
		echo "<script>window.location.href = 'error.php';</script>";
	}
?>