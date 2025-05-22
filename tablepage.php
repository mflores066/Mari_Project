<?php
$activePage = 'All table';
include 'db_conn.php'; // Include DB connection
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Table Reservation - All Table</title>
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
      justify-content: center; /* center horizontally */
      gap: 20px;
      padding: 20px;
    }

    .floor-plan {
      display: grid;
      grid-template-columns: repeat(3, 150px);
      gap: 30px;
      /* allow floor-plan to shrink but not grow too big */
      max-width: 480px; 
    }

    .table {
      background-color: #888;
      border-radius: 20px;
      width: 120px;
      height: 100px;
      position: relative;
      display: flex;
      justify-content: center;
      align-items: center;
      color: white;
      font-weight: bold;
      cursor: pointer;
    }

    .seat {
      width: 30px;
      height: 30px;
      border-radius: 50%;
      position: absolute;
    }

    .selected-table {
      outline: 3px solid #00f;
    }

    /* Sidebar styles from your provided code */
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

    .available { background-color: #c3f; }
    .reserved { background-color: orange; }
    .billed { background-color: green; }
    .soon { background-color: yellow; color: black; }

    .timestamp {
      position: absolute;
      bottom: 2px;
      font-size: 10px;
      color: black;
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

    .selected-table {
      outline: 3px solid #00f;
      border-radius: 10px;
    }

    /* Added top-right buttons style */
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

<!-- Added top-right buttons -->
<div class="top-right-buttons">
  <button onclick="location.href='myaccount.php'">My Account</button>
  <button id="logoutBtn" onclick="location.href='Loginpage.php'">Logout</button>
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
    $sql = "SELECT id, table_number, status, available_soon_time FROM tables";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $tableId = (int)$row['id'];
        $tableNum = htmlspecialchars($row['table_number']);
        $status = htmlspecialchars($row['status']);
        $soonTime = $row['available_soon_time'];

        echo '<div class="table" data-id="' . $tableId . '" data-status="' . $status . '" data-number="' . $tableNum . '">';
        echo $tableNum;
        for ($i = 0; $i < 4; $i++) {
              $positionStyle = ($i == 0) ? 'top:-15px; left:45px;' :
              (($i == 1) ? 'bottom:-15px; left:45px;' :
              (($i == 2) ? 'left:-15px; top:35px;' : 'right:-15px; top:35px;'));
              echo '<div class="seat ' . $status . '" style="' . $positionStyle . '"></div>';
                                  }
        if ($status === 'soon' && $soonTime) {
            echo '<div class="timestamp">Soon: ' . date('H:i', strtotime($soonTime)) . '</div>';
        }
        echo '</div>';
    }
    ?>
  </div>

  <div class="right-sidebar">
    <div class="sidebar-section">
      <h4>Table Status Summary</h4>
      <?php
      $counts = ['available' => 0, 'reserved' => 0, 'billed' => 0, 'soon' => 0];
      $countQuery = "SELECT status, COUNT(*) as count FROM tables GROUP BY status";
      $res = $conn->query($countQuery);
      while ($r = $res->fetch_assoc()) {
          $statusKey = $r['status'];
          if (isset($counts[$statusKey])) {
              $counts[$statusKey] = $r['count'];
          }
      }
      ?>
      <div class="tag available">Available: <?php echo $counts['available']; ?></div>
      <div class="tag reserved">Reserved: <?php echo $counts['reserved']; ?></div>
      <div class="tag billed">Billed: <?php echo $counts['billed']; ?></div>
      <div class="tag soon">Available Soon: <?php echo $counts['soon']; ?></div>
    </div>

    <div class="sidebar-section">
      <button id="reserveBtn" style="padding: 10px; border-radius: 10px; border: none; background-color: #a00; color: white; font-weight: bold; cursor: pointer;">
        Reserve
      </button>
    </div>
  </div>
</div>

<script>
  const tables = document.querySelectorAll('.floor-plan .table');
  let selectedTable = null;

  tables.forEach(table => {
    table.addEventListener('click', () => {
      const status = table.getAttribute('data-status');
      if (status === 'available') {
        if (selectedTable) {
          selectedTable.classList.remove('selected-table');
        }
        selectedTable = table;
        selectedTable.classList.add('selected-table');
      } else {
        alert("You can't reserve this table.");
      }
    });
  });

  document.getElementById('reserveBtn').addEventListener('click', () => {
    if (!selectedTable) {
      alert("Please select an available table to reserve.");
      return;
    }

    const tableId = selectedTable.getAttribute('data-id');

    fetch('reserve_table.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: 'id=' + encodeURIComponent(tableId)
    })
    .then(res => res.text())
    .then(data => {
      alert(data);
      window.location.reload(); // refresh to reflect updated table status
    })
    .catch(error => {
      alert('Reservation failed: ' + error);
    });
  });

  // Logout confirmation
  document.getElementById('logoutBtn').addEventListener('click', () => {
    if (confirm("Are you sure you want to log out?")) {
      window.location.href = 'logout.php'; // Redirect to your logout handling script
    }
  });
</script>

</body>
</html>
