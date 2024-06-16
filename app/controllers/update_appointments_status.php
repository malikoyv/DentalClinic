<?php
session_start(); // Start session

require_once '../../config/database.php'; // Include database configuration
require_once '../models/appointment.php'; // Include 'appointment' model

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Check if user is logged in and has the appropriate role (dentist)
    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'dentist') {
        echo json_encode(["error" => "Unauthorized access"]); // Return error for lack of permissions
        exit;
    }

    $database = new Database();
    $db = $database->getConnection(); // Create database connection

    $appointments = new Appointment($db);

    // Update appointment status to 'completed'
    $updatedRows = $appointments->updateStatusToCompleted();

    // Return information about the number of updated appointments
    echo json_encode(['success' => true, 'message' => "Updated status for $updatedRows appointments."]);
} else {
    // Return error if request method is not POST
    echo json_encode(['error' => 'Unsupported request method']);
}
?>
