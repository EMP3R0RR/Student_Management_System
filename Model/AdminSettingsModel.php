<?php
require_once '../Model/db.php';

function insertAdmin( $email,$password) {
    $con = getConnection();

    $query = "INSERT INTO admins (email,password)
              VALUES (?, ?)";

    $stmt = mysqli_prepare($con, $query);

    if (!$stmt) {
        return mysqli_error($con);
    }
   $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

   
    mysqli_stmt_bind_param($stmt, "ss", 
         $email, 
         $hashedPassword
    );

    if (mysqli_stmt_execute($stmt)) {
        return true;
    } else {
        return mysqli_stmt_error($stmt);
    }
}
?>
