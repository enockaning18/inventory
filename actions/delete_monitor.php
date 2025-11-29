<?php
require_once('../baseConnect/dbConnect.php');

// Enable MySQLi exceptions for cleaner error handling
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    try {
        $stmt = $conn->prepare("DELETE FROM monitor WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            header("Location: ../monitors.php?status=delete");
        } else {
            header("Location: ../monitors.php?status=error");
        }
        exit();

    } catch (mysqli_sql_exception $e) {
        // Check for foreign key constraint violation (error code 1451)
        if ($e->getCode() == 1451) {
            header("Location: ../monitors.php?status=system_fk_error");
        } else {
            // Other SQL error (optional: show message for debugging)
            header("Location: ../monitors.php?status=error&msg=" . urlencode($e->getMessage()));
        }
        exit();
    }

} else {
    header("Location: ../monitors.php?status=error");
    exit();
}
?>
