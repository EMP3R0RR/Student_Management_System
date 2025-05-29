<?php
require_once '../Model/StudentProfileModel.php';
session_start();

if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
    session_unset();
    session_destroy();
    header("Location: LoginView.php?error=Session expired");
    exit();
}

$_SESSION['last_activity'] = time();

if (!isset($_SESSION['user_id'])) {
    header("Location: LoginView.php?error=Please log in");
    exit();
}

if ($_SESSION['role'] !== 'student') {
    $profile_pages = [
        'student' => 'StudentProfile.php',
        'teacher' => 'teacherprofile.php',
        'admin'   => 'AdminProfile.php'
    ];
    if (array_key_exists($_SESSION['role'], $profile_pages)) {
        header("Location: " . $profile_pages[$_SESSION['role']] . "?error=Unauthorized access");
    } else {
        header("Location: LoginView.php?error=Invalid role");
    }
    exit();
}

$_SESSION['visited_profile'] = true;

$model = new StudentProfileModel();

if (isset($_POST['json']) && $_POST['json'] === 'true') {
    $profileData = $model->getStudentProfile($_SESSION['user_id']);
    echo json_encode($profileData);
    exit();
}

// if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_pic'])) {
//     $user_id = $_SESSION['user_id'];
//     $src = $_FILES['profile_pic']['tmp_name'];
//     $originalName = basename($_FILES['profile_pic']['name']);
//     $uploadDir = "../upload/";
//     $uniqueName = $user_id . "_" . time() . "_" . $originalName;
//     $targetPath = $uploadDir . $uniqueName;

//     $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
//     if (!in_array($_FILES['profile_pic']['type'], $allowedTypes)) {
//         echo json_encode(['success' => false, 'error' => 'Invalid file type']);
//         exit();
//     }

//     if (move_uploaded_file($src, $targetPath)) {
//         $result = $model->updateProfilePicture($user_id, $targetPath);
//         echo json_encode(['success' => $result]);
//     } else {
//         echo json_encode(['success' => false, 'error' => 'Failed to upload file']);
//     }
// } else {
//     echo json_encode(['success' => false, 'error' => 'Invalid request']);
// }


?>
