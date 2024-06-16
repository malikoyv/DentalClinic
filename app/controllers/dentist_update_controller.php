<?php
error_log("Dentist data edit form has been submitted."); // Log activity

// Start session and include configuration files
session_start();
require_once '../../config/database.php';
require_once '../models/dentist.php';

// Check user permissions
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'administrator') {
    header("location: ../views/dentist_login.php"); // Redirect to login page if user is not an administrator
    exit;
}

// Create a connection to the database
$database = new Database();
$db = $database->getConnection();
$dentist = new Dentist($db);

// Initialize variables to store form data
$firstName = $lastName = $email = $specialization = "";
$update_err = "";

// Handle POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $dentist_id = isset($_POST['dentist_id']) ? trim($_POST['dentist_id']) : null; // Get dentist ID

    // Validate form data
    // Validate first name
    if (empty(trim($_POST["first_name"]))) {
        $update_err = "Please enter a first name.";
    } else {
        $firstName = trim($_POST["first_name"]);
    }

    // Validate last name
    if (empty(trim($_POST["last_name"]))) {
        $update_err .= "\nPlease enter a last name.";
    } else {
        $lastName = trim($_POST["last_name"]);
    }

    // Validate email
    if (empty(trim($_POST["email"]))) {
        $update_err .= "\nPlease enter an email.";
    } else {
        $email = trim($_POST["email"]);
    }

    // Validate specialization
    if (empty(trim($_POST["specialization"]))) {
        $update_err .= "\nPlease enter a specialization.";
    } else {
        $specialization = trim($_POST["specialization"]);
    }

    // If there are no errors, proceed with profile update
    if (empty($update_err)) {
        // Check if the email is already used
        if (!empty($email) && $dentist->isEmailUsedByAnotherDentist($dentist_id, $email)) {
            $_SESSION['update_err'] = "The provided email address is used by another dentist.";
            header("location: ../views/admin_panel.php");
            exit;
        }

        // Attempt to update dentist profile
        if ($dentist->updateProfile($dentist_id, $firstName, $lastName, $email, $specialization)) {
            $_SESSION['update_success'] = "Dentist data has been successfully updated.";
            header("location: ../views/admin_panel.php");
            exit;
        } else {
            $_SESSION['update_err'] = "An error occurred while updating the data.";
            header("location: ../views/admin_panel.php");
            exit;
        }
    } else {
        // In case of validation errors, pass them to the session
        $_SESSION['update_err'] = $update_err;
        header("location: ../views/dentist_edit.php");
        exit;
    }
}

// Close the database connection
unset($db);
?>
