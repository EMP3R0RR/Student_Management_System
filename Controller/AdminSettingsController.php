<?php
require_once('../Model/AdminModel.php');

try {
   
    $jsonData = file_get_contents('php://input');
    $data = json_decode($jsonData, true);

    
    if (!$data ||  $data['email'], $data['password']) {
        echo json_encode([
            'success' => false,
            'message' => 'Missing required admin data'
        ]);
        exit;
    }

    // Call the model insert function
    $result = insertAdmin(
        $data['email'],
        $data['password'],
       );

    // Return response based on result
    if ($result === true) {
        echo json_encode([
            'success' => true,
            'message' => 'Admin inserted successfully'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Insertion failed: ' . $result
        ]);
    }

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Server error: ' . $e->getMessage()
    ]);
}
?>
