<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Simulated user data (Replace with database query)
    $user_data = [
        'email' => 'user@example.com',
        'password' => 'password123', // Hashed password in production
    ];

    // Get user input
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if user exists and password matches (Replace with database query)
    if ($email === $user_data['email'] && $password === $user_data['password']) {
        // Set session variables (Replace with actual user data from database)
        $_SESSION['user_id'] = 1;
        $_SESSION['email'] = $email;

        // Redirect to user dashboard or any other page
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Invalid email or password.";
        exit();
    }
} else {
    // If accessed directly, redirect to index.html
    header("Location: index.php");
    exit();
}
?>
