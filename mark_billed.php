<?php
include 'db_conn.php';

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['tableId'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid data']);
    exit;
}

$tableId = (int)$data['tableId'];

// Update the table status to 'billed'
$sql = "UPDATE tables SET status = 'billed' WHERE id = $tableId AND status = 'reserved'";
if ($conn->query($sql) === TRUE) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => $conn->error]);
}
?>
