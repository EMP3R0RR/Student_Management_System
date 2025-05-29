<?php

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>About Us</title>
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

    header h1, header h2 {
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

    .as {
      margin-top: 7rem;
      padding: 2rem;
      max-width: 1100px;
      margin-left: auto;
      margin-right: auto;
    }

    .aucontainer {
      display: flex;
      align-items: center;
      background-color: #ffffff;
      border-radius: 10px;
      padding: 1.5rem;
      margin-bottom: 2rem;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.07);
      transition: transform 0.3s ease;
    }

    .aucontainer:hover {
      transform: translateY(-3px);
    }

    .image-box {
      width: 180px;
      height: 180px;
      background-color: #cce0ff;
      border-radius: 10px;
      background-size: cover;
      background-position: center;
      flex-shrink: 0;
      margin-right: 2rem;
    }

    .text-content {
      flex: 1;
    }

    .text-content h2 {
      font-size: 1.5rem;
      margin-bottom: 0.5rem;
      color: #003366;
    }

    .text-content p {
      font-size: 0.95rem;
      color: #333;
    }

    @media (max-width: 768px) {
      .aucontainer {
        flex-direction: column;
        align-items: flex-start;
        text-align: center;
      }

      .image-box {
        margin-bottom: 1rem;
        margin-right: 0;
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
        <button onclick="window.location.href='Signupview.php'">SIGNUP</button>
        <button onclick="window.location.href='AboutUsView.php'">TEAM</button>
        <button onclick="window.location.href='LandingPageView.php'">BACK</button>
      </nav>
    </header>
  </div>

  <!-- About Us Section -->
  <main class="as">
    <header>
      <h2>About Us</h2>
    </header>

    <div class="aucontainer university-info">
      <div class="image-box" style="background-image: url('images/pic1.png');"></div>
      <div class="text-content">
        <h2>Our University</h2>
        <p>American International University-Bangladesh (AIUB) is a leading private university in Dhaka known for its modern campus and academic excellence. The Computer Science and Engineering (CSE) department offers a dynamic curriculum that blends theoretical knowledge with hands-on experience, supported by advanced labs and research opportunities. Accredited by IEB and PAASCU, the department prepares students for global careers through industry-driven projects and mandatory internships.</p>
      </div>
    </div>

    <div class="aucontainer">
      <div class="image-box" style="background-image: url('images/pic2.png');"></div>
      <div class="text-content">
        <h2>MD. Al-Amin</h2>
        <p>Project Supervisor and Course Faculty</p> <br>
        <p>Email : alamin@aiub.edu</p> <br>
        <p>Github : https://github.com/alamin200290</p> <br>

      </div>
    </div>

    <div class="aucontainer">
      <div class="image-box" style="background-image: url('images/pic3.png');"></div>
      <div class="text-content">
        <h2>Syed Musaeed Hossain</h2>
        <p>Project lead and back-end Architect </p> <br>
        <p>Email : syedpunno@gmail.com</p> <br>
        <p>Github : https://github.com/EMP3R0RR</p> <br>

      </div>
    </div>

    <div class="aucontainer">
      <div class="image-box" style="background-image: url('images/pic4.png');"></div>
      <div class="text-content">
        <h2>Md Salauddin Shanto</h2>
         <p>Project member and UI desginer </p> <br>
         <p>Email : syedpunno@gmail.com</p> <br>
         <p>Github : https://github.com/EMP3R0RR</p> <br>
      </div>
    </div>
  </main>

</body>
</html>
