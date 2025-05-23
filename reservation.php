<?php
$activePage = 'Reservation'; // active page indicator
include 'db_conn.php'; // database connection
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Table Reservation</title>
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
      user-select: none;
      transition: background-color 0.3s ease;
    }

    .table:hover {
      background-color: #b55;
    }

    .seat {
      width: 30px;
      height: 30px;
      border-radius: 50%;
      position: absolute;
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
      text-align: center;
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

    .available { background-color: #c3f; }
    .reserved { background-color: orange; }
    .billed { background-color: green; }
    .soon { background-color: yellow; color: black; }

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
  </style>
</head>
<body>

<div class="top-left-icons">
  <a href="menu.php" title="Food"><i class="fas fa-utensils"></i></a>
  <a href="tablepage.php" title="Table"><i class="fas fa-table"></i></a>
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
    // Fetch only reserved tables with timestamp
    $sql = "SELECT id, table_number, status, available_soon_time, reserved_at FROM tables WHERE status = 'reserved' ORDER BY table_number";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
          $tableId = (int)$row['id'];
          $tableNum = htmlspecialchars($row['table_number']);
          $status = htmlspecialchars($row['status']);
          $reservedAt = $row['reserved_at'];

          echo '<div class="table" data-id="' . $tableId . '" data-status="' . $status . '" data-number="' . $tableNum . '">';
          echo "Table $tableNum";

          for ($i = 0; $i < 4; $i++) {
              $positionStyle = ($i == 0) ? 'top:-15px; left:45px;' :
                               (($i == 1) ? 'bottom:-15px; left:45px;' :
                               (($i == 2) ? 'left:-15px; top:35px;' : 'right:-15px; top:35px;'));
              echo '<div class="seat ' . $status . '" style="' . $positionStyle . '"></div>';
          }

          if ($reservedAt) {
              $formatted = date('M d, Y – H:i', strtotime($reservedAt));
              echo '<div class="timestamp" style="top: 2px; right: 5px; position: absolute; font-size: 11px; color: black;">Reserved: ' . $formatted . '</div>';
          }

          echo '</div>';
      }
    } else {
      echo '<p style="grid-column: 1 / -1; text-align: center;">No tables are reserved currently.</p>';
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
  </div>
</div>

<script>
// When a reserved table is clicked, show a prompt to confirm billing
document.querySelectorAll('.table').forEach(table => {
  table.addEventListener('click', () => {
    const tableId = table.getAttribute('data-id');
    const tableNumber = table.getAttribute('data-number');
    
    if (confirm(`Mark Table ${tableNumber} as Billed?`)) {
      // Send AJAX request to update status
      fetch('update_table_status.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id: tableId, status: 'billed' })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          alert(`Table ${tableNumber} marked as Billed.`);
          // Redirect to running order page after update
          window.location.href = 'runningorder.php';
        } else {
          alert('Failed to update status: ' + data.message);
        }
      })
      .catch(() => {
        alert('Error updating status.');
      });
    }
  });
});
</script>

</body>
</html>
