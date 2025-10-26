<?php
require_once('../baseConnect/dbConnect.php');

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid request");
}
$computer_id = intval($_GET['id']);

// verify record exists (optional)
$stmt = $conn->prepare("SELECT id FROM computers WHERE id = ?");
$stmt->bind_param("i", $computer_id);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows !== 1) {
    die("Computer not found");
}
$stmt->close();

// redirect to the main page with edit id
header("Location: ../computers.php?edit_id=" . $computer_id);
exit;
