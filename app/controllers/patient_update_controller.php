<?php

// Display error information
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start session
session_start();

// Include database configuration and patient class
require_once '../../config/database.php';
require_once '../models/patient.php';

// Check if user is logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../views/login.php"); // Redirect to login page if user is not logged in
    exit;
}

$database = new Database();
$db = $database->getConnection();
$patient = new Patient($db);

$firstName = $lastName = $email = "";
$update_err = "";

// Process form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate first name
    if (empty(trim($_POST["first_name"]))) {
        $update_err = "Please enter your first name.";
    } else {
        $firstName = trim($_POST["first_name"]);
    }

    // Validate last name
    if (empty(trim($_POST["last_name"]))) {
        $update_err .= "\nPlease enter your last name.";
    } else {
        $lastName = trim($_POST["last_name"]);
    }

    // Validate email
    if (empty(trim($_POST["email"]))) {
        $update_err .= "\nPlease enter your email.";
    } else {
        $email = trim($_POST["email"]);
    }

    // Update profile if there are no validation errors
    if (empty($update_err)) {
        // Check if email is already used by another patient
        if ($patient->isEmailUsedByAnotherPatient($_SESSION['user_id'], $email)) {
            $_SESSION['update_err'] = "The email address is already in use.";
            header("location: ../views/patient_panel.php");
            exit;
        }

        // Attempt to update profile
        if ($patient->updateProfile($_SESSION['user_id'], $firstName, $lastName, $email)) {
            // Update session data and redirect
            $_SESSION["first_name"] = $firstName;
            $_SESSION["last_name"] = $lastName;
            $_SESSION["email"] = $email;

            // Set success message
            $_SESSION['update_success'] = "Profile information updated successfully.";
            header("location: ../views/patient_panel.php");
            exit;
        } else {
            // Set error message
            $_SESSION['update_err'] = "Error updating profile information.";
            header("location: ../views/patient_panel.php");
            exit;
        }
    } else {
        // Pass validation errors to session
        $_SESSION['update_err'] = $update_err;
        header("location: ../views/patient_panel.php");
        exit;
    }
}

// Close database connection
unset($db);
?>
