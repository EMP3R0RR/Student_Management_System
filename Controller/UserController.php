<?php
session_start();
require_once('../Model/UserModel.php');

$error_message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $role = isset($_POST['role']) ? trim($_POST['role']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    $valid_roles = ['student', 'teacher', 'admin'];

    if (empty($role) || !in_array($role, $valid_roles)) {
        $_SESSION['error'] = "Please select a valid role";
           header("Location: ../View/LoginView.php");
            exit();
    } elseif (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
       $_SESSION['error'] = "Please enter a valid email";
             header("Location: ../View/LoginView.php");
             exit();
    } elseif (empty($password)) {
        $_SESSION['error'] = "Please enter a password";
             header("Location: ../View/LoginView.php");
             exit();
    } else {
        $user = getUserByEmailAndRole($email, $role);

        if ($user && password_verify($password, $user['password'])) {

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['email'] = $user['email'];

            session_regenerate_id(true);

            $role_pages = [
                'student' => '../View/StudentProfileView.php',
                'teacher' => '../View/TeacherProfileView.php',
                'admin'   => '../View/AdminDashboardView.php'
            ];

            header("Location: " . $role_pages[$role]);
            exit();
        } else {
            $_SESSION['error'] = "Invalid email, password, or role";
             header("Location: ../View/LoginView.php");
             exit();
            
        }
        
    }
        
      
}

?>
