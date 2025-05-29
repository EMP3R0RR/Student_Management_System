<?php

require_once '../Model/db.php';

class StudentGradesModel {
    private $conn;

    public function __construct() {
        $this->conn = getConnection();
    }

    public function getGradesByStudentId($student_id) {
        $query = "SELECT course_name, marks, gpa FROM grades WHERE student_id = ?";
        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            return ['error' => 'Failed to prepare statement'];
        }

        $stmt->bind_param("i", $student_id);
        $stmt->execute();

        $result = $stmt->get_result();
        $grades = [];

        while ($row = $result->fetch_assoc()) {
            $grades[] = $row;
        }

        $stmt->close();
        return $grades;
    }
}

?>

