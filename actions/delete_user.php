<?php
require_once('../baseConnect/dbConnect.php');

// Ensure database connection
if (!$conn) {
    header("Location: ../users.php?status=dberror");
    exit();
}

// Validate that ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: ../users.php?status=invalid");
    exit();
}

$id = intval($_GET['id']);

try {
    // Prepare delete query
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    if (!$stmt) {
        throw new Exception("prepare_failed");
    }

    $stmt->bind_param("i", $id);
    $stmt->execute();

    // Check affected rows
    if ($stmt->affected_rows > 0) {
        header("Location: ../users.php?status=delete");
        exit();
    } else {
        header("Location: ../users.php?status=notfound");
        exit();
    }

} catch (mysqli_sql_exception $e) {
    
    // check fk issues
    if ($e->getCode() == 1451) {
        header("Location: ../users.php?status=fk_error");
        exit();
    } else {
        header("Location: ../users.php?status=error");
        exit();
    }
} catch (Exception $e) {
    
    header("Location: ../users.php?status=error");
    exit();
} finally {
    if (isset($stmt) && $stmt) {
        $stmt->close();
    }
    $conn->close();
}
?>
