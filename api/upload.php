<?php
header('Content-Type: application/json');
session_start();

if(!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$response = [];
$upload_dir = '../uploads/';

// Create uploads directory if it doesn't exist
if (!file_exists($upload_dir)) {

    mkdir($upload_dir, 0777, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_FILES['file'])) {

        $file = $_FILES['file'];
        $filename = uniqid() . '_' . basename($file['name']);
        $target_path = "{$upload_dir}{$filename}";
        
        // Check if it's an image
        $check = getimagesize($file['tmp_name']);

        if($check === false) {

            $response['success'] = false;
            $response['message'] = 'File is not an image.';

        } else {

            if (move_uploaded_file($file['tmp_name'], $target_path)) {

                $response['success'] = true;
                $response['filename'] = $filename;

            } else {

                $response['success'] = false;
                $response['message'] = 'Failed to upload file.';

            }
        }

    } else {

        $response['success'] = false;
        $response['message'] = 'No file uploaded.';
    }

} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {

    parse_str(file_get_contents("php://input"), $delete_vars);
    
    if (isset($delete_vars['filename'])) {

        $file_path = $upload_dir . $delete_vars['filename'];

        if (file_exists($file_path)) {

            if (unlink($file_path)) {
                
                $response['success'] = true;
                $response['message'] = 'File deleted successfully.';

            } else {

                $response['success'] = false;
                $response['message'] = 'Failed to delete file.';

            }

        } else {

            $response['success'] = false;
            $response['message'] = 'File not found.';

        }

    } else {

        $response['success'] = false;
        $response['message'] = 'Filename not provided.';

    }

} else {

    $response['success'] = false;
    $response['message'] = 'Invalid request method.';

}

echo json_encode($response); 