<?php

require_once '../Model/db.php';

class StudentRegistrationModel {
    private $conn;

    public function __construct() {
        $this->conn = getConnection();
    }

   public function getAllCourses() {
    $query = "SELECT course_id, course_name, price, schedule FROM courses";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result(); 
    return $result->fetch_all(MYSQLI_ASSOC); 
}

    public function registerCourses($student_id, $semester, $selectedCourses, $total_cost) {
        $courseNames = [];
        foreach ($selectedCourses as $course_id) {
           
           $query = "SELECT course_name FROM courses WHERE course_id = ?";
           $stmt = $this->conn->prepare($query);
           $stmt->bind_param("i", $course_id); 
           $stmt->execute();
           $result = $stmt->get_result();
           $course = $result->fetch_assoc();
           if ($course) {
           $courseNames[] = $course['course_name'];
}


            
            $insertQuery = "INSERT INTO enrolment (student_id, course_id, semester, selected_courses, total_cost, course_name)
                            VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($insertQuery);
            $stmt->execute([
                $student_id,
                $course_id,
                $semester,
                count($selectedCourses),
                $total_cost,
                implode(', ', $courseNames)
            ]);
        }

        return true;
    }
}
?>
