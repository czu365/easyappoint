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

if (empty($data['id'])) {
    echo json_encode(['success' => false, 'error' => 'ID wydarzenia jest wymagane']);
    exit;
}

$success = deleteEvent($data['id'], $auth->getCurrentUserId());

echo json_encode(['success' => $success]);