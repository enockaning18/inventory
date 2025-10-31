<?php
require_once('../baseConnect/dbConnect.php');

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    try {
        // Prepare and execute delete query
        $stmt = $conn->prepare("DELETE FROM lab WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            header("Location: ../labs.php?status=delete");
            exit();
        } else {
            header("Location: ../labs.php?status=error");
            exit();
        }

    } catch (mysqli_sql_exception $e) {
        
        if ($e->getCode() == 1451) {
            header("Location: ../labs.php?status=lab_fk_error");
            exit();
        } else {
            
            error_log("SQL Error: " . $e->getMessage());
            header("Location: ../labs.php?status=error");
            exit();
        }
    }

} else {
    header("Location: ../labs.php?status=error");
    exit();
}
?>
