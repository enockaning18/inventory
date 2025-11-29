<?php
require_once('../baseConnect/dbConnect.php');

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid request");
}
$system_id = intval($_GET['id']);

// verify record exists (optional)
$stmt = $conn->prepare("SELECT id FROM `system` WHERE id = ?");
$stmt->bind_param("i", $system_id);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows !== 1) {
    die("System not found");
}
$stmt->close();

// redirect to the main page with edit id
header("Location: ../system.php?edit_id=" . $system_id);
exit;
