<?php
error_log("Login form submitted."); // Logging form submission
error_log("Email: " . $_POST['email']); // Logging email address

ini_set('display_errors', 1); // Enable error display
ini_set('display_startup_errors', 1); // Enable startup error display
error_reporting(E_ALL); // Set error reporting level

session_start(); // Start a new session or resume existing session

// Include configuration files and the 'patient' model
require_once '../../config/database.php';
require_once '../models/patient.php';

$database = new Database();
$db = $database->getConnection(); // Establish database connection

$email = $password = ""; // Initialize variables
$email_err = $password_err = ""; // Initialize error variables

// Process form data upon submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter your email.";
    } else {
        $email = trim($_POST["email"]);
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Attempt login after validation
    if (empty($email_err) && empty($password_err)) {
        $user = new Patient($db);
        if ($user->login($email, $password)) {
            // Redirect to patient panel upon successful login
            header("location: ../views/patient_panel.php");
            exit;
        } else {
            // Pass login error to session and redirect back to login form
            $_SESSION['login_err'] = "Incorrect email or password.";
            header("location: ../views/patient_login.php");
            exit;
        }
    } else {
        // Pass validation errors to session
        $_SESSION['email_err'] = $email_err;
        $_SESSION['password_err'] = $password_err;
        header("location: ../views/patient_login.php");
        exit;
    }

    // Close database connection
    unset($db);
}
?>
