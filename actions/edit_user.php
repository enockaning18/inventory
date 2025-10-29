<?php
require_once('../baseConnect/dbConnect.php');

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid request");
}
$user_id = intval($_GET['id']);

// verify record exists (optional)
$stmt = $conn->prepare("SELECT id FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows !== 1) {
    die("User not found");
}
$stmt->close();

// redirect to the main page with edit id
header("Location: ../users.php?edit_id=" . $user_id);
exit;
