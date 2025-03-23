<?php

header('Content-Type: application/json');
session_start();

//if(!isset($_SESSION['user_id'])) {
//    http_response_code(401);
//    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
//    exit();
//}

require_once '../models/Model.php';

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $required_fields = ['manufacturer_id', 'name', 'color', 'manufacturing_year', 'registration_number'];
    $missing_fields = [];

    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $missing_fields[] = $field;
        }
    }

    if (!empty($missing_fields)) {
        $response['success'] = false;
        $response['message'] = 'Required fields missing: ' . implode(', ', $missing_fields);
    } else {
        try {
            $model = new Model();
            
            $model->manufacturer_id = $_POST['manufacturer_id'];
            $model->name = $_POST['name'];
            $model->color = $_POST['color'];
            $model->manufacturing_year = $_POST['manufacturing_year'];
            $model->registration_number = $_POST['registration_number'];
            $model->note = $_POST['note'] ?? '';
     
            // Handle images
            if (isset($_POST['images']) && is_array($_POST['images'])) {
                if (count($_POST['images']) > 0) {
                    $model->image1 = $_POST['images'][0];
                }
                if (count($_POST['images']) > 1) {
                    $model->image2 = $_POST['images'][1];
                }
            } else {
                $model->image1 = null;
                $model->image2 = null;
            }

            $result = $model->create();
            if ($result === true) {
                $response['success'] = true;
                $response['message'] = 'Model added successfully.';
            } else {
                $response['success'] = false;
                $response['message'] = is_string($result) ? $result : 'Failed to add model.';
            }
        } catch (Exception $e) {
            $response['success'] = false;
            $response['message'] = 'Error creating model.';
        }
    }

} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {

        $model = new Model();

        $models = $model->read();
        
        $response['success'] = true;
        $response['models'] = $models;
    
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {

    parse_str(file_get_contents("php://input"), $put_vars);
    
    if (isset($put_vars['id']) && isset($put_vars['is_sold'])) {

        $model = new Model();
        $model->id = $put_vars['id'];
        $model->is_sold = $put_vars['is_sold'];
        
        if ($model->updateSoldStatus()) {

            $response['success'] = true;
            $response['message'] = 'Model status updated successfully.';

        } else {

            $response['success'] = false;
            $response['message'] = 'Failed to update model status.';

        }
    } else {

        $response['success'] = false;
        $response['message'] = 'Missing required parameters.';

    }

} else {

    $response['success'] = false;
    $response['message'] = 'Invalid request method.';
}

echo json_encode($response); 