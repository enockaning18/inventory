<?php
require_once('../baseConnect/dbConnect.php');

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid request");
}
$monitor_id = intval($_GET['id']);

// verify record exists (optional)
$stmt = $conn->prepare("SELECT id FROM `monitor` WHERE id = ?");
$stmt->bind_param("i", $monitor_id);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows !== 1) {
    die("System not found");
}
$stmt->close();

// redirect to the main page with edit id
header("Location: ../monitors.php?edit_id=" . $monitor_id);
exit;
