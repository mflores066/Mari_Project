<?php
$activePage = 'Menu';
include 'db_conn.php'; // Database connection
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
      grid-column: 1 / 3;
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

    /* Layout */
    .container {
      display: grid;
      grid-template-columns: 1fr 320px;
      gap: 20px;
      max-width: 1100px;
      margin: 80px auto 40px;
      padding: 0 20px;
      min-height: 600px;
    }

    main {
      /* left side - menu content */
    }

    aside.sidebar {
      border: 2px solid #a00;
      border-radius: 20px;
      padding: 15px;
      background-color: #fff;
      box-shadow: 0 0 15px rgba(255, 0, 0, 0.15);
      display: flex;
      flex-direction: column;
      gap: 12px;
    }

    aside.sidebar h3 {
      font-size: 1.4rem;
      margin-top: 0;
      color: #a00;
      text-align: center;
      font-weight: 800;
    }

    .menu-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit,minmax(180px,1fr));
      gap: 20px;
    }
    .menu-item {
      border: 1px solid #ccc;
      border-radius: 15px;
      padding: 15px;
      background-color: #f9f9f9;
      display: flex;
      flex-direction: column;
      align-items: center;
      transition: box-shadow 0.3s ease;
      user-select: none;
      position: relative;
      min-height: 260px;
    }
    .menu-item:hover {
      box-shadow: 0 4px 10px rgba(0,0,0,0.15);
    }
    .menu-item.selected {
      outline: 3px solid #a00;
      background-color: #ffe6e6;
    }
    .menu-image {
      width: 120px;
      height: 90px;
      border-radius: 10px;
      margin-bottom: 10px;
      object-fit: cover;
      background-color: #ddd;
    }
    .menu-name {
      font-weight: bold;
      font-size: 1.1rem;
      margin-bottom: 5px;
      text-align: center;
    }
    .menu-price {
      font-weight: bold;
      color: #a00;
      margin-bottom: 10px;
    }

    /* Quantity controls inside menu item */
    .quantity-controls {
      display: flex;
      gap: 10px;
      align-items: center;
    }
    .qty-btn {
      background: #a00;
      border: none;
      color: white;
      font-weight: bold;
      width: 28px;
      height: 28px;
      border-radius: 6px;
      cursor: pointer;
      user-select: none;
      line-height: 1;
      font-size: 18px;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .qty-btn:hover {
      background: #7a0000;
    }
    .qty {
      font-weight: bold;
      min-width: 24px;
      text-align: center;
      font-size: 1.1rem;
      color: #a00;
    }

    /* Sidebar order list */
    .order-list {
      flex-grow: 1;
      overflow-y: auto;
      max-height: 420px;
    }
    .order-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 1px solid #ddd;
      padding: 8px 0;
    }
    .order-item-name {
      font-weight: 700;
      color: #a00;
      flex: 1 1 auto;
    }
    .order-item-controls {
      display: flex;
      align-items: center;
      gap: 6px;
      flex-shrink: 0;
    }
    .order-item-controls button {
      background: #a00;
      border: none;
      color: white;
      font-weight: bold;
      width: 26px;
      height: 26px;
      border-radius: 6px;
      cursor: pointer;
      user-select: none;
      line-height: 1;
    }
    .order-item-controls button:hover {
      background: #7a0000;
    }
    .order-item-qty {
      font-weight: bold;
      min-width: 24px;
      text-align: center;
    }
    .order-item-price {
      margin-left: 10px;
      font-weight: bold;
      color: #a00;
      min-width: 70px;
      text-align: right;
    }

    .order-total {
      font-weight: 900;
      font-size: 1.3rem;
      text-align: center;
      margin-top: 15px;
      border-top: 2px solid #a00;
      padding-top: 15px;
      color: #a00;
    }

    /* Nav buttons below main */
    .nav-buttons {
      margin-top: 30px;
      display: flex;
      justify-content: space-between;
      max-width: 600px;
      margin-left: 0;
    }
    .nav-buttons button {
      padding: 12px 30px;
      font-size: 1rem;
      font-weight: bold;
      border: none;
      border-radius: 12px;
      cursor: pointer;
      background-color: #a00;
      color: white;
      transition: background-color 0.3s ease;
    }
    .nav-buttons button:disabled {
      background-color: #ccc;
      cursor: default;
    }
    .nav-buttons button:hover:not(:disabled) {
      background-color: #7a0000;
    }

    /* Confirmation message */
    #confirmation-message {
      font-size: 1.5rem;
      text-align: center;
      color: #a00;
      margin-top: 3rem;
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

<header id="page-title">Select Your Delicious Dishes</header>

<div class="container">
  <main>
    <div id="step-menu">
      <div class="menu-grid" id="menuGrid">

        <?php
        // Hardcoded menu items with your image folder and example names/prices
        $menu_items = [
          ['id'=>1, 'name'=>'Shrimp Martini', 'price'=>359.00, 'image'=>'images/1.png'],
          ['id'=>2, 'name'=>'Truffled Camembert Caviar', 'price'=>899.00, 'image'=>'images/2.png'],
          ['id'=>3, 'name'=>'Malpensa Milano', 'price'=>599.00, 'image'=>'images/3.png'],
          ['id'=>4, 'name'=>'La Reine Platter', 'price'=>2199.00, 'image'=>'images/4.png'],
          ['id'=>5, 'name'=>'La Mer Noire Italiene', 'price'=>999.00, 'image'=>'images/5.png'],
          ['id'=>6, 'name'=>'Porc Crevettes Pesto', 'price'=>999.00, 'image'=>'images/6.png'],
          ['id'=>7, 'name'=>'CR7 et Antoine Griezmann', 'price'=>999.00, 'image'=>'images/7.png'],
          ['id'=>8, 'name'=>'Pates Noire', 'price'=>799.00, 'image'=>'images/10.png'],
          ['id'=>9, 'name'=>'L escargot Traditionale', 'price'=>1200.00, 'image'=>'images/11.png'],
          ['id'=>10, 'name'=>'Patatas Bravas', 'price'=>250.00, 'image'=>'images/12.png'],
          ['id'=>11, 'name'=>'British Fish and Chips', 'price'=>369.00, 'image'=>'images/13.png'],
          ['id'=>12, 'name'=>'Plateau de Charcuterie', 'price'=>1899.00, 'image'=>'images/14.png'],
          ['id'=>13, 'name'=>'Poissons Under the Fur Coat', 'price'=>399.00, 'image'=>'images/15.png'],
        ];

        foreach ($menu_items as $item) {
          $id = (int)$item['id'];
          $name = htmlspecialchars($item['name']);
          $price = number_format($item['price'], 2);
          $imageSrc = $item['image'];
          echo <<<HTML
          <div class="menu-item" data-id="$id" data-name="$name" data-price="$price">
            <img src="$imageSrc" alt="$name" class="menu-image" />
            <div class="menu-name">$name</div>
            <div class="menu-price">₱ $price</div>
            <div class="quantity-controls">
              <button class="qty-btn dec" title="Decrease quantity">−</button>
              <span class="qty">0</span>
              <button class="qty-btn inc" title="Increase quantity">+</button>
            </div>
          </div>
HTML;
        }
        ?>

      </div>
    </div>

    <div id="step-confirm" style="display:none;">
      <p id="confirmation-message">Thank you for your order! We appreciate your business.</p>
    </div>

    <div class="nav-buttons">
      <button id="btn-back" disabled>Back</button>
      <button id="btn-next" disabled>Next</button>
    </div>
  </main>

  <aside class="sidebar">
    <h3>Your Order</h3>
    <div class="order-list" id="orderList">
      <p style="font-style: italic; text-align:center; color:#c9753e;">No items selected</p>
    </div>
    <div class="order-total" id="orderTotal">Total: ₱ 0.00</div>
  </aside>
</div>

<script>
  const menuItems = document.querySelectorAll('.menu-item');
  const orderList = document.getElementById('orderList');
  const orderTotal = document.getElementById('orderTotal');
  const btnBack = document.getElementById('btn-back');
  const btnNext = document.getElementById('btn-next');
  const stepMenu = document.getElementById('step-menu');
  const stepConfirm = document.getElementById('step-confirm');
  const pageTitle = document.getElementById('page-title');

  let order = {};
  let currentStep = 0; // 0 = selecting, 1 = confirm/thank you

  function updateSidebar() {
    orderList.innerHTML = '';

    const keys = Object.keys(order);
    if (keys.length === 0) {
      orderList.innerHTML = '<p style="font-style: italic; text-align:center; color:#c9753e;">No items selected</p>';
      btnNext.disabled = true;
      orderTotal.textContent = 'Total: ₱ 0.00';
      return;
    }

    btnNext.disabled = false;

    let total = 0;
    keys.forEach(id => {
      const item = order[id];
      const itemTotal = item.price * item.qty;
      total += itemTotal;

      // Create order item elements
      const itemDiv = document.createElement('div');
      itemDiv.className = 'order-item';

      const nameDiv = document.createElement('div');
      nameDiv.className = 'order-item-name';
      nameDiv.textContent = item.name;

      const controlsDiv = document.createElement('div');
      controlsDiv.className = 'order-item-controls';

      const btnMinus = document.createElement('button');
      btnMinus.textContent = '−';
      btnMinus.title = 'Decrease quantity';
      btnMinus.onclick = () => {
        changeQuantity(id, -1);
      };

      const qtySpan = document.createElement('span');
      qtySpan.className = 'order-item-qty';
      qtySpan.textContent = item.qty;

      const btnPlus = document.createElement('button');
      btnPlus.textContent = '+';
      btnPlus.title = 'Increase quantity';
      btnPlus.onclick = () => {
        changeQuantity(id, 1);
      };

      const priceDiv = document.createElement('div');
      priceDiv.className = 'order-item-price';
      priceDiv.textContent = '₱ ' + itemTotal.toFixed(2);

      controlsDiv.appendChild(btnMinus);
      controlsDiv.appendChild(qtySpan);
      controlsDiv.appendChild(btnPlus);

      itemDiv.appendChild(nameDiv);
      itemDiv.appendChild(controlsDiv);
      itemDiv.appendChild(priceDiv);

      orderList.appendChild(itemDiv);
    });

    orderTotal.textContent = 'Total: ₱ ' + total.toFixed(2);
  }

  function changeQuantity(id, delta) {
    if (!order[id]) return;
    order[id].qty += delta;
    if (order[id].qty < 0) order[id].qty = 0;

    // Update quantity on menu item
    const menuItem = document.querySelector(`.menu-item[data-id="${id}"]`);
    if (menuItem) {
      const qtySpan = menuItem.querySelector('.quantity-controls .qty');
      qtySpan.textContent = order[id].qty;
      if(order[id].qty === 0){
        delete order[id];
        menuItem.classList.remove('selected');
      }
    }

    updateSidebar();
  }

  menuItems.forEach(menuItem => {
    const id = menuItem.getAttribute('data-id');
    const name = menuItem.getAttribute('data-name');
    const price = parseFloat(menuItem.getAttribute('data-price'));

    // Initialize quantity display to 0
    const qtySpan = menuItem.querySelector('.quantity-controls .qty');
    qtySpan.textContent = '0';

    // Plus button
    const btnInc = menuItem.querySelector('.qty-btn.inc');
    btnInc.onclick = (e) => {
      e.preventDefault();
      if (!order[id]) {
        order[id] = { id, name, price, qty: 1 };
        menuItem.classList.add('selected');
      } else {
        order[id].qty++;
      }
      qtySpan.textContent = order[id].qty;
      updateSidebar();
    };

    // Minus button
    const btnDec = menuItem.querySelector('.qty-btn.dec');
    btnDec.onclick = (e) => {
      e.preventDefault();
      if (order[id]) {
        order[id].qty--;
        if (order[id].qty <= 0) {
          delete order[id];
          menuItem.classList.remove('selected');
          qtySpan.textContent = '0';
        } else {
          qtySpan.textContent = order[id].qty;
        }
        updateSidebar();
      }
    };
  });

  btnBack.onclick = () => {
    if (currentStep === 1) {
      // Go back to menu selection
      stepMenu.style.display = 'block';
      stepConfirm.style.display = 'none';
      pageTitle.textContent = 'Select Your Delicious Dishes';
      btnBack.disabled = true;
      btnNext.textContent = 'Next';
      currentStep = 0;
    }
  };

  btnNext.onclick = () => {
    if (currentStep === 0) {
      if (Object.keys(order).length === 0) {
        alert('Please select at least one item before continuing.');
        return;
      }
      // Simulate order submission
      stepMenu.style.display = 'none';
      stepConfirm.style.display = 'block';
      pageTitle.textContent = 'Order Confirmed';
      btnBack.disabled = false;
      btnNext.disabled = true;
      currentStep = 1;

      // Clear order after confirmation
      order = {};
      // Reset menu quantities and UI
      menuItems.forEach(menuItem => {
        menuItem.classList.remove('selected');
        menuItem.querySelector('.quantity-controls .qty').textContent = '0';
      });
      updateSidebar();
    }
  };

  // Initially, no items selected so Next disabled
  btnNext.disabled = true;
</script>

</body>
</html>
