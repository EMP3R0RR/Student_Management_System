<?php


require_once '../Model/db.php'; 

class StudentProfileModel {
    private $conn;

    public function __construct() {
        $this->conn = getConnection(); 
    }

    public function getStudentProfile($userId) {
        $stmt = $this->conn->prepare("SELECT first_name, last_name, email, department, age, blood_group, address FROM students WHERE id = ?");
        $stmt->bind_param("i", $userId);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            return $result->fetch_assoc(); 
        }

        return null;
    }

    public function updateProfilePicture($user_id, $targetPath) {
        $stmt = $this->conn->prepare("UPDATE students SET profilepic = ? WHERE id = ?");
        $stmt->bind_param("si", $targetPath, $user_id);
        $propic = $stmt->execute();
        $stmt->close();

        return $propic;
            
    }
}
?>
