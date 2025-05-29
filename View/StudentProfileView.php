<?php 
require_once('../Controller/StudentProfileController.php'); 
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

    .edit-btn , .download-btn, .savep-btn {
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
        <form action="../Controller/LogoutController.php" method="POST">
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

    <div class="maindiv" id="mainContent">
      <div class="profile-container">
        <div class="profile-header">Hello !!</div>
        <form action="../Controller/StudentProfileController.php" id="uploadForm" method="POST" enctype="multipart/form-data">
         <div class="profile-pic-section">
          <img src="" alt="" id="profileImage" />
          <label class="upload-btn">
            Upload Photo
            <input type="file" name="profile_pic" id="photoUpload" required />
          </label>
           
        </div>
        <button type="submit" class="upload-btn">Save Photo</button>
      </form>

        <form class="profile-form" id="profileForm">
          <div>
            <label>First Name</label>
            <input type="text" id="firstName"  name="first_name" value="" disabled />
          </div>
          <div>
            <label>Last Name</label>
            <input type="text" id="lastName" name="last_name" value="" disabled />
          </div>
          <div>
            <label>Email</label>
            <input type="email" id="email" name="email" value="" disabled />
          </div>
          <div>
            <label>Department</label>
            <input type="text" id="department" name="department" value=""disabled />
          </div>
          <div>
            <label>Age</label>
            <input type="number" id="age" name="age" value="" disabled />
          </div>
          <div>
            <label>Blood Group</label>
            <input type="text" id="bloodGroup" name="blood_group" value="" disabled />
          </div>
          <div class="full-width">
            <label>Address</label>
            <input type="text" id="address" name="address" value="" disabled />
          </div>
          <div class="full-width">
            <button type="button" class="edit-btn" >Edit Profile</button>
            
            <button type="button" class="download-btn">Download Profile</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>

    document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById('uploadForm');
    const profileImage = document.getElementById('profileImage');

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const xhr0 = new XMLHttpRequest();
        const formData = new FormData(form);
        xhr0.open('POST', '../Controller/StudentProfileController.php', true);
        xhr0.onreadystatechange = function () {
            if (xhr0.readyState === 4) {
                if (xhr0.status === 200) {
                    try {
                        const response = JSON.parse(xhr0.responseText);
                        if (response.success) {
                            alert('Profile picture updated successfully!');
                        } else {
                            alert('Error: ' + response.error);
                        }
                    } catch (e) {
                        alert('Unexpected response: ' + xhr0.responseText);
                    }
                } else {
                    alert('Upload failed. Server returned status: ' + xhr0.status);
                }
            }
        };

        xhr0.send(formData);
    });

    document.getElementById("photoUpload").addEventListener("change", function () {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                profileImage.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });

    function loadProfileData() {
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "../Controller/StudentProfileController.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    try {
                        const data = JSON.parse(xhr.responseText);
                        if (data.error) {
                            alert("Failed to load profile: " + data.error);
                            return;
                        }

                        document.getElementById("firstName").value = data.first_name || "";
                        document.getElementById("lastName").value = data.last_name || "";
                        document.getElementById("email").value = data.email || "";
                        document.getElementById("department").value = data.department || "";
                        document.getElementById("age").value = data.age || "";
                        document.getElementById("bloodGroup").value = data.blood_group || "";
                        document.getElementById("address").value = data.address || "";
                    } catch (e) {
                        console.error("Parsing error:", e);
                        alert("Error parsing server response.");
                    }
                } else {
                    console.error("HTTP error:", xhr.status);
                    alert("An error occurred while fetching profile data.");
                }
            }
        };

        xhr.send("json=true");
    }

    loadProfileData(); 

    
    
});
    


  </script>
</body>
</html>
