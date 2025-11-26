<?php
require_once('../baseConnect/dbConnect.php');

if (isset($_POST['issue_id']) && is_numeric($_POST['issue_id'])) {
    $issue_id = intval($_POST['issue_id']);
    $stmt = $conn->prepare("SELECT * FROM issues WHERE id = ?");
    $stmt->bind_param("i", $issue_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    
    if ($result) {
        echo json_encode($result);
    } else {
        echo json_encode(['error' => 'Issue not found']);
    }
}
?>
