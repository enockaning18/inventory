<?php
require_once('../baseConnect/dbConnect.php');

// Validate and get the ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid request");
}

$exam_id = intval($_GET['id']);

// Verify that the record exists
$stmt = $conn->prepare("SELECT id FROM examination WHERE id = ?");
$stmt->bind_param("i", $exam_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    die("Examination record not found");
}

$stmt->close();

// Redirect to the main page with edit ID
header("Location: ../examination.php?edit_id=" . $exam_id);
exit;
?>
