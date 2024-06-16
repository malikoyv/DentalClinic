<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../views/login.php"); // Redirect to login page if user is not logged in
    exit;
}

require_once '../../config/database.php'; // Include database configuration
require_once '../models/patient.php'; // Include patient model

$database = new Database();
$db = $database->getConnection();
$patient = new Patient($db);

// Initialize variables for password and errors
$current_password = $new_password = $confirm_new_password = "";
$password_err = $new_password_err = $confirm_new_password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate current password
    if (empty(trim($_POST["current_password"]))) {
        $password_err = "Please enter your current password.";
    } else {
        $current_password = trim($_POST["current_password"]);
    }

    // Validate new password
    if (empty(trim($_POST["new_password"]))) {
        $new_password_err = "Please enter a new password.";
    } elseif (strlen(trim($_POST["new_password"])) < 6) {
        $new_password_err = "Password must have at least 6 characters.";
    } else {
        $new_password = trim($_POST["new_password"]);
    }

    // Validate confirm new password
    if (empty(trim($_POST["confirm_new_password"]))) {
        $confirm_new_password_err = "Please confirm the new password.";
    } else {
        $confirm_new_password = trim($_POST["confirm_new_password"]);
        if (empty($new_password_err) && ($new_password != $confirm_new_password)) {
            $confirm_new_password_err = "Passwords do not match.";
        }
    }

    // Check for errors before updating in the database
    if (empty($password_err) && empty($new_password_err) && empty($confirm_new_password_err)) {
        // Attempt to change password in the database
        if ($patient->changePassword($_SESSION['patient_id'], $current_password, $new_password)) {
            // Success message for password change
            $_SESSION['update_success'] = "Password changed successfully.";
            header("location: ../views/patient_panel.php");
            exit;
        } else {
            // Error message for incorrect current password
            $_SESSION['password_err'] = "Incorrect current password.";
            header("location: ../views/patient_panel.php");
            exit;
        }
    } else {
        // Pass validation error messages to session
        $_SESSION['password_err'] = $password_err;
        $_SESSION['new_password_err'] = $new_password_err;
        $_SESSION['confirm_new_password_err'] = $confirm_new_password_err;
        header("location: ../views/patient_panel.php");
        exit;
    }
}

// Close database connection
unset($db);
?>
