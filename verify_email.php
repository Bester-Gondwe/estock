<?php
session_start();
require_once 'models/User.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = new User();

    // Check if the email exists
    if (isset($_POST['email'])) {
        $email = htmlentities($_POST['email']); // Sanitize email input
        if ($user->emailExists($email)) {
            // Return a message if email exists
            echo json_encode(['error' => 'Email already exists']);
        } else {
            // Return a success message if email does not exist
            echo json_encode(['success' => 'Email is available']);
        }
    }
}
