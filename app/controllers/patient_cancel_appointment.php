<?php
session_start(); // Start the session

require_once '../../config/database.php'; // Include the database configuration file
require_once '../models/appointment.php'; // Include the 'appointment' model

// Check if the request method is POST and if appointment_id is provided
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['appointment_id'])) {
    $appointment_id = $_POST['appointment_id']; // Get the appointment ID from the form

    $database = new Database();
    $db = $database->getConnection(); // Create a new database connection

    $appointment = new Appointment($db); // Create an instance of Appointment
    $appointment->appointment_id = $appointment_id; // Assign the appointment ID to the object

    // Attempt to cancel the appointment by the patient
    if ($appointment->cancelByPatient()) {
        // If cancellation is successful, return success message
        echo json_encode(["message" => "Your appointment has been canceled"]);
    } else {
        // If cancellation fails, return error message
        echo json_encode(["message" => "Failed to cancel the appointment"]);
    }
}
?>
