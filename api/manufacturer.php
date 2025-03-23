<?php

header('Content-Type: application/json');
session_start();

if(!isset($_SESSION['user_id'])) {

    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);

    exit();

}

require_once '../models/Manufacturer.php';

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = isset($_POST['name']) ? trim($_POST['name']) : '';

    if (empty($name)) {

        $response['success'] = false;
        $response['message'] = 'Manufacturer name is required.';

    } else {

        $manufacturer = new Manufacturer();
        $manufacturer->name = $name;

        if ($manufacturer->create()) {

            $response['success'] = true;
            $response['message'] = 'Manufacturer added successfully.';

        } else {

            $response['success'] = false;
            $response['message'] = 'A manufacturer with this name already exists.';

        }
    }

} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $manufacturer = new Manufacturer();
    $manufacturers = $manufacturer->read();
    
    $response['success'] = true;
    $response['data'] = $manufacturers;

} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {

    // Get the raw POST data
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id'] ?? null;

    if ($id === null) {

        $response['success'] = false;
        $response['message'] = 'Manufacturer ID is required.';

    } else {

        $manufacturer = new Manufacturer();
        $manufacturer->id = $id;

        if ($manufacturer->delete()) {

            $response['success'] = true;
            $response['message'] = 'Manufacturer deleted successfully.';

        } else {

            $response['success'] = false;
            $response['message'] = 'Failed to delete manufacturer.';
        }
    }

} else {

    $response['success'] = false;
    $response['message'] = 'Invalid request method.';

}

echo json_encode($response); 