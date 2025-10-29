
<?php
require_once('../baseConnect/dbConnect.php');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); 

    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: ../users.php?status=delete");
        exit();
    } else {
        header("Location: ../users.php?status=error");
        exit();
    }
} 
else {
    // SQL error (wrong table/columns)
    header("Location: ../users.php?status=error");
    exit();
}
