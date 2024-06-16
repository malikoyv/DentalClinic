<?php
session_start();

// Check if the user is logged in as an administrator
// If not, redirect to the dentist login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'administrator') {
    header("location: ../views/dentist_login.php");
    exit;
}

// Load database configuration and dentist model
require_once '../../config/database.php';
require_once '../models/dentist.php';

// Create a connection to the database
$database = new Database();
$db = $database->getConnection();
$dentist = new Dentist($db);

// Check if the dentist ID has been passed and is not empty
if (isset($_GET["dentist_id"]) && !empty(trim($_GET["dentist_id"]))) {
    $dentist_id = trim($_GET["dentist_id"]);  // Remove unnecessary spaces for security

    // Check if the dentist is not an administrator
    if ($dentist->isAdministrator($dentist_id)) {
        // If so, set an error message and redirect to the admin panel
        $_SESSION['error_message'] = "Cannot delete a dentist with the role of administrator.";
        header("location: ../views/admin_panel.php");
        exit;
    }

    // Attempt to delete the dentist
    if ($dentist->delete($dentist_id)) {
        // Log the action and set a success message
        error_log("Deleted dentist with ID: $dentist_id");
        $_SESSION['success_message'] = "Successfully deleted the dentist.";
        header("location: ../views/admin_panel.php");
        exit;
    } else {
        // In case of error, set a message and redirect
        $_SESSION['error_message'] = "An error occurred while deleting the dentist.";
        header("location: ../views/admin_panel.php");
        exit;
    }
} else {
    // Redirect to the admin panel if the dentist ID was not passed
    error_log("Dentist ID was not passed");
    header("location: ../views/admin_panel.php");
    exit;
}
?>