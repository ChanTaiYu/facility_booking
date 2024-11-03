<?php 
	
	session_start();
	unset($_SESSION["uid"]);
	unset($_SESSION["email"]);
	unset($_SESSION["role"]);
	echo "<script>window.location.replace('index.php');</script>";

?>