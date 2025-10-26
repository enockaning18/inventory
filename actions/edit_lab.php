<?php
require_once('../baseConnect/dbConnect.php');

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid request");
}
$lab_id = intval($_GET['id']);

// verify record exists (optional)
$stmt = $conn->prepare("SELECT id FROM lab WHERE id = ?");
$stmt->bind_param("i", $lab_id);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows !== 1) {
    die("Lab not found");
}
$stmt->close();

// redirect to the main page with edit id
header("Location: ../labs.php?edit_id=" . $lab_id);
exit;
