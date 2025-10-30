<?php
require_once('../baseConnect/dbConnect.php');

// Check if request parameters exist
if (!isset($_GET['id']) || !isset($_GET['status'])) {
    header("Location: ../examination.php?status=invalid");
    exit();
}

// Sanitize and validate inputs
$exam_id = intval($_GET['id']);
$status  = trim($_GET['status']);

// Allowed statuses
$allowed_statuses = ['pending', 'approve', 'cancelled'];

if (!in_array(strtolower($status), $allowed_statuses)) {
    header("Location: ../examination.php?status=invalid_status");
    exit();
}

// Prepare the update statement
$stmt = $conn->prepare("UPDATE examination SET status = ? WHERE id = ?");
if (!$stmt) {
    header("Location: ../examination.php?status=error");
    exit();
}

$stmt->bind_param("si", $status, $exam_id);

if ($stmt->execute()) {
    header("Location: ../examination.php?status=exam_status");
} else {
    header("Location: ../examination.php?status=error");
}

$stmt->close();
$conn->close();
?>
