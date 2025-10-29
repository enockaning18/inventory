<?php
require_once('../baseConnect/dbConnect.php');

// Validate the ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid request");
}

$exam_id = intval($_GET['id']);

// Check if the exam exists
$stmt = $conn->prepare("SELECT id FROM examination WHERE id = ?");
if (!$stmt) {
    die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
}

$stmt->bind_param("i", $exam_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    die("Examination record not found");
}

$stmt->close();

// Redirect to edit page
header("Location: ../examination.php?edit_id=" . $exam_id);
exit;
?>
