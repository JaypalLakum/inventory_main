<?php
header('Content-Type: application/json');

require_once '../models/User.php';

User::logout();

echo json_encode([
    'success' => true,
    'message' => 'Logged out successfully'
]); 
