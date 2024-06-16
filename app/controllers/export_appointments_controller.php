<?php
session_start();

// Include the database configuration and 'appointment' model files
require_once '../../config/database.php';
require_once '../models/appointment.php';

// Check if the user is logged in and has dentist permissions
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'dentist') {
    header("location: ../views/dentist_login.php"); // Redirect to the login page if the user doesn't have permissions
    exit;
}

// Create a connection to the database
$database = new Database();
$db = $database->getConnection();
$appointments = new Appointment($db);

// Retrieve appointment data for the logged-in dentist
$data = $appointments->getAppointmentsByDentist($_SESSION['user_id']);

// Set HTTP headers for exporting data in CSV format
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="appointments.csv"');

// Open output stream for the CSV file
$output = fopen('php://output', 'w');

// Define and write column headers to the CSV file
fputcsv($output, array('Appointment ID', 'Date and Time', 'Status', 'First Name', 'Last Name'));

// Iterate through the data and write each row to the CSV file
array_walk($data, "fput");

// Close the output stream
fclose($output);
exit; // End the script

// Function for array_walk -> writing a row of data
function fput($row){
    // Open output stream for the CSV file
    $output = fopen('php://output', 'w');
    fputcsv($output, $row);
}
?>
