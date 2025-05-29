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


if ($_SESSION['role'] !== 'student') {
    $profile_pages = [
        'student' => 'StudentProfile.php',
        'teacher' => 'teacherprofile.php',
        'admin' => 'AdminProfile.php'
    ];
    header("Location: " . $profile_pages[$_SESSION['role']] . "?error=Unauthorized access");
    exit();
}


if (!isset($_SESSION['visited_profile']) || $_SESSION['visited_profile'] !== true) {
    header("Location: StudentProfile.php?error=Visit profile first");
    exit();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Payment</title>
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

    .header-left h1 {
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
      box-shadow: 2px 0 5px rgba(0,0,0,0.2);
    }

    .sidediv button {
      width: 100%;
      padding: 0.8rem 1rem;
      border: none;
      border-radius: 8px;
      background-color: #004080;
      color: white;
      font-size: 1.1rem;
      text-align: left;
      cursor: pointer;
      transition: background-color 0.3s, transform 0.2s;
    }

    .sidediv button:hover {
      background-color: #0059b3;
      transform: translateX(10px);
    }

    .maindiv {
      flex: 1;
      padding: 2rem;
      background-color: #fff;
      overflow-y: auto;
    }

    .payment-container {
      background-color: #ffffff;
      padding: 2rem;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    .payment-container h2 {
      color: #003366;
      margin-bottom: 1rem;
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

    .summary {
      margin-top: 2rem;
      padding: 1rem;
      background-color: #f9f9f9;
      border: 1px solid #ddd;
      border-radius: 8px;
      font-size: 1.1rem;
      color: #004080;
    }

    .summary span {
      font-weight: bold;
    }

    label {
      display: block;
      margin-top: 1rem;
      margin-bottom: 0.4rem;
      font-weight: 500;
      color: #003366;
    }

    select, input[type="number"] {
      width: 100%;
      padding: 0.5rem;
      font-size: 1rem;
      margin-bottom: 1rem;
      border: 1px solid #ccc;
      border-radius: 6px;
      color: #003366;
    }

    button {
      padding: 0.8rem 1rem;
      background-color: #004080;
      color: white;
      border: none;
      border-radius: 6px;
      font-size: 1rem;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    button:hover {
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
      <div class="payment-container">
        <h2>Payment Summary</h2>

        <table id="paymentTable" class="rowsandcoms">
          <thead>
            <tr><th>Course Name</th><th>Price</th></tr>
          </thead>
          <tbody id="courseRows">
            
          </tbody>
        </table>

        <div class="summary">
          Total: <span id="totalCost">0</span><br> 
          Paid: <input type="number" id="paidAmount" placeholder="Enter amount paid" />
          Due: <span id="dueAmount">0</span>
        </div>

        <label for="bank">Select Bank:</label>
        <select id="bank">
          <option value="">-- Select Bank --</option>
          <option value="bd">Bank of BD</option>
          <option value="chase">Chase ME</option>
          <option value="hsbc">HSBC</option>
        </select>

        <button onclick="validatePayment()">Pay</button>
      </div>
    </div>
  </div>

  <script>

    
window.onload = function() {
  calculateTotal();
  setupPaidInputListener();
};


function calculateTotal() {
  const table = document.getElementById("paymentTable");
  const totalCostElement = document.getElementById("totalCost");
  const dueAmountElement = document.getElementById("dueAmount");
  
  let total = 0;

  
  const rows = table.tBodies[0].rows;

  for (let i = 0; i < rows.length; i++) {
    
    const priceText = rows[i].cells[1].innerText.trim();
    const price = parseFloat(priceText);

    if (!isNaN(price)) {
      total += price;
    }
  }

  
  totalCostElement.innerText = total.toFixed(2);

  
  dueAmountElement.innerText = total.toFixed(2);
}


function setupPaidInputListener() {
  const paidInput = document.getElementById("paidAmount");
  const totalCostElement = document.getElementById("totalCost");
  const dueAmountElement = document.getElementById("dueAmount");

  paidInput.addEventListener("input", function() {
    const paidValue = parseFloat(paidInput.value);
    const totalValue = parseFloat(totalCostElement.innerText);

    if (!isNaN(paidValue) && paidValue >= 0) {
      let due = totalValue - paidValue;

      
      if (due < 0) due = 0;

      dueAmountElement.innerText = due.toFixed(2);
    } else {
      
      dueAmountElement.innerText = totalValue.toFixed(2);
    }
  })
}


function validatePayment() {
  const bankSelect = document.getElementById("bank");
  const paidInput = document.getElementById("paidAmount");
  const totalCostElement = document.getElementById("totalCost");

  const selectedBank = bankSelect.value;
  const paidValue = parseFloat(paidInput.value);
  const totalValue = parseFloat(totalCostElement.innerText);

  if (selectedBank === "") {
    alert("Please select a bank before proceeding.");
    return false;
  }

  if (isNaN(paidValue) || paidValue < 0) {
    alert("Please enter a valid paid amount.");
    return false;
  }

  if (paidValue > totalValue) {
    alert("Paid amount cannot be more than the total cost.");
    return false;
  }


  const data = {
         bank: selectedBank,
         total_cost: totalValue,
          paid: paidValue,
         due: totalValue - paidValue
};

const xhr = new XMLHttpRequest();
xhr.open("POST", "../Controller/StudentPaymentController.php", true);
xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
xhr.send("json=" + JSON.stringify(data));
xhr.onreadystatechange = function () {
  if (xhr.readyState == 4 && xhr.status == 200) {
    console.log(xhr.responseText); 
    alert("Payment successful!");
  }
};

return true;
}

    
  </script>

</body>
</html>