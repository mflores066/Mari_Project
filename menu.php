<?php
$activePage = 'Menu';
include 'db_conn.php'; // DB connection
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Menu - Order</title>
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
    .top-left-buttons {
      position: fixed;
      top: 15px;
      left: 15px;
      display: flex;
      gap: 15px;
      z-index: 1000;
    }
    .icon-btn {
      background-color: #eee;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      padding: 6px 12px;
      display: flex;
      align-items: center;
      gap: 8px;
      font-weight: bold;
      color: #a00;
      font-size: 14px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.2);
      transition: background-color 0.3s ease;
    }
    .icon-btn svg {
      width: 20px;
      height: 20px;
      fill: currentColor;
    }
    .icon-btn:hover {
      background-color: #a00;
      color: white;
    }
    .main {
      display: flex;
      justify-content: center;
      gap: 20px;
      padding: 20px;
      margin-top: 80px; /* space for fixed buttons */
    }
    .menu-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit,minmax(180px,1fr));
      gap: 20px;
      max-width: 720px;
      width: 100%;
    }
    .menu-item {
      border: 1px solid #ccc;
      border-radius: 15px;
      padding: 15px;
      cursor: pointer;
      background-color: #f9f9f9;
      display: flex;
      flex-direction: column;
      align-items: center;
      transition: box-shadow 0.3s ease;
      user-select: none;
      position: relative;
    }
    .menu-item:hover {
      box-shadow: 0 4px 10px rgba(0,0,0,0.15);
    }
    .menu-item.selected {
      outline: 3px solid #00f;
      background-color: #e0eaff;
    }
    .menu-image {
      width: 120px;
      height: 90px;
      background-color: #ddd;
      border-radius: 10px;
      margin-bottom: 10px;
      object-fit: cover;
    }
    .menu-name {
      font-weight: bold;
      font-size: 1.1rem;
      margin-bottom: 5px;
      text-align: center;
    }
    .menu-desc {
      font-size: 0.85rem;
      color: #555;
      text-align: center;
      margin-bottom: 10px;
      min-height: 40px;
    }
    .menu-price {
      font-weight: bold;
      color: #a00;
      margin-bottom: 10px;
    }
    .right-sidebar {
      width: 250px;
      background-color: #e0e0e0;
      border-radius: 20px;
      padding: 20px;
      display: flex;
      flex-direction: column;
      height: fit-content;
    }
    .sidebar-section {
      margin-bottom: 20px;
    }
    .sidebar-section h4 {
      margin-bottom: 10px;
      border-bottom: 1px solid black;
      padding-bottom: 5px;
      text-align: center;
    }
    #addOrderBtn {
      padding: 12px;
      border-radius: 12px;
      border: none;
      background-color: #a00;
      color: white;
      font-weight: bold;
      cursor: pointer;
      font-size: 16px;
      transition: background-color 0.3s ease;
      width: 100%;
    }
    #addOrderBtn:hover {
      background-color: #800000;
    }
    .order-list {
      list-style: none;
      padding: 0;
      margin: 0;
      max-height: 300px;
      overflow-y: auto;
      border: 1px solid #aaa;
      border-radius: 10px;
      background: white;
    }
    .order-list li {
      padding: 8px 10px;
      border-bottom: 1px solid #ddd;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .order-list li:last-child {
      border-bottom: none;
    }
    .order-remove-btn {
      background: transparent;
      border: none;
      color: #a00;
      cursor: pointer;
      font-size: 16px;
    }
  </style>
</head>
<body>

<div class="top-left-buttons">
  <button id="foodBtn" class="icon-btn" disabled>
    <!-- Fork & Knife icon -->
    <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
      <path d="M7 3h2v12H7V3zm6 0h2v12h-2V3zm-5.5 9.5a1.5 1.5 0 013 0V21h-3v-8.5z"/>
      <path d="M1 21h22v2H1v-2z"/>
    </svg>
    Food
  </button>
  <button id="tableBtn" class="icon-btn" onclick="window.location.href='tablepage.php'">
    <!-- Table icon -->
    <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
      <path d="M3 5v14h18V5H3zm16 12H5V7h14v10z"/>
      <path d="M7 9h2v6H7zm4 0h2v6h-2z"/>
    </svg>
    Table
  </button>
</div>

<header>MENU - ORDER</header>

<div class="main">
  <div class="menu-grid" id="menuGrid">
    <?php
    // Fetch all available menu items
    $sql = "SELECT id, name, description, price, category, is_available FROM menu WHERE is_available = 1 ORDER BY category, name";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $id = (int)$row['id'];
        $name = htmlspecialchars($row['name']);
        $desc = htmlspecialchars($row['description'] ?? '');
        $price = number_format($row['price'], 2);
        $category = htmlspecialchars($row['category'] ?? 'Uncategorized');
        // For image placeholder, can link by id or category
        $imageSrc = "images/menu/" . $id . ".jpg"; // Example path, you can change to your image storage
        if (!file_exists($imageSrc)) {
          $imageSrc = "https://via.placeholder.com/120x90?text=" . urlencode($name);
        }
        echo <<<HTML
        <div class="menu-item" data-id="$id" data-name="$name" data-price="$price">
          <img src="$imageSrc" alt="$name" class="menu-image" />
          <div class="menu-name">$name</div>
          <div class="menu-desc">$desc</div>
          <div class="menu-price">₱ $price</div>
        </div>
HTML;
    }
    ?>
  </div>

  <div class="right-sidebar">
    <div class="sidebar-section">
      <h4>Order Details</h4>
      <ul id="orderList" class="order-list"></ul>
    </div>

    <div class="sidebar-section">
      <button id="addOrderBtn">Add Order</button>
    </div>
  </div>
</div>

<script>
  const menuItems = document.querySelectorAll('.menu-item');
  const orderList = document.getElementById('orderList');
  const addOrderBtn = document.getElementById('addOrderBtn');
  let selectedOrders = [];

  menuItems.forEach(item => {
    item.addEventListener('click', () => {
      const id = item.getAttribute('data-id');
      // Toggle selection
      if (selectedOrders.find(o => o.id === id)) {
        selectedOrders = selectedOrders.filter(o => o.id !== id);
        item.classList.remove('selected');
      } else {
        // Add with qty = 1 initially
        selectedOrders.push({id, name: item.getAttribute('data-name'), price: item.getAttribute('data-price'), qty: 1});
        item.classList.add('selected');
      }
      renderOrderList();
    });
  });

  function renderOrderList() {
    orderList.innerHTML = '';
    selectedOrders.forEach(order => {
      const li = document.createElement('li');
      li.innerHTML = `
        <span>${order.name} (₱${order.price}) x 
          <input type="number" min="1" value="${order.qty}" style="width: 45px" data-id="${order.id}" class="qty-input" />
        </span>
        <button class="order-remove-btn" data-id="${order.id}" title="Remove">&times;</button>
      `;
      orderList.appendChild(li);
    });

    // Add event listeners to qty inputs
    document.querySelectorAll('.qty-input').forEach(input => {
      input.addEventListener('change', e => {
        const id = e.target.getAttribute('data-id');
        let val = parseInt(e.target.value);
        if (isNaN(val) || val < 1) val = 1;
        e.target.value = val;
        const order = selectedOrders.find(o => o.id === id);
        if (order) order.qty = val;
      });
    });

    // Add event listeners to remove buttons
    document.querySelectorAll('.order-remove-btn').forEach(btn => {
      btn.addEventListener('click', e => {
        const id = btn.getAttribute('data-id');
        selectedOrders = selectedOrders.filter(o => o.id !== id);
        // Remove highlight from menu item
        document.querySelector(`.menu-item[data-id="${id}"]`)?.classList.remove('selected');
        renderOrderList();
      });
    });
  }

  addOrderBtn.addEventListener('click', () => {
    if(selectedOrders.length === 0){
      alert('Please select at least one menu item to order.');
      return;
    }

    // Here you can implement sending order to server (AJAX or form submission)
    // For demo: just alert order summary
    let orderSummary = 'Order Summary:\n';
    selectedOrders.forEach(o => {
      orderSummary += `${o.name} x${o.qty} - ₱${(o.price * o.qty).toFixed(2)}\n`;
    });
    alert(orderSummary);

    // Reset selections after ordering
    selectedOrders = [];
    document.querySelectorAll('.menu-item.selected').forEach(item => item.classList.remove('selected'));
    renderOrderList();
  });
</script>

</body>
</html>
