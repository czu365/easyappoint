<?php
file_put_contents('debug_post.txt', print_r($_POST, true));
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

$conn = new mysqli('localhost', 'easyappoint_user', 'Zamek123@', 'calendar_app');
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => 'Błąd połączenia z bazą']);
    exit;
}

$title = $_POST['title'] ?? '';
$client_name = $_POST['client_name'] ?? '';
$client_email = $_POST['client_email'] ?? '';
$description = $_POST['description'] ?? '';
$start = $_POST['start'] ?? '';
$end = $_POST['end'] ?? '';
$user_id = 1; // lub inny domyślny ID właściciela kalendarza

if (empty($start) || empty($end)) {
    echo json_encode(['success' => false, 'error' => 'Brak daty/godziny']);
    exit;
}

$stmt = $conn->prepare("INSERT INTO events (title, client_name, client_email, description, start, end, user_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
if (!$stmt) {
    echo json_encode(['success' => false, 'error' => 'Błąd prepare: ' . $conn->error]);
    exit;
}
$stmt->bind_param("ssssssi", $title, $client_name, $client_email, $description, $start, $end, $user_id);
if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => $stmt->error]);
}
$stmt->close();
$conn->close();