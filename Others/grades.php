<?php
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


$valid_roles = ['student', 'teacher', 'admin'];
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], $valid_roles)) {
    session_unset();
    session_destroy();
    header("Location: LoginView.php?error=Invalid role");
    exit();
}


if ($_SESSION['role'] !== 'student') {
    $profile_pages = [
        'student' => 'StudentProfileView.php',
        'teacher' => 'teacherprofileView.php',
        'admin' => 'AdminProfileView.php'
    ];
    header("Location: " . $profile_pages[$_SESSION['role']] . "?error=Unauthorized access");
    exit();
}


if (!isset($_SESSION['visited_profile']) || $_SESSION['visited_profile'] !== true) {
    header("Location: StudentProfileView.php?error=Visit profile first");
    exit();
}

$error_message = '';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "school_db";
$port = 3307;

$conn = mysqli_connect($servername, $username, $password, $dbname, $port);
if (!$conn) {
    die("Cannot connect to database: " . mysqli_connect_error());
}
mysqli_set_charset($conn, "utf8mb4");


$student_id = $_SESSION['user_id']; 
$query = "SELECT course_name, marks, gpa FROM grades WHERE student_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();

$grades = [];
while ($row = $result->fetch_assoc()) {
    $grades[] = $row;
}
$stmt->close();
$conn->close();

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Grades</title>
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Segoe UI', sans-serif;
      height: 100vh;
      display: flex;
      flex-direction: column;
      background: linear-gradient(to right, #e0ecff, #f3f9ff);
    }

    header {
      width: 100%;
      height: 85px;
      background-color: #004080;
      color: white;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0 2rem;
      position: sticky;
      top: 0;
      z-index: 10;
    }

    .header-left {
      display: flex;
      align-items: center;
    }

    header h1 {
      font-size: 1.5rem;
      color: white;
    }

    .header-right {
      display: flex;
      gap: 1rem;
    }

    .header-right button {
      padding: 0.5rem 1rem;
      border: none;
      border-radius: 6px;
      background-color: #ffffff;
      color: #004080;
      cursor: pointer;
      font-weight: bold;
      transition: background-color 0.3s;
    }

    .header-right button:hover {
      background-color: #cce0ff;
    }

    .dashboard {
      display: flex;
      height: calc(100% - 85px);
    }

    .sidediv {
      width: 250px;
      background-color: #003366;
      color: white;
      padding: 2rem 1rem;
      display: flex;
      flex-direction: column;
      gap: 1.5rem;
      box-shadow: 2px 0 5px rgba(0, 0, 0, 0.2);
    }

    .sidediv button {
      width: 100%;
      padding: 0.8rem 1rem;
      border: none;
      border-radius: 8px;
      background-color: #004080;
      color: white;
      font-size: 1.1rem;
      cursor: pointer;
      text-align: left;
      transition: background-color 0.3s, transform 0.2s;
    }

    .sidediv button:hover {
      background-color: #0059b3;
      transform: translateX(10px);
    }

    .maindiv {
      flex: 1;
      padding: 2rem;
      background-color: #ffffff;
      overflow-y: auto;
    }

    .form-container {
      background-color: #ffffff;
      padding: 2rem;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    .form-container h2 {
      margin-bottom: 1rem;
      color: #003366;
    }

    table.rowsandcoms {
      width: 100%;
      border-collapse: collapse;
      margin-top: 1rem;
      background-color: #fff;
      border-radius: 6px;
      overflow: hidden;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    table.rowsandcoms th,
    table.rowsandcoms td {
      padding: 15px 20px;
      text-align: center;
      border-bottom: 1px solid #ddd;
      font-size: 16px;
      color: #333;
    }

    table.rowsandcoms thead {
      background-color: #f0f0f0;
      font-weight: bold;
    }

    .total-container {
      margin-top: 2rem;
      padding: 1rem;
      background-color: #f9f9f9;
      border: 1px solid #ddd;
      border-radius: 8px;
      font-size: 1.1rem;
      color: #004080;
    }
  </style>
</head>
<body>

  <!-- Header -->
  <header>
    <div class="header-left">
      <h1>Welcome to the Dashboard</h1>
    </div>
    <div class="header-right">
      <form action="" method="POST">
        <button type="submit">🔔 Notifications</button>
        </form>
        <form action="logout.php" method="POST">
        <button type="submit">Logout</button>
        </form>
    </div>
  </header>

 
  <div class="dashboard">
   
    <div class="sidediv">
      <button onclick="window.location.href='studentprofile.php'">Profile</button>
      <button onclick="window.location.href='grades.php'">Grades</button>
      <button onclick="window.location.href='registration.php'">Registration</button>
      <button onclick="window.location.href='payment.php'">Payment</button>
    </div>

   
    <div class="maindiv">
      <main class="form-container">
        <h2>Subject-wise Grades</h2>
        <table class="rowsandcoms" id="gradesTable">
          <thead>
            <tr>
              <th>Subject</th>
              <th>Marks</th>
              <th>GPA</th>
            </tr>
          </thead>
          <tbody>
            <?php if (count($grades) > 0): ?>
              <?php foreach ($grades as $grade): ?>
            <tr>
              <td><></td>
              <td><></td>
              <td><></td>
            </tr>
            <?php endforeach; ?>
            <?php else: ?>
              <tr><td colspan="6">No grades found.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>

        
        <div class="total-container" id="totalContainer">
          Total Average Marks: <span id="avgMarks">0</span><br>
          Total Average GPA: <span id="avgGPA">0</span>
        </div>
      </main>
    </div>
  </div>

  <
  <script>
    const rows = document.querySelectorAll("#gradesTable tbody tr");
    let totalMarks = 0, totalGPA = 0;

    rows.forEach(row => {
      const marks = parseFloat(row.cells[1].innerText);
      const gpa = parseFloat(row.cells[2].innerText);
      if (!isNaN(marks)) totalMarks += marks;
      if (!isNaN(gpa)) totalGPA += gpa;
    });

    const rowCount = rows.length;
    document.getElementById("avgMarks").innerText = rowCount ? (totalMarks / rowCount).toFixed(2) : "0";
    document.getElementById("avgGPA").innerText = rowCount ? (totalGPA / rowCount).toFixed(2) : "0";
  
  </script>

</body>
</html>
