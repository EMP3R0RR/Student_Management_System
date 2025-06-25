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
      height: 0px;
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
      background-color: #ffffff;
      border-radius: 10px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
      padding: 1.5rem;
      margin-bottom: 2rem;
    }

    .overview-container h2 {
      margin-bottom: 1rem;
      color: #003366;
    }

    /* Table style */
    .overview-table {
      width: 100%;
      border-collapse: collapse;
    }

    .overview-table th,
    .overview-table td {
      border: 1px solid #cccccc;
      padding: 0.75rem;
      text-align: left;
    }

    .overview-table th {
      background-color: #e6f0ff;
      color: #003366;
    }

    .total-row {
      font-weight: bold;
      background-color: #f2f9ff;
    }

    /* Form fields */
    .form-group {
      margin-bottom: 1rem;
    }

    .form-group label {
      display: block;
      margin-bottom: 0.5rem;
      color: #003366;
    }

    .form-group input {
      width: 100%;
      padding: 0.5rem;
      border: 1px solid #ccc;
      border-radius: 6px;
    }

    .form-button {
      padding: 0.6rem 1.2rem;
      background-color:rgb(168, 43, 4);
      color: white;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-weight: bold;
      transition: background-color 0.3s;
    }

    .form-button:hover {
      background-color: #0059b3;
    }

    /* Search bar form */
    .search-form {
      display: flex;
      gap: 0.5rem;
      margin-bottom: 1rem;
    }

    .search-form input[type="email"] {
      padding: 0.5rem;
      border-radius: 4px;
      border: 1px solid #ccc;
      width: 250px;
    }

    .search-form button {
      padding: 0.5rem 1rem;
      border: none;
      border-radius: 4px;
      background-color: #689ed4;
      color: #ffffff;
      cursor: pointer;
      font-weight: bold;
      transition: background-color 0.3s;
    }

    .search-form button:hover {
      background-color: #004080;
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
      <button onclick="window.location.href='AdminProfileView.php'">Profile</button>
      <button onclick="window.location.href='AdminStudentOverview.php'">Student Overview</button>
      <button onclick="window.location.href='AdminTeacherOverview.php'">Teacher Overview</button>
      <button onclick="window.location.href='AdminCourseOverview.php'">Courses Overview</button>
      <button onclick="window.location.href='AdminSettingsView.php'">Settings</button>
    </div>

    
    <div class="maindiv" id="mainContent">

    <div class="overview-container" id="AdminListContainer">
      <h2>Admin List</h2>
      <table class="overview-table">
        <thead>
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Signup Date</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>MD AL-AMIN</td>
            <td>teacher@example.com</td>
            <td>20-04-25</td>
          </tr>
          <tr class="total-row">
            <td colspan="3">Total Admins: 1</td>
          </tr>
        </tbody>
      </table>
    </div>

    
    <div class="overview-container" id="newAdminContainer">
      <h2>New Admin</h2>
      <form method="post" action="../Controller/AdminSettingsController" id="newAdminForm" onsubmit="return validateNewAdminForm()">
        <div class="form-group">
          <label for="newAdminEmail">Email:</label>
          <input type="email" id="newAdminEmail" required>
        </div>
        <div class="form-group">
          <label for="newAdminPassword">Password:</label>
          <input type="password" id="newAdminPassword" required>
        </div>
        <button class="form-button" type="submit" onclick="handleAdminFormSubmit()">Create</button>
      </form>
    </div>

   
    <div class="overview-container" id="deleteAdminContainer">
      <h2>Delete Admin</h2>
      <form id="deleteAdminForm" onsubmit="return confirmDelete()">
        <div class="search-form">
          <input type="email" name="deleteEmail" id="deleteAdminEmail" placeholder="Enter Admin email" required>
          <button type="submit">Search</button>
        </div>
        <button class="form-button" type="submit">Delete</button>
      </form>
    </div>

    
    <div class="overview-container" id="AdminMessageContainer">
      <h2>Admin Messages</h2>
      <table class="overview-table">
        <thead>
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Message</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>MD AL-AMIN</td>
            <td>teacher@example.com</td>
            <td>20-04-25</td>
          </tr>
        </tbody>
      </table>
    </div>

  </div>
    </div>
  </div>
  

  
  <script>
  function handleAdminFormSubmit() {
    const form = document.getElementById('newAdminForm');

    if (validateNewAdminForm()) {
        
        const formData = {
            email: document.getElementById('newAdminEmail').value,
            password: document.getElementById('newAdminPassword').value
        };
         const jsonData = JSON.stringify(formData);

        const xhr = new XMLHttpRequest();
        xhr.open('POST', '../Controller/AdminSettingsController.php', true);
       xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
       xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    console.log('Success:', response);
                    alert('Admin created successfully!');
                    form.reset();
                } else {
                    console.error('Error:', xhr.status, xhr.statusText);
                    alert('Failed to create admin. Please try again.');
                }
            }
        };
       xhr.send(jsonData);
    }

    return false; 
}


function validateNewAdminForm() {
    const email = document.getElementById('newAdminEmail').value;
    const password = document.getElementById('newAdminPassword').value;
    
   
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        alert('Please enter a valid email address');
        return false;
    }

  
    if (password.length < 8) {
        alert('Password must be at least 8 characters long');
        return false;
    }

    return true;
}

  </script>

</body>
</html>
