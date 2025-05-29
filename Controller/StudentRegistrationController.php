<?php
session_start();


require_once '../Model/db.php';
require_once '../Model/StudentRegistrationModel.php';

$_SESSION['visited_profile'] = true;

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: LoginView.php?error=Unauthorized");
    exit();
}

$model = new StudentRegistrationModel();
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $student_id = $_SESSION['user_id'];
    $semester = $_POST['semester'];
    $selected_courses = $_POST['courses'] ?? [];
    $total_cost = $_POST['total_cost'];

    if (empty($selected_courses) || empty($semester)) {
        $error_message = "Please select at least one course and semester.";
    } else {
        $model->registerCourses($student_id, $semester, $selected_courses, $total_cost);
        $_SESSION['success'] = "Registration successful!";
        
    }

}

$courses = $model->getAllCourses();

// include '../View/StudentRegistrationView.php';

?>
