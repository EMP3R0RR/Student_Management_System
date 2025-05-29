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


if ($_SESSION['role'] !== 'teacher') {
    $profile_pages = [
        'student' => 'StudentProfileView.php',
        'teacher' => 'TeacherProfileView.php',
        'admin' => 'AdminProfileView.php'
    ];
    header("Location: " . $profile_pages[$_SESSION['role']] . "?error=Unauthorized access");
    exit();
}


if (!isset($_SESSION['visited_profile']) || $_SESSION['visited_profile'] !== true) {
    header("Location: teacherprofile.php?error=Visit profile first");
    exit();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Upload Grades</title>
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #e0ecff, #f3f9ff);
      height: 100vh;
      display: flex;
      flex-direction: column;
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

    header h1 {
      font-size: 1.5rem;
      cursor: default;
    }
        .header-center {
      flex-grow: 1;
      display: flex;
      justify-content: center;
      height: 100%;
      align-items: center;
    }

    .search-bar {
      display: flex;
      gap: 0.5rem;
      height: 40px;
    }

    .search-bar input[type="text"] {
      padding: 0.5rem;
      border-radius: 4px;
      border: none;
      width: 250px;
      height: 100%;
    }

    .search-bar button {
      padding: 0 1rem;
      border: none;
      border-radius: 4px;
      background-color: #ffffff;
      color: #004080;
      cursor: pointer;
      font-weight: bold;
      transition: background-color 0.3s;
      height: 100%;
    }

    .search-bar button:hover {
      background-color: #cce0ff;
    }
    .header-left {
      display: flex;
      align-items: center;
      height: 100%;
    }
   
    .header-right {
      height: 100%;
      display: flex;
      align-items: center;
    }

    .header-right button {
      margin-left: 1rem;
      padding: 0.5rem 1rem;
      border: none;
      border-radius: 6px;
      background-color: white;
      color: #004080;
      font-weight: bold;
      cursor: pointer;
    }

    .header-right button:hover {
      background-color: #cce0ff;
    }

    .dashboard {
      flex: 1;
      display: flex;
    }

    .sidediv {
      width: 250px;
      background-color: #003366;
      color: white;
      padding: 2rem 1rem;
      display: flex;
      flex-direction: column;
      gap: 1.5rem;
    }

    .sidediv button {
      width: 100%;
      padding: 0.8rem 1rem;
      border: none;
      border-radius: 8px;
      background-color: #004080;
      color: white;
      font-size: 1.1rem;
      text-align: left;
      cursor: pointer;
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

    .grades-container {
      background-color: #ffffff;
      padding: 2rem;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    .grades-container h2 {
      color: #003366;
      margin-bottom: 1rem;
    }

    .grades-container label {
      display: block;
      margin-top: 10px;
      margin-bottom: 4px;
      color: #003366;
    }

    .grades-container select {
      width: 100%;
      padding: 0.6rem;
      border: 1px solid #ccc;
      border-radius: 6px;
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

    table.rowsandcoms select,
    table.rowsandcoms input[type="number"] {
      width: 100%;
      padding: 0.6rem;
      border: 1px solid #ccc;
      border-radius: 6px;
    }

    .submit-button {
      margin-top: 1rem;
      width: 100%;
      background-color: #004080;
      color: white;
      padding: 0.6rem;
      border: none;
      border-radius: 6px;
      cursor: pointer;
    }

    .submit-button:hover {
      background-color: #0059b3;
    }
  </style>
</head>
<body>

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
       <button onclick="window.location.href='TeacherProfileView.php'">Profile</button>
      <button onclick="window.location.href='TeacherScheduleView.php'">Schedule</button>
      <button onclick="window.location.href='TeacherGradesUploadView.php'">Grades Upload</button>
      <button onclick="window.location.href='TeacherUpassignmentView.php'">Assignments</button>
    </div>

    <div class="maindiv">
      <div class="grades-container">
        <h2>Upload Grades</h2>
        <label for="courseSelect">Select Course</label>
        <select id="courseSelect">
          <option value="">-- Select Course --</option>
          <option value="mathematics">Mathematics</option>
          <option value="physics">Physics</option>
          <option value="english">English</option>
        </select>

        <table class="rowsandcoms">
          <thead>
            <tr>
              <th>Student Name</th>
              <th>Grade</th>
              <th>Marks</th>
            </tr>
          </thead>
          <tbody id="studentTable">
            
            <tr>
              <td>John Doe</td>
              <td>
                <select class="gradeSelect">
                  <option value="">-- Select Grade --</option>
                  <option value="A+">A+</option>
                  <option value="A">A</option>
                  <option value="A-">A-</option>
                  <option value="B+">B+</option>
                  <option value="B">B</option>
                  <option value="B-">B-</option>
                  <option value="C+">C+</option>
                  <option value="C">C</option>
                  <option value="C-">C-</option>
                  <option value="D">D</option>
                </select>
              </td>
              <td>
                <input type="number" class="marksInput" min="0" max="100" placeholder="Enter marks" />
              </td>
            </tr>
            <tr>
              <td>Jane Smith</td>
              <td>
                <select class="gradeSelect">
                  <option value="">-- Select Grade --</option>
                  <option value="A+">A+</option>
                  <option value="A">A</option>
                  <option value="A-">A-</option>
                  <option value="B+">B+</option>
                  <option value="B">B</option>
                  <option value="B-">B-</option>
                  <option value="C+">C+</option>
                  <option value="C">C</option>
                  <option value="C-">C-</option>
                  <option value="D">D</option>
                </select>
              </td>
              <td>
                <input type="number" class="marksInput" min="0" max="100" placeholder="Enter marks" />
              </td>
            </tr>
            <tr>
              <td>Michael Brown</td>
              <td>
                <select class="gradeSelect">
                  <option value="">-- Select Grade --</option>
                  <option value="A+">A+</option>
                  <option value="A">A</option>
                  <option value="A-">A-</option>
                  <option value="B+">B+</option>
                  <option value="B">B</option>
                  <option value="B-">B-</option>
                  <option value="C+">C+</option>
                  <option value="C">C</option>
                  <option value="C-">C-</option>
                  <option value="D">D</option>
                </select>
              </td>
              <td>
                <input type="number" class="marksInput" min="0" max="100" placeholder="Enter marks" />
              </td>
            </tr>
          </tbody>
        </table>

        <button class="submit-button" onclick="submitGrades()">Submit Grades</button>
      </div>
    </div>
  </div>

</body>
</html>