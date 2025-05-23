<?php
include 'db_conn.php';

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['id'], $input['status'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

$tableId = (int)$input['id'];
$status = $conn->real_escape_string($input['status']);

// Validate allowed statuses (for safety)
$allowedStatuses = ['available', 'reserved', 'billed', 'soon'];
if (!in_array($status, $allowedStatuses)) {
    echo json_encode(['success' => false, 'message' => 'Invalid status']);
    exit;
}

// Update table status
$sql = "UPDATE tables SET status='$status' WHERE id=$tableId";
if ($conn->query($sql)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => $conn->error]);
}

$conn->close();
?>
