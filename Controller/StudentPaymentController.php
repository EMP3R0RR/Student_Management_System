<?php
session_start();
require_once '../Model/StudentPaymentModel.php';

// Session timeout check
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
    session_unset();
    session_destroy();
    http_response_code(403);
    echo "Session expired";
    exit();
}
$_SESSION['last_activity'] = time();

// User login check
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo "Unauthorized access";
    exit();
}

// JSON input check
if (!isset($_POST['json'])) {
    http_response_code(400);
    echo "Invalid request";
    exit();
}

$data = json_decode($_POST['json'], true);

if (
    !isset($data['bank']) ||
    !isset($data['total_cost']) ||
    !isset($data['paid']) ||
    !isset($data['due'])
) {
    http_response_code(422);
    echo "Missing fields";
    exit(); 
}

$student_id = (int) $_SESSION['user_id'];
$bank = htmlspecialchars($data['bank']);
$total_cost = (float) $data['total_cost'];
$paid = (float) $data['paid'];
$due = (float) $data['due'];

$result = insertPayment($student_id, $bank, $total_cost, $paid, $due);

if ($result === true) {
    echo "success";
} else {
    http_response_code(500);
    echo $result;
}
?>
