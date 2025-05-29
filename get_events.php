<?php
require_once 'includes/database.php'; // Upewnij się, że masz plik do połączenia z bazą

header('Content-Type: application/json');

$user_id = $_SESSION['user_id'] ?? 1; // lub inny sposób pobrania ID użytkownika

$sql = "SELECT id, title, start, end FROM events WHERE user_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($events);