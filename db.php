<?php
$host = "localhost";
$user = "root";
$db = "unicornic";
$conn = mysqli_connect($host, $user, "", $db);

echo "<script>console.log('Connected to Database.');</script>";
?>