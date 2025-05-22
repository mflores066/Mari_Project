<?php
session_start();
include 'db_conn.php';

// Handle table reservation
if (isset($_POST['reserve_table'])) {
    $table_id = intval($_POST['table_id']);

    $update_query = "UPDATE tables SET status = 'Reserved' WHERE id = $table_id AND status = 'Available'";
    if (mysqli_query($conn, $update_query) && mysqli_affected_rows($conn) > 0) {
        header("Location: reservation.php?success=1");
        exit();
    } else {
        header("Location: reservation.php?error=1");
        exit();
    }
}

// Get all tables
$table_query = "SELECT * FROM tables ORDER BY table_number ASC";
$tables = mysqli_query($conn, $table_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Reserve a Table - Les Boissons</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" />
  <style>
    body {
      background-color: #f8f9fa;
      font-family: 'Poppins', sans-serif;
    }
    .table-card {
      transition: transform 0.2s ease;
    }
    .table-card:hover {
      transform: scale(1.03);
    }
    .reserved {
      background-color: #adb5bd !important;
      color: white;
    }
  </style>
</head>
<body>

<div class="container py-5">
  <div class="text-center mb-4">
    <h2>Reserve a Table at Les Boissons</h2>
    <p class="text-muted">Click "Reserve" on an available table below</p>
  </div>

  <?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success text-center">Table reserved successfully!</div>
  <?php elseif (isset($_GET['error'])): ?>
    <div class="alert alert-danger text-center">Failed to reserve the table. It might already be reserved.</div>
  <?php endif; ?>

  <div class="row">
    <?php while ($row = mysqli_fetch_assoc($tables)): ?>
      <div class="col-md-3 mb-4">
        <div class="card table-card text-center <?= $row['status'] === 'Reserved' ? 'reserved' : '' ?>">
          <div class="card-body">
            <h5 class="card-title">Table <?= htmlspecialchars($row['table_number']) ?></h5>
            <p>Status: <strong><?= htmlspecialchars($row['status']) ?></strong></p>
            <?php if ($row['status'] === 'Available'): ?>
              <form method="POST">
                <input type="hidden" name="table_id" value="<?= $row['id'] ?>">
                <button type="submit" name="reserve_table" class="btn btn-success">Reserve</button>
              </form>
            <?php else: ?>
              <button class="btn btn-secondary" disabled>Already Reserved</button>
            <?php endif; ?>
          </div>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
