<?php
require_once '../Model/db.php';

function insertPayment($student_id, $bank, $total_cost, $paid, $due) {
    $con = getConnection();

    $query = "INSERT INTO payment (student_id, bank, total_cost, paid, due) 
              VALUES (?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($con, $query);

    if (!$stmt) {
        return mysqli_error($con);
    }

    
    mysqli_stmt_bind_param($stmt, "isddd", $student_id, $bank, $total_cost, $paid, $due);

    if (mysqli_stmt_execute($stmt)) {
        return true;
    } else {
        return mysqli_stmt_error($stmt);
    }
}
?>
