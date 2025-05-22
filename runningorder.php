<?php
$activePage = 'Running order';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Table Reservation - Running Order</title>

  <!-- Add FontAwesome CSS here correctly -->
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
  />

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
  </style>
</head>
<body>

<!-- Fixed top-left icon buttons container -->
<div class="top-left-icons">
  <a href="menu.php" title="Food">
    <i class="fas fa-utensils"></i>
  </a>
  <a href="tablepage.php" title="Table">
    <i class="fas fa-table"></i>
  </a>
</div>

<header>TABLE RESERVATION</header>

<div class="controls">
  <a href="tablepage.php" class="<?= $activePage === 'All table' ? 'selected' : '' ?>">All table</a>
  <a href="reservation.php" class="<?= $activePage === 'Reservation' ? 'selected' : '' ?>">Reservation</a>
  <a href="runningorder.php" class="<?= $activePage === 'Running order' ? 'selected' : '' ?>">Running order</a>
</div>

<div class="status-legend">
  <span><div class="dot available"></div> Available</span>
  <span><div class="dot reserved"></div> Reserved</span>
  <span><div class="dot billed"></div> Billed</span>
  <span><div class="dot soon"></div> Available soon</span>
</div>

<div class="main">
  <div class="floor-plan">

    <!-- Billed -->
    <div class="table">T-07
      <div class="seat billed" style="top:-15px; left: 45px;"></div>
      <div class="seat billed" style="bottom:-15px; left: 45px;"></div>
      <div class="seat billed" style="left:-15px; top: 35px;"></div>
      <div class="seat billed" style="right:-15px; top: 35px;"></div>
    </div>
    <div class="table">T-08
      <div class="seat billed" style="top:-15px; left: 45px;"></div>
      <div class="seat billed" style="bottom:-15px; left: 45px;"></div>
      <div class="seat billed" style="left:-15px; top: 35px;"></div>
      <div class="seat billed" style="right:-15px; top: 35px;"></div>
    </div>
    <div class="table">T-09
      <div class="seat billed" style="top:-15px; left: 45px;"></div>
      <div class="seat billed" style="bottom:-15px; left: 45px;"></div>
      <div class="seat billed" style="left:-15px; top: 35px;"></div>
      <div class="seat billed" style="right:-15px; top: 35px;"></div>
    </div>

    <!-- Available Soon -->
    <div class="table">T-10
      <div class="seat soon" style="top:-15px; left: 45px;"></div>
      <div class="seat soon" style="bottom:-15px; left: 45px;"></div>
      <div class="seat soon" style="left:-15px; top: 35px;"></div>
      <div class="seat soon" style="right:-15px; top: 35px;"></div>
      <div class="timestamp">Soon: 10:30</div>
    </div>
    <div class="table">T-11
      <div class="seat soon" style="top:-15px; left: 45px;"></div>
      <div class="seat soon" style="bottom:-15px; left: 45px;"></div>
      <div class="seat soon" style="left:-15px; top: 35px;"></div>
      <div class="seat soon" style="right:-15px; top: 35px;"></div>
      <div class="timestamp">Soon: 11:00</div>
    </div>
    <div class="table">T-12
      <div class="seat soon" style="top:-15px; left: 45px;"></div>
      <div class="seat soon" style="bottom:-15px; left: 45px;"></div>
      <div class="seat soon" style="left:-15px; top: 35px;"></div>
      <div class="seat soon" style="right:-15px; top: 35px;"></div>
      <div class="timestamp">Soon: 11:30</div>
    </div>
  </div>

  <div class="right-sidebar">
    <div class="sidebar-section">
      <h4>Table Status Summary</h4>
      <div class="tag billed">Billed: 3</div>
      <br>
      <div class="tag billed">T-07</div>
      <div class="tag billed">T-08</div>
      <div class="tag billed">T-09</div>
      <br>
      <div class="tag soon">Available Soon: 3</div>
      <br>
      <div class="tag soon">T-10</div>
      <div class="tag soon">T-11</div>
      <div class="tag soon">T-12</div>
    </div>
  </div>
</div>

</body>
</html>
