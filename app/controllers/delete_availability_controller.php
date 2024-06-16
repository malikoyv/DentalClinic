<?php
// Start the session
session_start();

// Check if the user is logged in, if not, redirect to the login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'dentist') {
    header("location: ../views/dentist_login.php");
    exit;
}

// Check if the parameter availability_id has been passed
if (isset($_GET['availability_id'])) {

    // Include the file with the database connection configuration
    require_once '../../config/database.php';

    // Include the availability.php file containing the definition of the Availability class
    require_once '../models/availability.php';

    // Create a database connection
    $database = new Database();
    $db = $database->getConnection();

    // Create an object of the Availability class
    $availability = new Availability($db);

    // Set the availability_id of the Availability object based on the availability_id parameter passed in the URL
    $availability->availability_id = $_GET['availability_id'];

    // Delete the availability
    if ($availability->delete()) {
        // Set the success message
        $_SESSION['success_message'] = "Availability has been deleted.";
    } else {
        // Set the error message
        $_SESSION['error_message'] = "Failed to delete availability.";
    }
}

// Redirect to the dentist panel page
header("location: ../views/dentist_panel.php");
exit;
?>