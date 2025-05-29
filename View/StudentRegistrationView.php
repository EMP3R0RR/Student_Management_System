<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


require_once __DIR__ . '/../Model/StudentRegistrationModel.php';
$model = new StudentRegistrationModel();
$courses = $model->getAllCourses();


if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
    session_unset();
    session_destroy();
    header("Location: LoginView.php?error=Session expired");
    exit();
}
$_SESSION['last_activity'] = time();
if (isset($_SESSION['success'])) {
    echo $_SESSION['success'];
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Course Registration</title>
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
    .success {
            color: green;
            font-weight: bold;
        }

    .header-left {
      display: flex;
      align-items: center;
    }

    header h1 {
      font-size: 1.5rem;
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
    }

    .sidediv button {
      padding: 0.8rem 1rem;
      background-color: #004080;
      color: white;
      border: none;
      border-radius: 8px;
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
      background-color: #fff;
      padding: 2rem;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 1.5rem;
    }

    th, td {
      padding: 12px;
      text-align: center;
      border-bottom: 1px solid #ddd;
    }

    th {
      background-color: #f0f0f0;
    }

    select {
      width: 100%;
      padding: 10px;
      font-size: 1rem;
      margin-top: 1rem;
      border-radius: 6px;
      border: 1px solid #ccc;
    }

    .regbtn {
      margin-top: 1rem;
      width: 100%;
      padding: 12px;
      font-size: 1rem;
      background-color: #004080;
      color: white;
      border: none;
      border-radius: 6px;
      font-weight: bold;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .regbtn button :hover {
      background-color:rgb(103, 136, 167);
    }

    .total-box {
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
      <button onclick="window.location.href='StudentProfileView.php'">Profile</button>
      <button onclick="window.location.href='StudentGradesView.php'">Grades</button>
      <button onclick="window.location.href='StudentRegistrationView.php'">Registration</button>
      <button onclick="window.location.href='StudentPaymentView.php'">Payment</button>
    </div>

    <div class="maindiv">
      <main class="form-container">
        <h2>Course Registration</h2>
          <?php if (isset($_SESSION['success'])): ?>
          <p class="success-message"><?php echo htmlspecialchars($_SESSION['success'], ENT_QUOTES, 'UTF-8'); ?></p>
          <?php unset($_SESSION['success']);  ?>
          <?php endif; ?>
       <form id="registrationForm" action= "../Controller/StudentRegistrationController.php" method="POST">
          <table>
            <thead>
              <tr>
                <th>Select</th>
                <th>Course</th>
                <th>Price</th>
                <th>Schedule</th>
              </tr>
            </thead>
            <tbody>
            <?php foreach ($courses as $course): ?>
              <tr>
                <td>
                <input type="checkbox" class="course-checkbox" 
                   name="courses[]" 
                   value="<?= htmlspecialchars($course['course_id']) ?>" 
                   data-price="<?= htmlspecialchars($course['price']) ?>" 
                   id="course-<?= htmlspecialchars($course['course_id']) ?>">
                </td>
                     <td><?= htmlspecialchars($course['course_name']) ?></td>
                           <td>$<?= number_format($course['price'], 2) ?></td>
                         <td><?= htmlspecialchars($course['schedule']) ?></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
          </table>

          <label for="semester">Select Semester:</label>
          
          <select id="semester" name="semester">
            <option value="">-- Select --</option>
            <option value="spring">Spring 2025</option>
            <option value="summer">Summer 2025</option>
            <option value="fall">Fall 2025</option>
          </select>
              

          <input type="hidden" id="selected_courses" name="selected_courses" value="0">
          <input type="hidden" id="total_cost" name="total_cost" value="0">

          <button type="submit" class="regbtn">Register Selected Courses</button>
        </form>

        <div class="total-box">
            Selected Courses: <span id="selectedCount">0</span><br>
            Total Cost: $<span id="totalCost">0</span>
        </div>
      </main>
    </div>
  </div>

  <script>
    const checkboxes = document.querySelectorAll(".course-checkbox");
    const totalCostSpan = document.getElementById("totalCost");
    const selectedCountSpan = document.getElementById("selectedCount");
    const selectedCoursesInput = document.getElementById("selected_courses");
    const totalCostInput = document.getElementById("total_cost");

    function updateCourseSummary() {
      let total = 0;
      let count = 0;

      checkboxes.forEach(checkbox => {
        if (checkbox.checked) {
          total += parseFloat(checkbox.dataset.price);
          count++;
        }
      });

      totalCostSpan.textContent = total.toFixed(2);
      selectedCountSpan.textContent = count;
      selectedCoursesInput.value = count;
      totalCostInput.value = total.toFixed(2);
    }

    checkboxes.forEach(cb => {
      cb.addEventListener("change", updateCourseSummary);
    });

    document.getElementById("registrationForm").addEventListener("submit", function(e) {
      e.preventDefault();
      const semester = document.getElementById("semester").value;
      const selectedCourses = Array.from(checkboxes).filter(cb => cb.checked);

      if (selectedCourses.length === 0) {
        alert("Please select at least one course.");
        return;
      }

      if (!semester) {
        alert("Please select a semester.");
        return;
      }

      // Submit form
      this.submit();
    });
  </script>

</body>
</html>