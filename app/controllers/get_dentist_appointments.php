<?php
session_start();

// Include database configuration and 'appointment' model files
require_once '../../config/database.php';
require_once '../models/appointment.php';

// Check if user is logged in and has appropriate role (e.g., dentist)
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] != 'dentist') {
    // If user is not logged in or does not have appropriate role, return error
    echo json_encode(["error" => "You don't have permissions!"]);
    exit;
}

// Establish database connection
$database = new Database();
$db = $database->getConnection();

// Initialize Appointment object
$appointment = new Appointment($db);

// Get appointments for the logged-in dentist
$dentistAppointments = $appointment->getAppointmentsByDentist($_SESSION['user_id']);

// Return appointment data in JSON format
echo json_encode($dentistAppointments);
?>
