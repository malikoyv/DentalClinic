<?php
session_start();

// Include database configuration and 'appointment' model files
require_once '../../config/database.php';
require_once '../models/appointment.php';

if (!isset($_SESSION['user_id'])) {
    // If the user is not logged in, return an error
    echo json_encode(["error" => "You are not logged in"]);
    exit;
}

// Create a new database connection
$database = new Database();
$db = $database->getConnection();

// Create a new 'appointment' object
$appointment = new Appointment($db);

// Get appointments for the logged-in patient
$patientAppointments = $appointment->getPatientAppointments($_SESSION['user_id']);

// Return appointments in JSON format
echo json_encode($patientAppointments);
?>
