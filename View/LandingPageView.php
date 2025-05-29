<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Landing Page</title>
  <link rel="stylesheet" href="style.css" />
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

    /* Fixed header wrapper */
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

    /* ✅ Landing main layout */
    .landing-main {
      max-width: 1100px;
      margin: 8rem auto 2rem; /* ensures it appears below the fixed header */
      padding: 1rem;
    }

    .feature-info {
      background-color: #ffffffd9;
      padding: 2rem;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.07);
      transition: transform 0.3s ease;
      margin-bottom: 2rem;
    }

    .feature-info:hover {
      transform: translateY(-3px);
    }

    h2 {
      font-size: 1.8rem;
      color: #003366;
      margin-bottom: 1rem;
    }

    h3 {
      font-size: 1.2rem;
      color: #004080;
      margin-top: 1.5rem;
      margin-bottom: 0.5rem;
    }

    p {
      font-size: 0.95rem;
      margin-bottom: 1rem;
      text-align: center;
    }

    ul {
      list-style: disc;
      padding-left: 1.5rem;
      margin-bottom: 1rem;
    }

    ul li {
      margin-bottom: 0.6rem;
    }

    /* Responsive layout */
    @media (max-width: 768px) {
      header {
        flex-direction: column;
        align-items: flex-start;
      }

      nav {
        flex-direction: column;
        width: 100%;
        margin-top: 1rem;
      }

      nav button {
        width: 100%;
        text-align: left;
      }

      .landing-main {
        padding: 1rem;
        margin-top: 10rem;
      }
    }
  </style>
</head>
<body>

  <!-- Landing header wrapper -->
  <div class="landing-header">
    <header>
      <h1 >Student Management System</h1>
      <nav>
        <button onclick="window.location.href='LoginView.php'">LOGIN</button>
        <button onclick="window.location.href='Signupview.php'">SIGNUP</button>
        <button onclick="window.location.href='AboutUsView.php'">TEAM</button>
        <button onclick="window.location.href='LandingPageView.php'">BACK</button>
      </nav>
    </header>
  </div>

  <!-- landing main container -->
  <div class="landing-main">

    <!--  Feature info block -->
    <div class="feature-info" id="feature-info">
      <h2>📚 Student Management Made Easy</h2>
      <p>
        Our Student Management System helps you register, organize, and manage student information efficiently – all in one place.
      </p>

      <h3>✅ Key Features</h3>
      <ul>
        <li><strong>Student Registration:</strong> Add students with name, class, roll number, and contact info.</li>
        <li><strong>Manage Records:</strong> View, edit, or delete student data quickly.</li>
        <li><strong>Smart Validation:</strong> Email, roll, and phone validation in real-time.</li>
        <li><strong>Search & Sort:</strong> Filter by name, class, or roll number.</li>
        <li><strong>Responsive Dashboard:</strong> Clean and intuitive interface.</li>
      </ul>

      <h3>📦 Bonus Tools</h3>
      <ul>
        <li>Download student lists as CSV</li>
        <li>Optional profile photo upload</li>
        <li>Modules for attendance or performance</li>
      </ul>

      <p><strong>Start managing your students the smart way!</strong></p>
    </div>

    <!--  Main content area for login/signup -->
    <div id="mainContent"></div>
  </div>
  

  
  

<script>
  //for dynamic page loads. 

  
</script>



</body>
</html>
