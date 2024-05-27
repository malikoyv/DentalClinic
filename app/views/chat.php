<?php
session_start();
require_once '../models/patient.php';
require_once '../models/dentist.php';
require_once '../../config/database.php';

$database = new Database();
$db = $database->getConnection();
$user = null;
$sender_id = 1;
$conversationId = 1; // Example conversation ID. Replace with actual logic to get the conversation ID

// Initialize user object based on role
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'patient') {
        $user = new Patient($db);
    } elseif ($_SESSION['role'] === 'dentist') {
        $user = new Dentist($db);
    }
} else {
    echo 'Unauthorized';
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message']) && !empty($_POST['message'])) {
    $message = htmlspecialchars(strip_tags($_POST['message']));
    if ($user) {
        $user->sendMessage($conversationId, $message);
    }
}

// Get messages
$messages = [];
if ($user) {
    $messages = $user->getMessages($conversationId);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .chat-box {
            max-height: 500px;
            overflow-y: scroll;
        }
        .message {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .message.patient {
            text-align: right;
            background-color: #e1ffe1;
        }
        .message.doctor {
            text-align: left;
            background-color: #e1e1ff;
        }
    </style>
</head>
<body>
    <!-- Navigation bar -->
    <?php include 'shared_navbar.php'; ?>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <h3>Chat</h3>
                <div class="chat-box p-3 mb-3 border">
                    <?php foreach ($messages as $message): ?>
                        <div class="message <?= htmlspecialchars($message['sender_role']) ?>">
                            <strong><?= htmlspecialchars($message['sender_role']) ?>:</strong>
                            <?= htmlspecialchars($message['message_text']) ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                <form method="post">
                    <div class="input-group mb-3">
                        <input type="text" name="message" class="form-control" placeholder="Type your message...">
                        <button class="btn btn-primary" type="submit">Send</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript to auto-refresh the chat -->
    <script>
        setInterval(function() {
            fetch(`get_messages.php?conversation_id=<?= $conversationId ?>`)
                .then(response => response.json())
                .then(data => {
                    const chatBox = document.querySelector('.chat-box');
                    chatBox.innerHTML = '';
                    data.messages.forEach(message => {
                        const messageDiv = document.createElement('div');
                        messageDiv.classList.add('message', message.sender_role);
                        messageDiv.innerHTML = `<strong>${message.sender_role}:</strong> ${message.message_text}`;
                        chatBox.appendChild(messageDiv);
                    });
                });
        }, 3000); // Refresh every 3 seconds
    </script>
</body>
</html>
