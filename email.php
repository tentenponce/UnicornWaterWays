<?php
require_once    ('PHPMailer-master/class.phpmailer.php');
require_once    ('PHPMailer-master/class.smtp.php');

$mail               = new PHPMailer();
$body               = "
<h3>Reservation Code:</h3>
<h1>02222160100C7</h1>
<p>Please keep this reservation code as this will serve as your
confirmation on your reservation at Unicorn Water Ways.
Fuck you.</p>";
$mail->IsSMTP();                                        // telling the class to use SMTP
$mail->SMTPDebug    = 0;                                // enables SMTP debug information (for testing)
$mail->SMTPAuth     = true;                             // enable SMTP authentication
$mail->SMTPSecure   = "tls";                            // sets the prefix to the servier
$mail->Host         = "smtp.gmail.com";                 // sets GMAIL as the SMTP server
$mail->Port         = 587;                              // set the SMTP port for the GMAIL server

$mail->Username     = "poncetenten10@gmail.com"  ;           // GMAIL username
$mail->Password     = '10101996tenn' ;           // GMAIL password

$mail->SetFrom('UnicornWaterWays@gmail.com', 'Unicorn Water Ways');
$mail->Subject    = "Unicorn Water Ways";
$mail->MsgHTML($body);
$address = "albinokimi@gmail.com";
$mail->AddAddress($address, "Client SEND");

// $mail->AddAttachment("images/phpmailer.gif");        // attachment
// $mail->AddAttachment("images/phpmailer_mini.gif");   // attachment

if(!$mail->Send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
} 
else {
    echo "Message sent!";
}

?>