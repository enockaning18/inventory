<?php
require_once('../baseConnect/dbConnect.php');

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid request");
}
$brand_id = intval($_GET['id']);

// verify record exists (optional)
$stmt = $conn->prepare("SELECT id FROM brand WHERE id = ?");
$stmt->bind_param("i", $brand_id);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows !== 1) {
    die("Brand not found");
}
$stmt->close();

// redirect to the main page with edit id
header("Location: ../brands.php?edit_id=" . $brand_id);
exit;
