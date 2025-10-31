<?php
require_once('../baseConnect/dbConnect.php');

// Enable MySQLi exceptions globally
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    try {
        $stmt = $conn->prepare("DELETE FROM module WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            header("Location: ../modules.php?status=delete");
        } else {
            header("Location: ../modules.php?status=error");
        }
        exit();

    } catch (mysqli_sql_exception $e) {
        // Check for foreign key constraint violation (code 1451)
        if ($e->getCode() == 1451) {
            header("Location: ../modules.php?status=module_fk_error");
        } else {
            header("Location: ../modules.php?status=error&msg=" . urlencode($e->getMessage()));
        }
        exit();
    }

} else {
    header("Location: ../modules.php?status=error");
    exit();
}
?>
