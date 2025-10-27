
<?php
require_once('../baseConnect/dbConnect.php');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); 

    $stmt = $conn->prepare("DELETE FROM course WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: ../labs.php?status=delete");
        exit();
    } else {
        header("Location: ../labs.php?status=error");
        exit();
    }
} 
else {
    // SQL error (wrong table/columns)
    header("Location: ../labs.php?status=error");
    exit();
}
