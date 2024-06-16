<?php
// Start a new session or resume an existing one
session_start();

// Required files: database configuration and 'appointment' model
require_once '../../config/database.php';
require_once '../models/appointment.php';

// Set response header to JSON content type
header('Content-Type: application/json');

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Read JSON data sent in the request
    $data = json_decode(file_get_contents('php://input'), true);

    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        // If not, return an error
        echo json_encode(["status" => "error", "message" => "You must be logged in to make a reservation."]);
        exit;
    }

    // Retrieve patient, dentist, and appointment date data from JSON
    $patient_id = $_SESSION['user_id'];
    $dentist_id = $data['dentist_id'] ?? null;
    
    $appointment_date = $data['appointment_date'] ?? null;
    if ($appointment_date) {
        $dateTime = new DateTime($appointment_date);
        $formattedDate = $dateTime->format('Y-m-d H:i:s');
        $appointment_date = $formattedDate;
    }

    try {
        // Create a new database connection
        $database = new Database();
        $db = $database->getConnection();

        // Create a new Appointment object and set its properties
        $appointment = new Appointment($db);
        $appointment->patient_id = $patient_id;
        $appointment->dentist_id = $dentist_id;
        $appointment->appointment_date = $appointment_date;
        $appointment->status = 'scheduled';

        // Attempt to create a new appointment
        if ($appointment->create()) {
            // Return a success message
            echo json_encode(["status" => "success", "message" => "The appointment has been successfully booked!"]);
        } else {
            // In case of failure, return an error
            echo json_encode(["status" => "error", "message" => "Failed to book the appointment."]);
        }
    } catch (PDOException $e) {
        // Log the exception and return an error message
        error_log('Error creating appointment: ' . $e->getMessage());
        echo json_encode(["status" => "error", "message" => "An error occurred while booking the appointment."]);
    }
}
?>