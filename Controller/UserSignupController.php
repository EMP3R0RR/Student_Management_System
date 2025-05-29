<?php
session_start(); 

require_once('../Model/UserSignupModel.php');

$model = new UserSignupModel();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userData = [
        'fname' => trim($_POST['fname'] ?? ''),
        'lname' => trim($_POST['lname'] ?? ''),
        'email' => trim($_POST['email'] ?? ''),
        'age' => (int)($_POST['age'] ?? 0),
        'bloodGroup' => trim($_POST['bloodGroup'] ?? ''),
        'department' => trim($_POST['department'] ?? ''),
        'address' => trim($_POST['address'] ?? ''),
        'password' => $_POST['password'] ?? '',
        'confirm' => $_POST['confirm'] ?? '',
    ];

    if (empty($userData['fname']) || !preg_match("/^[a-zA-Z ]{2,50}$/", $userData['fname'])) {
        $_SESSION['error'] = "First name must be 2-50 letters";
        
    } elseif (empty($userData['lname']) || !preg_match("/^[a-zA-Z ]{2,50}$/", $userData['lname'])) {
        $_SESSION['error'] = "Last name must be 2-50 letters";
        
    } elseif (!filter_var($userData['email'], FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email";
        
    } elseif ($userData['age'] < 15 || $userData['age'] > 100) {
        $_SESSION['error'] = "Age must be 15–100";
        
    } elseif (!in_array($userData['bloodGroup'], ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'])) {
        $_SESSION['error'] = "Invalid blood group";
        
    } elseif (!in_array($userData['department'], ['CSE', 'BBA', 'EEE', 'ENGLISH', 'DATA SCIENCE', 'LLB'])) {
        $_SESSION['error'] = "Invalid department";
        
    } elseif (strlen($userData['address']) < 5 || strlen($userData['address']) > 255) {
        $_SESSION['error'] = "Address must be 5–255 chars";
        
    } elseif (strlen($userData['password']) < 8) {
        $_SESSION['error'] = "Password must be 8+ characters";
        
    } elseif ($userData['password'] !== $userData['confirm']) {
        $_SESSION['error'] = "Passwords do not match";
        
    } elseif ($model->isEmailRegistered($userData['email'])) {
        $_SESSION['error'] = "Email already registered";
        
    } else {
        if ($model->registerUser($userData)) {
            header("Location: ../View/LoginView.php");
            exit();
        } else {
            $_SESSION['error'] = "Signup failed. Try again.";
            
        }
    }

    header("Location: ../View/SignupView.php");
    exit();
}   
else {
    include('../View/SignupView.php');
}
