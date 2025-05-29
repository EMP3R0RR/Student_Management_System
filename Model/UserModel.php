<?php

require_once('db.php');

function getUserByEmailAndRole($email, $role) {
    $con = getConnection();
    
    $query = "SELECT id, role, email, password FROM users WHERE email = ? AND role = ?";
    $stmt = mysqli_prepare($con, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ss", $email, $role);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
        return $user;
    }



    return false;
}

function getAdminByEmailAndRole ($email,$role)
{
  $con=getConnection();

    $query = "SELECT id, role, email, password FROM admins WHERE email = ? AND role = ?";
    $stmt = mysqli_prepare($con, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ss", $email, $role);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
        return $user;
    }



    return false;

}


?>
