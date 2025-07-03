<?php
session_start();
require_once('../Model/UserModel.php');

function validateCredentials () {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $role = isset($_POST['role']) ? trim($_POST['role']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    $valid_roles = ['student', 'teacher', 'admin'];

    if (empty($role) || !in_array($role, $valid_roles)) {
        echo "Please select a valid role";
        return false;

    } elseif (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Please enter a valid email";
        return false;

    } elseif (empty($password)) {
        echo "Please enter a password";
          return false;

    } else{

        return true;     
    }      
}

}

function userLogin(){

    $role = trim($_POST['role']) ;
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $user = [
        'email' => $email,
        'password' => $password
    ];

    $status= loginUser($user);
    
    if ($status)
    {

    }

}


?>
