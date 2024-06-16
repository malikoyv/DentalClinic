<?php
session_start();

// Include database configuration and appointment model
require_once '../../config/database.php';
require_once '../models/appointment.php';

// Check if the request is of POST type and contains an appointment ID
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['appointment_id'])) {
    $appointment_id = $_POST['appointment_id']; // Get the appointment ID from POST data

    // Create a connection to the database
    $database = new Database();
    $db = $database->getConnection();

    // Create an appointment object and assign it the appointment ID
    $appointment = new Appointment($db);
    $appointment->appointment_id = $appointment_id;

    // Attempt to cancel the appointment by the dentist
    if ($appointment->cancelByDentist()) {
        // If successful, return a success message
        echo json_encode(["message" => "Scheduled appointment has been cancelled"]);
    } else {
        // Otherwise, return an error message
        echo json_encode(["message" => "An error occurred. Failed to cancel the appointment"]);
    }
}
?>
