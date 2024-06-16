<?php
session_start();

// Clearing session array
$_SESSION = array();

// Session destroying
session_destroy();

// Redirecting to the main page
header("location: ../views/index.php");
exit; // Script ending