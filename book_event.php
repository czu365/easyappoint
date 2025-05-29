<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

// Połącz z bazą (dostosuj dane logowania)
$conn = new mysqli('localhost', 'easyappoint_user', 'Zamek123@', 'calendar_app');
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => 'Błąd połączenia z bazą']);
    exit;
}

// Pobierz dane z formularza
$title = $_POST['title'] ?? '';
$client_name = $_POST['client_name'] ?? '';
$client_email = $_POST['client_email'] ?? '';
$description = $_POST['description'] ?? '';
$start = $_POST['start'] ?? '';
$end = $_POST['end'] ?? '';

// Zapisz do bazy
$stmt = $conn->prepare("INSERT INTO events (title, client_name, client_email, description, start, end) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssss", $title, $client_name, $client_email, $description, $start, $end);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Błąd zapisu']);
}
$stmt->close();
$conn->close();