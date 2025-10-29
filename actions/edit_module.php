<?php
require_once('../baseConnect/dbConnect.php');

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid request");
}
$module_id = intval($_GET['id']);

// verify record exists (optional)
$stmt = $conn->prepare("SELECT id FROM module WHERE id = ?");
$stmt->bind_param("i", $module_id);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows !== 1) {
    die("Module not found");
}
$stmt->close();

// redirect to the main page with edit id
header("Location: ../modules.php?edit_id=" . $module_id);
exit;
