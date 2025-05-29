<?php
require_once('../Model/db.php');

class UserSignupModel {
    private $conn;

    public function __construct() {
        $this->conn = getConnection();
    }

    public function isEmailRegistered($email) {
        $query = "SELECT id FROM users WHERE email = ?";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $exists = mysqli_num_rows($result) > 0;
        mysqli_stmt_close($stmt);
        return $exists;
    }

    public function registerUser($userData) {
        $hashed_password = password_hash($userData['password'], PASSWORD_DEFAULT);
        $role = 'student';

        mysqli_begin_transaction($this->conn);
        try {
            $stmt = mysqli_prepare($this->conn, "INSERT INTO users (role, email, password) VALUES (?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "sss", $role, $userData['email'], $hashed_password);
            mysqli_stmt_execute($stmt);
            $user_id = mysqli_insert_id($this->conn);
            mysqli_stmt_close($stmt);

            $stmt = mysqli_prepare($this->conn, "INSERT INTO students (id, first_name, last_name, email, age, blood_group, department, address) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "isssisss", $user_id, $userData['fname'], $userData['lname'], $userData['email'], $userData['age'], $userData['bloodGroup'], $userData['department'], $userData['address']);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            mysqli_commit($this->conn);
            return true;
        } catch (Exception $e) {
            mysqli_rollback($this->conn);
            return false;
        }
    }
}
?>
