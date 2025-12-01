<?php
require_once('../baseConnect/dbConnect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['issue_id'])) {
    $issue_id = intval($_POST['issue_id']);
    $issue_status = trim($_POST['issue_status']);
    $date_returned = trim($_POST['date_returned']);
    $resolved_type     = isset($_POST['resolved_type']) ? trim($_POST['resolved_type']) : null;

    if($issue_status !== 'Resolved') {
        //set resolved type to null if not resolved
        $resolved_type = null; 
    }

    $stmt = $conn->prepare("UPDATE issues SET issue_status = ?, resolved_type = ?, date_returned = ? WHERE id = ?");
    $stmt->bind_param("sssi", $issue_status, $resolved_type, $date_returned, $issue_id);

    if ($stmt->execute()) {
        header("Location: ../issues_list.php?status=issue_update");
        // $_SESSION['success'] = 'Issue status updated successfully';
    } else {
        header("Location: ../issues_list.php?status=error");
    }
    $stmt->close();


    exit;
}
