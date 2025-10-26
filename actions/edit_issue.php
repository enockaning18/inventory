
<?php
require_once('../baseConnect/dbConnect.php');

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid request");
}
$issue_id = intval($_GET['id']);

// verify record exists (optional)
$stmt = $conn->prepare("SELECT id FROM issues WHERE id = ?");
$stmt->bind_param("i", $issue_id);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows !== 1) {
    die("Issue not found");
}
$stmt->close();

// redirect to the main page with edit id
header("Location: ../issues.php?edit_id=" . $issue_id);
exit;
