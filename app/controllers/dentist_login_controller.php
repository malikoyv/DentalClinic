<?php
error_log("Login form has been submitted."); // Log the login attempt
error_log("Email: " . $_POST['email']); // Log the submitted email address

// PHP error display settings (useful during development)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Include database configuration file and the 'dentist' model class
require_once '../../config/database.php';
require_once '../models/dentist.php';

// Create a connection to the database
$database = new Database();
$db = $database->getConnection();

// Initialize variables to store login data and potential errors
$email = $password = "";
$email_err = $password_err = "";

// Handle POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter an email.";
    } else {
        $email = trim($_POST["email"]);
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // If there are no validation errors, proceed with login
    if (empty($email_err) && empty($password_err)) {
        $user = new Dentist($db);
        // Attempt to log in the user
        if ($user->login($email, $password)) {
            // Check user role and redirect to the appropriate panel
            if ($_SESSION["role"] == 'dentist') {
                header("location: ../views/dentist_panel.php");
                exit;
            } elseif ($_SESSION["role"] == 'administrator') {
                header("location: ../views/admin_panel.php");
                exit;
            } else {
                // Additional roles or default redirection can be handled here
            }
        } else {
            // Login failed, set error message
            $_SESSION['login_err'] = "Invalid email or password.";
            header("location: ../views/dentist_login.php");
            exit;
        }
    } else {
        // In case of validation errors, redirect back to the login form
        $_SESSION['email_err'] = $email_err;
        $_SESSION['password_err'] = $password_err;
        header("location: ../views/dentist_login.php");
        exit;
    }

    // Close the database connection
    unset($db);
}
?>
