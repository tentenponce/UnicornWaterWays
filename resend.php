<?php
	//===PREPARE EMAIL===//
	require_once    ('PHPMailer-master/class.phpmailer.php');
	require_once    ('PHPMailer-master/class.smtp.php');
	session_start();

	$code = $_GET['code'];
	$email = $_SESSION['email'];

	$mail               = new PHPMailer();
	$body               = "
	<h3>Reservation Code:</h3>
	<h1>$code</h1>
	<p>Please keep this reservation code as this will serve as your
	confirmation on your reservation at Unicorn Water Ways.
	Thank you.</p>";
	$mail->IsSMTP();                                        // telling the class to use SMTP
	$mail->SMTPDebug    = 0;                                // enables SMTP debug information (for testing)
	$mail->SMTPAuth     = true;                             // enable SMTP authentication
	$mail->SMTPSecure   = "tls";                            // sets the prefix to the servier
	$mail->Host         = "smtp.gmail.com";                 // sets GMAIL as the SMTP server
	$mail->Port         = 587;                              // set the SMTP port for the GMAIL server

	$mail->Username     = "unicornwaterways@gmail.com"  ;           // GMAIL username
	$mail->Password     = 'unicornic' ;           // GMAIL password

	$mail->SetFrom('unicornwaterways@gmail.com', 'Unicorn WaterWays');
	$mail->Subject    = "Unicorn Water Ways";
	$mail->MsgHTML($body);
	$address = $email;
	$mail->AddAddress($address, "Client");

	// $mail->AddAttachment("images/phpmailer.gif");        // attachment
	// $mail->AddAttachment("images/phpmailer_mini.gif");   // attachment

	if(!$mail->Send()) {
	    //echo "<script>alert('Mailer Error: " . $mail->ErrorInfo . " email: $email');</script>";
	    $isSend = false;
	} 
	else {
	   //echo "Message sent!";
		$isSend = true;
	}
	//===END OF EMAIL===//	

	echo "<script>window.location.href = 'reserveSuccess.php?code=$code&isSend=$isSend';</script>";	
?>