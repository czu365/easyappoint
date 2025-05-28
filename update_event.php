<?php
require_once 'includes/auth.php';
require_once 'includes/functions.php';

header('Content-Type: application/json');

$auth = new Auth();
if (!$auth->isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Nieautoryzowany dostęp']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (empty($data['id']) || empty($data['title']) || empty($data['start'])) {
    echo json_encode(['success' => false, 'error' => 'ID, tytuł i data rozpoczęcia są wymagane']);
    exit;
}

$success = updateEvent(
    $data['id'],
    $auth->getCurrentUserId(),
    $data['title'],
    $data['start'],
    $data['end'] ?? null,
    $data['description'] ?? null
);

echo json_encode(['success' => $success]);