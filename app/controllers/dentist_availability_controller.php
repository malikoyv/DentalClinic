<?php
session_start();

// Check if the user is logged in as a dentist
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'dentist') {
    header("location: ../views/dentist_login.php"); // Redirect to the dentist login page if the user does not have permissions
    exit;
}

// Include configuration and availability model files
require_once '../../config/database.php';
require_once '../models/availability.php';

// Create a connection to the database
$database = new Database();
$db = $database->getConnection();
$availability = new Availability($db);

// Initialize variables to store data and potential errors
$start_time = $end_time = $name = $price = "";
$start_time_err = $end_time_err = $name_err = $price_err = "";

// Handle POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get data from the form and trim unnecessary spaces
    $dentist_id = $_SESSION['user_id'];
    $start_time = trim($_POST["start_time"]);
    $end_time = trim($_POST["end_time"]);
    $name = trim($_POST["name"]);
    $price = trim($_POST["price"]);

    // Validate start and end times
    if (empty($start_time)) {
        $start_time_err = "Please provide a start time.";
    }

    if (empty($end_time)) {
        $end_time_err = "Please provide an end time.";
    }

    // Check if end time is later than start time
    if (!empty($start_time) && !empty($end_time) && strtotime($start_time) >= strtotime($end_time)) {
        $end_time_err = "End time must be later than start time.";
    }

    // Check if the start time is not in the past
    $currentDateTime = date('Y-m-d H:i:s');
    if (!empty($start_time) && strtotime($start_time) < strtotime($currentDateTime)) {
        $start_time_err = "Start time cannot be in the past.";
    }

    // If there are no errors, proceed with creating or updating availability
    if (empty($start_time_err) && empty($end_time_err)) {
        $availability->dentist_id = $dentist_id;
        $availability->start_time = $start_time;
        $availability->end_time = $end_time;

        // Check if this is an update to existing availability
        if (isset($_POST['availability_id']) && !empty($_POST['availability_id'])) {
            $availability->availability_id = $_POST['availability_id'];
            $success = $availability->update();
        } else {
            // If not, create new availability
            $success = $availability->create();
        }

        // Handle response after attempting to create or update
        if ($success) {
            $_SESSION['success_message'] = "Availability successfully updated!";
            header("location: ../views/dentist_panel.php");
            exit;
        } else {
            echo "An error occurred while updating availability.";
        }
    } else {
        // If validation errors occurred, set appropriate error messages
        $_SESSION['start_time_err'] = $start_time_err;
        $_SESSION['end_time_err'] = $end_time_err;
        header("location: ../views/dentist_panel.php");
        exit;
    }
}

// Release database connection resource
unset($db);
?>
