<?php
	session_start();
	session_destroy(); //destroy all sessions

 	//return to home page/refresh.
	echo "<script>window.location.href = 'index.php#home';</script>";
?>