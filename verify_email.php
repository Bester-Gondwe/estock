<?php
session_start();
require_once 'models/User.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = new User();

    if (isset($_POST['email'])) {
        if ($user->emailExists(htmlentities($_POST['email']))) {
            echo 'email Already exists';
        }
    }
}
