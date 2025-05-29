<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

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
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => $stmt->error]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Missing fields']);
} // Do sprawdzenia POST 