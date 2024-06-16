<?php
session_start();

// Include database configuration and 'availability' model files
require_once '../../config/database.php';
require_once '../models/availability.php';

// Check if the user is logged in and has dentist permissions
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'dentist') {
    header("location: ../views/dentist_login.php"); // Redirect to the login page if the user doesn't have permissions
    exit;
}

// Establish a database connection
$database = new Database();
$db = $database->getConnection();
$availability = new Availability($db);

// Retrieve availability data for the logged-in dentist
$data = $availability->getAllAvailability($_SESSION['user_id']);

// Set HTTP headers for exporting data in CSV format
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="availability.csv"');

// Open output stream for CSV file
$output = fopen('php://output', 'w');

// Define and write column headers in the CSV file
fputcsv($output, array('ID Dostępności', 'Dentysta ID', 'Czas Rozpoczęcia', 'Czas Zakończenia', 'Cena', 'Nazwa'));

// Iterate through data and write each row to the CSV file
array_walk($data, "fput");

// Close the output stream
fclose($output);
exit; // End the script execution

function fput($row){
    // Open output stream for CSV file
    $output = fopen('php://output', 'w');
    fputcsv($output, $row); // Write row to CSV file
}
?>