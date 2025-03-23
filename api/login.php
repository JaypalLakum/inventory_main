<?php
header('Content-Type: application/json');
require_once '../models/User.php';

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $response['success'] = false;
        $response['message'] = 'Please provide both username and password.';
    } else {
        $user = new User();
        if ($user->login($username, $password)) {
            $response['success'] = true;
            $response['message'] = 'Login successful.';
        } else {
            $response['success'] = false;
            $response['message'] = 'Invalid username or password.';
        }
    }
} else {
    $response['success'] = false;
    $response['message'] = 'Invalid request method.';
}

echo json_encode($response); 