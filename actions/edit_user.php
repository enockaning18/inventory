<?php
require_once('../baseConnect/dbConnect.php');

// Validate the user ID parameter
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: ../users.php?status=invalid");
    exit();
}

$user_id = intval($_GET['id']);

// Check if the user exists
$stmt = $conn->prepare("SELECT id FROM users WHERE id = ?");
if (!$stmt) {
    header("Location: ../users.php?status=error");
    exit();
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// If user not found
if ($result->num_rows === 0) {
    $stmt->close();
    header("Location: ../users.php?status=notfound");
    exit();
}

$stmt->close();

// Redirect back to users page with edit_id param
header("Location: ../users.php?edit_id=" . $user_id);
exit();
?>
