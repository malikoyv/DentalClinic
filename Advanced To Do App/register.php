<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate input data
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];
    
    // Check if passwords match
    if ($password !== $confirm_password) {
        echo "Passwords do not match.";
        exit();
    }

    // Additional validation, e.g., check if email format is valid

    // Process registration (insert into database, etc.)
    // This is a placeholder, you would typically have database operations here

    // Redirect user after successful registration
    header("Location: index.html");
    exit();
} else {
    // If accessed directly, redirect to index.html
    header("Location: index.html");
    exit();
}
?>
