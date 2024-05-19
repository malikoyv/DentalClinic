<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $task_name = $_POST["task_name"];
    $task_description = $_POST["task_description"];
    $priority = $_POST["priority"];
    $due_date = $_POST["due_date"];

    // Save the task to the database or perform other actions
    // This is just a placeholder

    echo "Task added successfully!";
} else {
    header("Location: index.html");
    exit();
}
?>
