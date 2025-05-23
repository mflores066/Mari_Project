<?php
$activePage = 'Running order';
include 'db_conn.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Running Order</title>
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

    .controls {
      display: flex;
      justify-content: center;
      gap: 10px;
      margin-bottom: 10px;
    }

    .controls a {
      padding: 8px 16px;
      border: none;
      border-radius: 20px;
      cursor: pointer;
      text-decoration: none;
      color: white;
      background-color: #888;
    }

    .controls a.selected {
      background-color: #a00;
    }

    .status-legend {
      display: flex;
      justify-content: center;
      gap: 15px;
      margin-bottom: 10px;
    }

    .status-legend span {
      display: flex;
      align-items: center;
      gap: 5px;
    }

    .dot {
      width: 15px;
      height: 15px;
      border-radius: 50%;
      display: inline-block;
    }

    .main {
      display: flex;
      justify-content: center;
      gap: 20px;
      padding: 20px;
    }

    .floor-plan {
      display: grid;
      grid-template-columns: repeat(3, 150px);
      gap: 30px;
      max-width: 480px;
    }

    .table {
      background-color: #888; /* gray table */
      border-radius: 20px;
      width: 100px;
      height: 100px;
      position: relative;
      display: flex;
      justify-content: center;
      align-items: center;
      color: white;
      font-weight: bold;
      font-size: 16px;
    }

    .seat {
      width: 30px;
      height: 30px;
      background-color: green;
      border-radius: 50%;
      position: absolute;
    }

    .seat.top { top: -20px; left: 50%; transform: translateX(-50%); }
    .seat.bottom { bottom: -20px; left: 50%; transform: translateX(-50%); }
    .seat.left { left: -20px; top: 50%; transform: translateY(-50%); }
    .seat.right { right: -20px; top: 50%; transform: translateY(-50%); }

    .timestamp {
      position: absolute;
      bottom: 5px;
      font-size: 10px;
      color: white;
    }

    .right-sidebar {
      width: 250px;
      background-color: #e0e0e0;
      border-radius: 20px;
      padding: 20px;
      display: flex;
      flex-direction: column;
    }

    .sidebar-section {
      margin-bottom: 20px;
    }

    .sidebar-section h4 {
      margin-bottom: 10px;
      border-bottom: 1px solid black;
      padding-bottom: 5px;
    }

    .tag {
      display: inline-block;
      padding: 5px 10px;
      margin: 5px 5px 0 0;
      border-radius: 10px;
      color: white;
      font-weight: bold;
    }

    .tag.billed { background-color: green; }

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
      display: flex;
      align-items: center;
      justify-content: center;
      width: 40px;
      height: 40px;
      border-radius: 8px;
      background-color: #eee;
      box-shadow: 0 2px 6px rgba(0,0,0,0.2);
    }

    .top-left-icons a:hover {
      background-color: #a00;
      color: white;
    }

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

<div class="controls">
  <a href="tablepage.php" class="<?php echo $activePage === 'All table' ? 'selected' : ''; ?>">All table</a>
  <a href="reservation.php" class="<?php echo $activePage === 'Reservation' ? 'selected' : ''; ?>">Reservation</a>
  <a href="runningorder.php" class="<?php echo $activePage === 'Running order' ? 'selected' : ''; ?>">Running order</a>
</div>

<div class="status-legend">
  <span><div class="dot available"></div> Available</span>
  <span><div class="dot reserved"></div> Reserved</span>
  <span><div class="dot billed"></div> Billed</span>
  <span><div class="dot soon"></div> Available soon</span>
</div>

<div class="main">
  <div class="floor-plan">
    <?php
    $sql = "SELECT id, table_number, status, available_soon_time FROM tables WHERE status = 'billed' ORDER BY id ASC";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $tableNum = htmlspecialchars($row['table_number']);
        $soonTime = $row['available_soon_time'];
        $status = htmlspecialchars($row['status']);

        echo '<div class="table">';
        echo $tableNum;
        echo '<div class="seat top"></div>';
        echo '<div class="seat bottom"></div>';
        echo '<div class="seat left"></div>';
        echo '<div class="seat right"></div>';
        if ($soonTime) {
            echo '<div class="timestamp">Soon: ' . date('H:i', strtotime($soonTime)) . '</div>';
        }
        echo '</div>';
    }
    ?>
  </div>

  <div class="right-sidebar">
    <div class="sidebar-section">
      <h4>Running Orders</h4>
      <?php
      $count = 0;
      $countSql = "SELECT COUNT(*) AS total FROM tables WHERE status = 'billed'";
      $res = $conn->query($countSql);
      if ($row = $res->fetch_assoc()) {
          $count = $row['total'];
      }
      ?>
      <div class="tag billed">Billed: <?php echo $count; ?></div>
    </div>
  </div>
</div>

<script>
  document.getElementById('logoutBtn').addEventListener('click', () => {
    if (confirm("Are you sure you want to log out?")) {
      window.location.href = 'logout.php';
    }
  });
</script>

</body>
</html>
