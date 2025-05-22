<?php
include 'db_conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tableId = isset($_POST['id']) ? (int)$_POST['id'] : 0;

    if ($tableId <= 0) {
        echo "Invalid table ID.";
        exit;
    }

    // Check if table is currently available
    $stmt = $conn->prepare("SELECT status FROM tables WHERE id = ?");
    $stmt->bind_param("i", $tableId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "Table not found.";
        exit;
    }

    $row = $result->fetch_assoc();
    if ($row['status'] !== 'available') {
        echo "Table is not available for reservation.";
        exit;
    }

    // Update status to 'reserved'
    $update = $conn->prepare("UPDATE tables SET status = 'reserved' WHERE id = ?");
    $update->bind_param("i", $tableId);

    if ($update->execute()) {
        echo "Table reserved successfully.";
    } else {
        echo "Failed to reserve the table.";
    }

    $stmt->close();
    $update->close();
    $conn->close();
} else {
    echo "Invalid request method.";
}
?>
