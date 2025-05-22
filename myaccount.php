<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: Loginpage.php');
    exit;
}

include 'db_conn.php';

$userId = $_SESSION['user_id'];

$sql = "SELECT username, email, full_name FROM admin WHERE id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
}
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "User not found.";
    exit;
}

$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>My Account</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background-color: #fff;
    }

    header {
      text-align: center;
      padding: 20px;
      font-size: 2rem;
      font-weight: bold;
    }

    /* Top-left icons styling */
    .top-left-icons {
      position: fixed;
      top: 15px;
      left: 15px;
      display: flex;
      gap: 15px;
      z-index: 1000;
    }

    .top-left-icons a {
      color: #a00;
      font-size: 26px;
      text-decoration: none;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      width: 40px;
      height: 40px;
      border-radius: 8px;
      background-color: #eee;
      box-shadow: 0 2px 6px rgba(0,0,0,0.2);
      transition: background-color 0.3s ease;
    }

    .top-left-icons a:hover {
      background-color: #a00;
      color: white;
    }

    /* Top-right buttons */
    .top-right-buttons {
      position: fixed;
      top: 15px;
      right: 15px;
      display: flex;
      gap: 10px;
      z-index: 1000;
    }

    .top-right-buttons button {
      padding: 6px 12px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-weight: bold;
      font-size: 14px;
      background-color: #a00;
      color: white;
      transition: background-color 0.3s ease;
    }

    .top-right-buttons button:hover {
      background-color: #800000;
    }

    /* Main container for the content */
    .main {
      max-width: 480px;
      margin: 80px auto 40px; /* margin top to avoid fixed header/buttons */
      background-color: #e0e0e0;
      border-radius: 20px;
      padding: 30px 25px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      display: flex;
      flex-direction: column;
      gap: 20px;
    }

    .account-title {
      font-size: 1.8rem;
      font-weight: bold;
      text-align: center;
      margin-bottom: 10px;
    }

    .info {
      background: white;
      border-radius: 10px;
      padding: 15px 20px;
      font-weight: bold;
      color: #333;
      box-shadow: 0 1px 5px rgba(0,0,0,0.1);
    }

    .info .label {
      color: #555;
      font-weight: normal;
    }

    .logout-btn {
      background-color: #a00;
      color: white;
      border: none;
      border-radius: 12px;
      font-weight: bold;
      padding: 12px;
      cursor: pointer;
      transition: background-color 0.3s ease;
      text-align: center;
      text-decoration: none;
      user-select: none;
    }
    .logout-btn:hover {
      background-color: #800000;
    }
  </style>
</head>
<body>

  <div class="top-left-icons">
    <a href="menu.php" title="Food"><i class="fas fa-utensils"></i></a>
    <a href="tablepage.php" title="Table"><i class="fas fa-table"></i></a>
  </div>

  <div class="top-right-buttons">
    <button onclick="location.href='myaccount.php'">My Account</button>
    <button id="logoutBtn">Logout</button>
  </div>

  <header>TABLE RESERVATION</header>

  <main class="main">
    <div class="account-title">My Account</div>

    <div class="info">
      <span class="label">Username: </span> <?php echo htmlspecialchars($user['username']); ?>
    </div>
    <div class="info">
      <span class="label">Full Name: </span> <?php echo htmlspecialchars($user['full_name']); ?>
    </div>
    <div class="info">
      <span class="label">Email: </span> <?php echo htmlspecialchars($user['email']); ?>
    </div>

    <button class="logout-btn" onclick="logout()">Logout</button>
  </main>

  <script>
    // Logout confirmation
    document.getElementById('logoutBtn').addEventListener('click', () => {
      if (confirm("Are you sure you want to log out?")) {
        window.location.href = 'logout.php';
      }
    });

    function logout() {
      if (confirm("Are you sure you want to log out?")) {
        window.location.href = 'logout.php';
      }
    }
  </script>

</body>
</html>
