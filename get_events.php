<?php
require_once 'includes/database.php';
header('Content-Type: application/json');

$db = new Database();
$conn = $db->getConnection();

$result = $conn->query("SELECT id, title, start, end, description FROM events");
$events = [];
while ($row = $result->fetch_assoc()) {
    $events[] = [
        'id' => $row['id'],
        'title' => $row['title'],
        'start' => $row['start'],
        'end' => $row['end'],
        'description' => $row['description']
    ];
}
echo json_encode($events);