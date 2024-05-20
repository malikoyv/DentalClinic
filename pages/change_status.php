<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];
$status = $_GET['status'];

$sql = "UPDATE tasks SET done = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $status, $id);
$stmt->execute();

header("Location: ../index.php");
?>
