<?php
require_once __DIR__ . '/config/bootstrap.php';
require_once __DIR__ . '/models/User.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$email = trim($_POST['email'] ?? '');
if ($email === '') {
    echo json_encode(['error' => 'Email is required']);
    exit;
}

$user = new User();
if ($user->emailExists($email)) {
    echo json_encode(['error' => 'Email already exists']);
} else {
    echo json_encode(['success' => 'Email is available']);
}
