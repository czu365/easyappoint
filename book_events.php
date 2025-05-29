<?php
require_once 'includes/database.php';

$db = new Database();
$conn = $db->getConnection();

$title = $_POST['title'] ?? '';
$start = $_POST['start'] ?? '';
$end = $_POST['end'] ?? '';
$client_name = $_POST['client_name'] ?? '';
$client_email = $_POST['client_email'] ?? '';

if ($title && $start && $client_name && $client_email) {
    $stmt = $conn->prepare("INSERT INTO events (title, start, end, client_name, client_email, status, created_at) VALUES (?, ?, ?, ?, ?, 'pending', NOW())");
    $stmt->bind_param("sssss", $title, $start, $end, $client_name, $client_email);
    $stmt->execute();
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}