<?php
session_start();
require_once '../models/patient.php';
require_once '../models/dentist.php';

// Database connection
require_once '../../config/database.php';
$database = new Database();
$db = $database->getConnection();
$user = null; // Initialize $user variable

// Initialize user object based on role
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'patient') {
        $user = new Patient($db);
    } elseif ($_SESSION['role'] === 'doctor') {
        $user = new Dentist($db);
    }
} else {
    header("Location: patient_login.php");
    exit();
}

// Start a new conversation or get the existing one
$conversationId = null;
if ($_SESSION['role'] === 'patient') {
    if(isset($_GET['doctor_id'])) {
        $conversationId = $user->startConversation($_GET['doctor_id']);
    } else {
        // Handle case when 'doctor_id' parameter is not set
        // Redirect or show an error message
    }
} elseif ($_SESSION['role'] === 'doctor') {
    if(isset($_GET['conversation_id'])) {
        $conversationId = $_GET['conversation_id'];
    } else {
        // Handle case when 'conversation_id' parameter is not set
        header("Location: patient_login.php");
        exit();
    }
}

// Send a message
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if($user && isset($_POST['message'])) {
        // Validate and sanitize input
        $message = htmlspecialchars(strip_tags($_POST['message']));
        $user->sendMessage($conversationId, $message);
    }
}

// Get messages
$messages = [];
if($user) {
    $messages = $user->getMessages($conversationId);
}
?>
