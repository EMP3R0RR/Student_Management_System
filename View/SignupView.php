<?php
  session_start();
    $error_message = "";
    
    if (isset($_SESSION['error'])) {
    $error_message = $_SESSION['error'];
    unset($_SESSION['error']); 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Sign Up</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(to right, #e0ecff, #f3f9ff);
      color: #333;
      line-height: 1.6;
      min-height: 100vh;
    }

    .landing-header {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      z-index: 1000;
    }

    header {
      background-color: #004080;
      color: white;
      padding: 1rem 2rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
    }

    header h1 {
      font-size: 1.8rem;
      cursor: pointer;
    }

    nav {
      display: flex;
      gap: 1rem;
    }

    nav button {
      background: transparent;
      border: 1px solid white;
      padding: 0.4rem 1rem;
      color: white;
      font-weight: 500;
      border-radius: 6px;
      cursor: pointer;
      transition: background-color 0.3s, color 0.3s;
    }

    nav button:hover {
      background-color: white;
      color: #004080;
    }

    .page-layout {
      display: flex;
      justify-content: space-between;
      gap: 2rem;
      margin: 8rem auto 2rem;
      padding: 2rem;
      max-width: 1100px;
      background-color: #ffffffd9;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.07);
    }

    .form-container,
    .side-container {
      flex: 1;
      padding: 1rem;
    }

    .form-container h2,
    .side-container h2 {
      text-align: center;
      color: #004080;
      margin-bottom: 1rem;
    }

    form label {
      display: block;
      margin-top: 12px;
      font-weight: 500;
      color: #003366;
    }

    input, select {
      width: 100%;
      padding: 10px;
      font-size: 1rem;
      margin-top: 6px;
      border: 1px solid #ccc;
      border-radius: 6px;
    }

    .row-fields {
      display: flex;
      justify-content: space-between;
      gap: 1rem;
    }

    .field {
      flex: 1;
    }

    button[type="submit"] {
      margin-top: 20px;
      width: 100%;
      padding: 10px;
      background-color: #004080;
      color: white;
      border: none;
      border-radius: 6px;
      font-size: 1rem;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    button[type="submit"]:hover {
      background-color: #0059b3;
    }

    .error {
      color: red;
      font-size: 0.9rem;
      margin-top: 5px;
    }

    .info-section {
      margin-bottom: 1.5rem;
    }

    .info-section h3 {
      font-size: 1.1rem;
      margin-bottom: 0.5rem;
      color: #003366;
    }

    .info-section ul {
      list-style: disc;
      padding-left: 1.5rem;
    }

    .info-section li {
      margin-bottom: 0.5rem;
      color: #444;
    }

    @media (max-width: 768px) {
      .page-layout {
        flex-direction: column;
      }

      nav {
        flex-direction: column;
        margin-top: 1rem;
      }

      nav button {
        width: 100%;
        text-align: left;
      }

      .row-fields {
        flex-direction: column;
      }
    }
  </style>
</head>
<body>

  <!-- Landing Header -->
  <div class="landing-header">
    <header>
      <h1 onclick="window.location.href='LandingPageView.php'">Student Management System</h1>
      <nav>
       <button onclick="window.location.href='LoginView.php'">LOGIN</button>
        <button onclick="window.location.href='SignupView.php'">SIGNUP</button>
        <button onclick="window.location.href='AboutUsView.php'">TEAM</button>
        <button onclick="window.location.href='LandingPageView.php'">BACK</button>
      </nav>
    </header>
  </div>

  <!-- Sign Up Form and Info Panel -->
  <div class="page-layout">
    <!-- Sign Up Form -->
    <div class="form-container suform-container" id="signup">
      <h2>Sign Up</h2>
    <?php if (!empty($error_message)): ?>
  <div class="error" style="color: red;"><?php echo htmlspecialchars($error_message); ?></div>
  <?php endif; ?>
      <form id="signupForm" action="../Controller/UserSignupController.php" method="POST">
        <div class="error" id="errorMsg"></div>

        <label for="fname">First Name</label>
        <input type="text" id="fname" name="fname" required value="<?php echo isset($_POST['fname']) ? htmlspecialchars($_POST['fname']) : ''; ?>" />

        <label for="lname">Last Name</label>
        <input type="text" id="lname" name="lname" required value="<?php echo isset($_POST['lname']) ? htmlspecialchars($_POST['lname']) : ''; ?>" />

        <label for="email">Email</label>
        <input type="email" id="email" name="email" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" />

        <label for="age">Age</label>
        <input type="number" id="age" name="age" required value="<?php echo isset($_POST['age']) ? htmlspecialchars($_POST['age']) : ''; ?>" />

        <div class="row-fields">
          <div class="field">
            <label for="bloodGroup"><strong>Blood Group</strong></label>
            <select id="bloodGroup" name="bloodGroup" required>
              <option value="">Select</option>
              <option value="A+" <?php echo isset($_POST['bloodGroup']) && $_POST['bloodGroup'] === 'A+' ? 'selected' : ''; ?>>A+</option>
              <option value="A-" <?php echo isset($_POST['bloodGroup']) && $_POST['bloodGroup'] === 'A-' ? 'selected' : ''; ?>>A-</option>
              <option value="B+" <?php echo isset($_POST['bloodGroup']) && $_POST['bloodGroup'] === 'B+' ? 'selected' : ''; ?>>B+</option>
              <option value="B-" <?php echo isset($_POST['bloodGroup']) && $_POST['bloodGroup'] === 'B-' ? 'selected' : ''; ?>>B-</option>
              <option value="O+" <?php echo isset($_POST['bloodGroup']) && $_POST['bloodGroup'] === 'O+' ? 'selected' : ''; ?>>O+</option>
              <option value="O-" <?php echo isset($_POST['bloodGroup']) && $_POST['bloodGroup'] === 'O-' ? 'selected' : ''; ?>>O-</option>
              <option value="AB+" <?php echo isset($_POST['bloodGroup']) && $_POST['bloodGroup'] === 'AB+' ? 'selected' : ''; ?>>AB+</option>
              <option value="AB-" <?php echo isset($_POST['bloodGroup']) && $_POST['bloodGroup'] === 'AB-' ? 'selected' : ''; ?>>AB-</option>
            </select>
          </div>
          <div class="field">
            <label for="department"><strong>Department</strong></label>
            <select id="department" name="department" required>
              <option value="">Select</option>
              <option value="CSE" <?php echo isset($_POST['department']) && $_POST['department'] === 'CSE' ? 'selected' : ''; ?>>CSE</option>
              <option value="BBA" <?php echo isset($_POST['department']) && $_POST['department'] === 'BBA' ? 'selected' : ''; ?>>BBA</option>
              <option value="EEE" <?php echo isset($_POST['department']) && $_POST['department'] === 'EEE' ? 'selected' : ''; ?>>EEE</option>
              <option value="ENGLISH" <?php echo isset($_POST['department']) && $_POST['department'] === 'ENGLISH' ? 'selected' : ''; ?>>ENGLISH</option>
              <option value="DATA SCIENCE" <?php echo isset($_POST['department']) && $_POST['department'] === 'DATA SCIENCE' ? 'selected' : ''; ?>>DS</option>
              <option value="LLB" <?php echo isset($_POST['department']) && $_POST['department'] === 'LLB' ? 'selected' : ''; ?>>LLB</option>
            </select>
          </div>
        </div>

        <label for="address">Address</label>
        <input type="text" id="address" name="address" required value="<?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?>" />

        <label for="password">Password</label>
        <input type="text" id="password" name="password" required />

        <label for="confirm">Confirm Password</label>
        <input type="text" id="confirm" name="confirm" required />

        <button type="submit">Sign Up</button>
      </form>
    </div>

    
    <div class="side-container">
      <div class="info-container" id="signup-info">
        <h2>Why Sign Up?</h2>
        <div class="info-section">
          <h3>🎓 Create Your Academic Profile</h3>
          <ul>
            <li>Track your academic history and performance.</li>
            <li>Join classes and access materials.</li>
          </ul>
        </div>
        <div class="info-section">
          <h3>📋 Secure Enrollment</h3>
          <ul>
            <li>Verified identity for smooth communication.</li>
            <li>Role-specific features tailored to your needs.</li>
          </ul>
        </div>
        <div class="info-section">
          <h3>💡 Personalized Experience</h3>
          <ul>
            <li>Custom notifications and reminders.</li>
            <li>Access to grades, attendance, and reports.</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <script>

    function validation() {

    const signupForm = document.getElementById("signupForm");
    
    if (signupForm){
        signupForm.addEventListener("submit", function(e) {
            e.preventDefault();
    const fname = document.getElementById("fname").value.trim();
    const lname = document.getElementById("lname").value.trim();
    const email = document.getElementById("email").value.trim();
    const age = document.getElementById("age").value.trim();
    const bloodGroup = document.getElementById("bloodGroup").value;
    const department = document.getElementById("department").value;
    const address = document.getElementById("address").value.trim();
    const password = document.getElementById("password").value.trim();
    const confirm = document.getElementById("confirm").value.trim();

    const errorMsg = document.getElementById("errorMsg");
    errorMsg.textContent = ""; 

    
    if (
      !fname || !lname || !email || !age || !bloodGroup || !department ||
      !address || !password || !confirm
    ) {
      errorMsg.textContent = "Please fill out all fields.";
      return;
    }

    
    const emailPattern = /^[^@\s]+@[^@\s]+\.[^@\s]+$/;
    if (!emailPattern.test(email)) {
      errorMsg.textContent = "Please enter a valid email address.";
      return;
    }

    
    if (password !== confirm) {
      errorMsg.textContent = "Passwords do not match."
      return;
    }

    
    alert("Signup successful!");
    location.reload();
    

        });
    }

    window.location.href="LoginView.php"

   
}  
    
  </script>
</body>
</html>