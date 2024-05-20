<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['username'])) {
    header("Location: pages/login.php");
    exit();
}

$username = $_SESSION['username'];

$sql = "SELECT tasks.id, tasks.task, tasks.done FROM tasks JOIN users ON tasks.user_id = users.id WHERE users.username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

$tasks = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo List</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <header id="main-header">
        <h1 class="header-title">Todo List</h1>
    </header>
    <main>
        <section class="task-section">
            <ul class="task-list">
                <?php foreach ($tasks as $task): ?>
                    <li class="task-item <?php echo $task['done'] ? 'done' : 'pending'; ?>">
                        <?php echo htmlspecialchars($task['task']); ?>
                        <a href="pages/delete_task.php?id=<?php echo $task['id']; ?>">Delete</a>
                        <a href="pages/change_status.php?id=<?php echo $task['id']; ?>&status=<?php echo $task['done'] ? 0 : 1; ?>">
                            <?php echo $task['done'] ? 'Mark as Pending' : 'Mark as Done'; ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>
        <a class="logout-btn" href="pages/logout.php">Logout</a>
        <form action="pages/add_task.php" method="POST">
            <input type="text" name="task" required>
            <button type="submit">Add Task</button>
        </form>
    </main>
    <footer>
        <p>&copy; 2024 Todo List Application</p>
    </footer>
</body>
</html>