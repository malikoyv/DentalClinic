<?php 
	include('default.html');
	include('database.php');

	if(loggedin()) {
		deleteaccount($_SESSION['email']);
	}
	header("location:login.php");
 ?>