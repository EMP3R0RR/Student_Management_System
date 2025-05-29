<?php


session_start();

// Check session timeout (30 minutes)
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
    session_unset();
    session_destroy();
    header("Location: LoginView.php?error=Session expired");
    exit();
}
$_SESSION['last_activity'] = time();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: LoginView.php?error=Please log in");
    exit();
}

// Check if user is a student
if ($_SESSION['role'] !== 'student') {
    $profile_pages = [
        'student' => 'StudentProfile.php',
        'teacher' => 'teacherprofile.php',
        'admin' => 'AdminProfile.php'
    ];
    header("Location: " . $profile_pages[$_SESSION['role']] . "?error=Unauthorized access");
    exit();
}

// Mark profile as visited for navigation control
$_SESSION['visited_profile'] = true;



?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard</title>

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
      overflow-y: auto;
      display: flex;
      justify-content: center;
      align-items: flex-start;
    }

    /* NEW PROFILE CONTAINER STYLES */
    .profile-container {
      background-color: white;
      padding: 2rem;
      width: 100%;
      max-width: 1500px;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
      border-radius: 10px;
    }

    .profile-header {
      font-size: 1.8rem;
      margin-bottom: 1.5rem;
      color: #004080;
    }

    .profile-pic-section {
      display: flex;
      align-items: center;
      margin-bottom: 1.5rem;
    }

    .profile-pic-section img {
      width: 150px;
      height: 150px;
      border-radius: 50%;
      object-fit: cover;
      margin-right: 1rem;
      border: 2px solid #004080;
    }

    .profile-pic-section input[type="file"] {
      display: none;
    }

    .upload-btn {
      padding: 0.5rem 1rem;
      background-color: #004080;
      color: white;
      border: none;
      border-radius: 6px;
      cursor: pointer;
    }

    .profile-form {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1rem 2rem;
    }

    .profile-form label {
      font-weight: bold;
      color: #333;
    }

    .profile-form input {
      padding: 0.5rem;
      border-radius: 5px;
      border: 1px solid #ccc;
      width: 100%;
    }

    .full-width {
      grid-column: span 2;
    }

    .edit-btn , .download-btn {
      margin-top: 1.5rem;
      padding: 0.6rem 1.2rem;
      background-color: #004080;
      color: white;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-size: 1rem;
    }
    
  </style>
</head>

<body>
  <header>
    <div class="header-left">
      <h1>Welcome to the Dashboard</h1>
    </div>
    <div class="header-center">
      <div class="search-bar" id="searchBarContainer">
        <input type="text" id="dashboardSearch" placeholder="Search..." />
        <button id="searchButton">Search</button>
      </div>
    </div>
    <div class="header-right">
        <form action="" method="POST">
        <button type="submit">🔔 Notifications</button>
        </form>
    </div>
    <div class="header-right">
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

    <div class="maindiv" id="mainContent" method="post" action="StudentProfile.php">
      <div class="profile-container">
        <div class="profile-header">Hello !!</div>
        <div class="profile-pic-section">
          <img src="https://via.placeholder.com/120" alt="Profile Picture" id="profileImage" />
          <label class="upload-btn">
            Upload Photo
            <input type="file" id="photoUpload" accept="image/*" />
          </label>
        </div>

        <form class="profile-form" id="profileForm">
          <div>
            <label>First Name</label>
            <input type="text" id="firstName" value="" disabled />
          </div>
          <div>
            <label>Last Name</label>
            <input type="text" id="lastName" value="" disabled />
          </div>
          <div>
            <label>Email</label>
            <input type="email" id="email" value="" disabled />
          </div>
          <div>
            <label>Department</label>
            <input type="text" id="department" value=""disabled />
          </div>
          <div>
            <label>Age</label>
            <input type="number" id="age" value="" disabled />
          </div>
          <div>
            <label>Blood Group</label>
            <input type="text" id="bloodGroup" value="" disabled />
          </div>
          <div class="full-width">
            <label>Address</label>
            <input type="text" id="address" value="" disabled />
          </div>
          <div class="full-width">
            <button type="button" class="edit-btn" onclick="toggleEdit()">Edit Profile</button>
            <button type="button" class="download-btn" onclick="downloadProfile()">Download Profile</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    function toggleEdit() {
      const inputs = document.querySelectorAll("#profileForm input");
      const button = document.querySelector(".edit-btn");

      if (button.textContent === "Edit Profile") {
        inputs.forEach(input => input.disabled = false);
        button.textContent = "Save Profile";
      } else {
        let valid = true;
        inputs.forEach(input => {
          if (input.value.trim() === "") {
            valid = false;
            input.style.border = "2px solid red";
          } else {
            input.style.border = "1px solid #ccc";
          }
        });

        if (valid) {
          inputs.forEach(input => input.disabled = true);
          button.textContent = "Edit Profile";
          alert("Profile updated successfully!");
        } else {
          alert("Please fill in all fields before saving.");
        }
      }
    }

    document.getElementById("photoUpload").addEventListener("change", function () {
      const file = this.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
          document.getElementById("profileImage").src = e.target.result;
        };
        reader.readAsDataURL(file);
      }
    });

    function downloadProfile() {
      const inputs = document.querySelectorAll('#profileForm input');
      let textData = "User Profile Info:\n\n";
      inputs.forEach(input => {
        textData += `${input.name}: ${input.value}\n`;
      });

      const blob = new Blob([textData], { type: "text/plain" });
      const link = document.createElement("a");
      link.href = URL.createObjectURL(blob);
      link.download = "profile_info.txt"; 
      link.click();
    }
  </script>
</body>
</html>
