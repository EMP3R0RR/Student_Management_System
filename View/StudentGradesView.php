<?php
require_once '../Controller/StudentGradesController.php';


// Duplicate minimal session/auth checks here or in an included common auth file
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: LoginView.php?error=Please log in as student");
    exit();
}

if (!isset($_SESSION['visited_profile']) || $_SESSION['visited_profile'] !== true) {
    header("Location: StudentProfileView.php?error=Visit profile first");
    exit();
}
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
     <button onclick="window.location.href='StudentProfileView.php'">Profile</button>
      <button onclick="window.location.href='StudentGradesView.php'">Grades</button>
      <button onclick="window.location.href='StudentRegistrationView.php'">Registration</button>
      <button onclick="window.location.href='StudentPaymentView.php'">Payment</button>
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
                    <tbody id="gradesBody">
                        <tr>
                            <th id="sub"name="Subject" value=""></th>
                            <th id="marks"name="Marks" value=""></th>
                            <th id="gpa"name="GPAL" value=""></th>
                        </tr>
                    </tbody>
                </table>

        
        <div class="total-container" id="totalContainer">
          Total Average Marks: <span id="avgMarks">0</span><br>
          Total Average GPA: <span id="avgGPA">0</span>
        </div>
      </main>
    </div>
  </div>

  
  <script>
         window.onload = function () {
      fetch('../Controller/StudentGradesController.php?json=true')
      .then(response => response.json())
      .then(data => {
        if (data.error) {
          alert("hoynai" + data.error);
          return;
        }

        
        document.getElementById("sub").value = data.Subject || "";
        document.getElementById("marks").value = data.Marks || "";
        document.getElementById("gpa").value = data.GPAL || "";
        
      })
      .catch(err => {
        console.error("AJAX error:", err);
        alert("An error occurred while fetching Grades data.");
      });
  };
    </script>

</body>
</html>
