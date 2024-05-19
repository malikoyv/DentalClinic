<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advanced To Do App</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Advanced To Do App</h1>
    </header>
    <main>
        <section id="registration">
        <h2>Register</h2>
        <form action="register.php" method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            <p>Or register using social media:</p>
            <div class="social-buttons">
                <button type="button" id="facebook-register">Register with Facebook</button>
                <button type="button" id="google-register">Register with Google</button>
            </div>
            <button type="submit">Register</button>
        </form>
        </section>
        
        <section id="login">
        <h2>Login</h2>
        <form action="login.php" method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        </section>

        <section id="task-list">
            <!-- Task list will be dynamically populated here -->
        </section>
        <section id="task-form">
            <h2>Add New Task</h2>
            <form action="add_task.php" method="POST">
                <input type="text" name="task_name" placeholder="Task Name" required>
                <textarea name="task_description" placeholder="Task Description"></textarea>
                <select name="priority">
                    <option value="low">Low Priority</option>
                    <option value="medium">Medium Priority</option>
                    <option value="high">High Priority</option>
                </select>
                <input type="datetime-local" name="due_date">
                <button type="submit">Add Task</button>
            </form>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 Advanced To Do App</p>
    </footer>
</body>
</html>
