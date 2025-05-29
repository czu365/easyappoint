<?php
header('Content-Type: application/json');
error_reporting(0); // lub E_ALL jeśli chcesz logować błędy
ini_set('display_errors', 0);

require_once 'includes/database.php';

$db = new Database();
$conn = $db->getConnection();

$title = $_POST['title'] ?? '';
$start = $_POST['start'] ?? '';
$end = $_POST['end'] ?? '';
$client_name = $_POST['client_name'] ?? '';
$client_email = $_POST['client_email'] ?? '';
$description = $_POST['description'] ?? '';

if ($title && $start && $client_name && $client_email) {
    $stmt = $conn->prepare("INSERT INTO events (title, start, end, client_name, client_email, description, status, created_at) VALUES (?, ?, ?, ?, ?, ?, 'pending', NOW())");
    if ($stmt) {
        $stmt->bind_param("ssssss", $title, $start, $end, $client_name, $client_email, $description);
        $stmt->execute();
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Prepare failed']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Missing fields']);
}