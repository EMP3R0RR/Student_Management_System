<?php
session_start();
require_once '../Model/StudentGradesModel.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    echo json_encode(['error' => 'Unauthorized access']);
    exit();
}

if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
    session_unset();
    session_destroy();
    header("Location: LoginView.php?error=Session expired");
    exit();
}


$_SESSION['last_activity'] = time();

$studentId = $_SESSION['user_id'];

$model = new StudentGradesModel();
$grades = $model->getGradesByStudentId($studentId);
if (isset($_GET['json']) && $_GET['json'] === 'true') {
    header('Content-Type: application/json');
    echo json_encode($grades);
    exit();
}
?>
