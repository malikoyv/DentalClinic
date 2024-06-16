<?php
session_start();

// Check if the user is logged in as an administrator
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'administrator') {
    header("location: ../views/login.php"); // Redirect to login page if the user does not have permissions
    exit;
}

// Include configuration and model files
require_once '../../config/database.php';
require_once '../models/dentist.php';

// Create a connection to the database
$database = new Database();
$db = $database->getConnection();
$dentist = new Dentist($db);

// Initialize variables to store data and potential errors
$first_name = $last_name = $email = $password = $specialization = "";
$first_name_err = $last_name_err = $email_err = $password_err = $specialization_err = "";

// Handle POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get data from the form and trim unnecessary spaces
    $first_name = trim($_POST["first_name"]);
    $last_name = trim($_POST["last_name"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $specialization = trim($_POST["specialization"]);

    // Validate email address
    if ($dentist->isEmailExists($email)) {
        $email_err = "The email address is already in use."; // Set error if email already exists
    }

    // Check if there are no validation errors
    if (empty($first_name_err) && empty($last_name_err) && empty($email_err) && empty($password_err) && empty($specialization_err)) {
        // Set dentist data
        $dentist->first_name = $first_name;
        $dentist->last_name = $last_name;
        $dentist->email = $email;
        $dentist->password = $password;
        $dentist->specialization = $specialization;

        // Attempt to create a new dentist record
        if ($dentist->create()) {
            $_SESSION['success_message'] = "Successfully added a new dentist!";
            header("location: ../views/admin_panel.php"); // Redirect to the admin panel
            exit;
        } else {
            echo "An error occurred while adding the dentist."; // Display error if unable to add the dentist
        }
    } else {
        $_SESSION['error_message'] = "Failed to add the dentist. " . $email_err; // Set error message
        header("location: ../views/admin_panel.php"); // Redirect to the admin panel
        exit;
    }
}
?>