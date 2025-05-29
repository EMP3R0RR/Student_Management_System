<?php

session_start();

$error_message = '';


$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "school_db";
$port = 3307; 


$conn = mysqli_connect($servername, $username, $password, $dbname, $port);


if (!$conn) {
    $error_message = "Cannot connect to database: " . mysqli_connect_error();
} else {
    
    mysqli_set_charset($conn, "utf8mb4");
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($error_message)) {
    
    $role = isset($_POST['role']) ? trim($_POST['role']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    
    $valid_roles = ['student', 'teacher', 'admin'];
    if (empty($role) || !in_array($role, $valid_roles)) {
        $error_message = "Please select a valid role";
    } elseif (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Please enter a valid email";
    } elseif (empty($password)) {
        $error_message = "Please enter a password";
    } else {
         
        $query = "SELECT id, role, email, password FROM users WHERE email = ? AND role = ?";
        $stmt = mysqli_prepare($conn, $query);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ss", $email, $role);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $user = mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt);

            if ($user && password_verify($password, $user['password'])) {
                
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['email'] = $user['email'];

                
                session_regenerate_id(true);

                
                $role_pages = [
                    'student' => 'StudentProfile.php',
                    'teacher' => 'teacherprofile.php',
                    'admin' => 'admindashboard.php'
                ];
                header("Location: " . $role_pages[$role]);
                exit();
            } else {
                $error_message = "Invalid email, password, or role";
            }
        } else {
            $error_message = "Database query error: " . mysqli_error($conn);
        }
    }
}


if ($conn) {
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login - Student Management System</title>
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

    label {
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

    .password-container {
      position: relative;
    }

    .toggle-password {
      position: absolute;
      top: 50%;
      right: 10px;
      transform: translateY(-50%);
      cursor: pointer;
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
    }
  </style>
</head>
<body>
  <!-- Landing Header -->
  <div class="landing-header">
    <header>
      <h1 onclick="window.location.href='landingpage.php'">Student Management System</h1>
      <nav>
        <button onclick="window.location.href='LoginView.php'">LOGIN</button>
        <button onclick="window.location.href='signup.php'">SIGNUP</button>
        <button onclick="window.location.href='aboutus.php'">TEAM</button>
        <button onclick="window.location.href='landingpage.php'">BACK</button>
      </nav>
    </header>
  </div>

  <!-- Login Form and Info Panel -->
  <div class="page-layout">
    <!-- Left: Login Form -->
    <div class="form-container" id="login">
      <h2>Login</h2>
      <!-- <?php if ($error_message): ?>
        <div class="error"><?php echo htmlspecialchars($error_message); ?></div>
      <?php endif; ?> -->
      <form id="loginForm" action="login.php" method="POST">
        <label for="role">Login as</label>
        <select id="role" name="role" required>
          <option value="">Select Role</option>
          <option value="student" <?php echo isset($_POST['role']) && $_POST['role'] === 'student' ? 'selected' : ''; ?>>Student</option>
          <option value="teacher" <?php echo isset($_POST['role']) && $_POST['role'] === 'teacher' ? 'selected' : ''; ?>>Teacher</option>
          <option value="admin" <?php echo isset($_POST['role']) && $_POST['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
        </select>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" />
        <div class="error" id="emailError"></div>

        <label for="password">Password</label>
        <div class="password-container">
          <input type="password" id="password" name="password" required />
          <span class="toggle-password" onclick="showOrHidePassword()">👁</span>
        </div>

        <button type="submit" id="loginBtn">Login</button>
        <p style="text-align:center;"><a href="#">Forgot Password?</a></p>
        <p style="text-align:center;">Don't have an account? <a href="signup.html">Sign up</a></p>
      </form>
    </div>

    <!-- Right: Info Container -->
    <div class="side-container">
      <div class="info-container" id="login-info">
        <h2>Why Login?</h2>
        <div class="info-section">
          <h3>🔒 Secure Access</h3>
          <ul>
            <li>Your data is protected with top-tier security protocols.</li>
            <li>Role-based access ensures you're in the right space.</li>
          </ul>
        </div>
        <div class="info-section">
          <h3>🎯 Personalized Dashboard</h3>
          <ul>
            <li>Students view courses, attendance, and grades.</li>
            <li>Teachers manage classes and student performance.</li>
            <li>Admins maintain the database and reports.</li>
          </ul>
        </div>
        <div class="info-section">
          <h3>⚙️ Efficient Tools</h3>
          <ul>
            <li>Smart validation and search tools.</li>
            <li>Export and backup capabilities.</li>
            <li>Performance tracking modules.</li>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <script>
    function showOrHidePassword() {
      const passwordInput = document.getElementById("password");
      if (passwordInput.type === "password") {
        passwordInput.type = "text";
      } else {
        passwordInput.type = "password";
      }
    }

    const loginForm = document.getElementById("loginForm");
    const emailError = document.getElementById("emailError");

    if (loginForm) {
      loginForm.addEventListener("submit", function (e) {
        const role = document.getElementById("role").value.trim();
        const email = document.getElementById("email").value.trim();
        const password = document.getElementById("password").value.trim();

        emailError.textContent = "";

        if (!role || !email || !password) {
          e.preventDefault();
          alert("Please fill in all fields.");
          return;
        }

        const emailPattern = /^[^@\s]+@[^@\s]+\.[^@\s]+$/;
        if (!emailPattern.test(email)) {
          e.preventDefault();
          emailError.textContent = "Please enter a valid email address.";
          return;
        }

        const roles = ["student", "teacher", "admin"];
        if (!roles.includes(role)) {
          e.preventDefault();
          alert("Please select a valid role.");
          return;
        }
      });
    }
  </script>
</body>
</html>