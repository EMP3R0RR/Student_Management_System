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


if ($_SESSION['role'] !== 'admin') {
    $profile_pages = [
        'student' => 'StudentProfileView.php',
        'teacher' => 'teacherprofileView.php',
        'admin' => 'AdminProfileView.php',
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
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ADMIN Dashboard</title>

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
      cursor: default;
    }

    .header-center {
      flex-grow: 1;
      display: flex;
      justify-content: center;
    }

    .search-bar {
      display: flex;
      gap: 0.5rem;
    }

    .search-bar input[type="text"] {
      padding: 0.5rem;
      border-radius: 4px;
      border: none;
      width: 250px;
    }

    .search-bar button {
      padding: 0.5rem 1rem;
      border: none;
      border-radius: 4px;
      background-color: #ffffff;
      color: #004080;
      cursor: pointer;
      font-weight: bold;
      transition: background-color 0.3s;
    }

    .search-bar button:hover {
      background-color: #cce0ff;
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
      flex: 1;
      display: flex;
      height: calc(100% - 60px);
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
    .overview-container {
      display: flex;
      flex-direction: column;
      gap: 2rem;
    }

    .container-box {
      background-color: white;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      padding: 1.5rem;
    }

    .container-box h2 {
      margin-bottom: 1rem;
      color: #004080;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 1rem;
    }

    th, td {
      padding: 0.75rem;
      border: 1px solid #ccc;
      text-align: left;
    }

    th {
      background-color: #e6f0ff;
      color: #003366;
    }

    .form-group {
      margin-bottom: 1rem;
    }

    .form-group label {
      display: block;
      margin-bottom: 0.3rem;
      font-weight: bold;
    }

    .form-group input {
      width: 100%;
      padding: 0.5rem;
      border-radius: 6px;
      border: 1px solid #ccc;
    }

    .submit-btn {
      padding: 0.5rem 1rem;
      border: none;
      border-radius: 6px;
      background-color: #004080;
      color: white;
      font-weight: bold;
      cursor: pointer;
    }

    .submit-btn:hover {
      background-color: #0059b3;
    }

    .custom-search {
      display: flex;
      gap: 0.5rem;
      margin-bottom: 1rem;
    }

    .custom-search input[type="text"] {
      padding: 0.5rem;
      border-radius: 4px;
      border: 1px solid #ccc;
      width: 250px;
    }

    .custom-search button {
      padding: 0.5rem 1rem;
      border: none;
      border-radius: 4px;
      background-color: #004080;
      color: white;
      font-weight: bold;
      cursor: pointer;
    }

    .custom-search button:hover {
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
        <form action="../Controller/LogoutController.php" method="POST">
        <button type="submit">Logout</button>
        </form>
    </div>
  </header>

  <div class="dashboard">
   
    <div class="sidediv">
      <button onclick="window.location.href='AdminProfileView.php'">Profile</button>
      <button onclick="window.location.href='AdminStudentOverview.php'">Student Overview</button>
      <button onclick="window.location.href='AdminTeacherOverview.php'">Teacher Overview</button>
      <button onclick="window.location.href='AdminCourseOverview.php'">Courses Overview</button>
      <button onclick="window.location.href='AdminSettingsView.php'">Settings</button>
    </div>

    
    <div class="maindiv" id="mainContent">
        <div class="overview-container">
            <div class="container-box" id="availableCoursesContainer">
    <h2>Available Courses</h2>
    <table>
      <thead>
        <tr>
          <th>Course Name</th>
          <th>Price</th>
          <th>Schedule</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Computer Science</td>
          <td>5000 TAKA </td>
          <td>Mon/Wed/Fri</td>
        </tr>
        <tr>
          <td>Mathematics</td>
          <td>4000 TAKA</td>
          <td>Tue/Thu</td>
        </tr>
      </tbody>
    </table>
    <p><strong>Total Courses:</strong> 2</p>
  </div>
  <div class="container-box" id="newCourseContainer">
    <h2>New Course</h2>
    <form id="newCourseForm" onsubmit="return validateNewCourseForm()">
      <div class="form-group">
        <label for="courseName">Course Name:</label>
        <input type="text" id="courseName" name="courseName" required>
      </div>
      <div class="form-group">
        <label for="coursePrice">Price:</label>
        <input type="number" id="coursePrice" name="coursePrice" min="1" required>
      </div>
      <button type="submit" class="submit-btn">Create</button>
    </form>
  </div>
  <div class="container-box" id="deleteCourseContainer">
    <h2>Delete Course</h2>
    <form id="deleteCourseForm" onsubmit="return confirmDeleteCourse()">
      <div class="custom-search">
        <input type="text" id="deleteCourseInput" name="courseName" placeholder="Enter Course Name" required>
        <button type="submit">Search</button>
      </div>
      <button type="submit" class="submit-btn" style="background-color:#cc0000;">Delete</button>
    </form>
  </div>
        </div>
      
    </div>
</div>
  

  
  <script>
function validateNewCourseForm() {
      const name = document.getElementById("courseName").value.trim();
      const price = document.getElementById("coursePrice").value.trim();

      if (!name || !price || isNaN(price) || Number(price) <= 0) {
        alert("Please enter valid course details.");
        return false;
      }

      alert("Course created successfully!");
      return true;
    }

    function confirmDeleteCourse() {
      const courseName = document.getElementById("deleteCourseInput").value.trim();

      if (!courseName) {
        alert("Please enter a course name.");
        return false;
      }

      return confirm(`Are you sure you want to delete the course: ${courseName}?`);
    }

  </script>

</body>
</html>
