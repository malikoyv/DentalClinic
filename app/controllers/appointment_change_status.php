<?php
// Start a new session or resume an existing one
session_start();

// Required files: database configuration and 'appointment' model
require_once '../config/database.php';
require_once '../models/appointment.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the user is logged in and has the role 'dentist'
    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'dentist') {
        // If not, return an error
        echo json_encode(["error" => "Unauthorized access"]);
        exit;
    }

    // Retrieve appointment ID and new status from POST data
    $appointmentId = $_POST['appointment_id'];
    $newStatus = $_POST['new_status'];

    // Create a new database connection
    $database = new Database();
    $db = $database->getConnection();

    // Create a new Appointment object
    $appointment = new Appointment($db);

    // Attempt to change the status of the appointment
    $result = $appointment->changeStatus($appointmentId, $newStatus);

    // Check if the operation was successful
    if ($result) {
        // If yes, return a success message
        echo json_encode(['success' => true, 'message' => "Appointment status has been changed."]);
    } else {
        // If not, return an error
        echo json_encode(['error' => 'Failed to change appointment status']);
    }
} else {
    // If the request method is not POST, return an error
    echo json_encode(['error' => 'Unsupported request method']);
}
?>