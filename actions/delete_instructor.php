<?php
require_once('../baseConnect/dbConnect.php');

// Enable exception mode for MySQLi
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    try {
        $stmt = $conn->prepare("DELETE FROM instructors WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            header("Location: ../instructors.php?status=delete");
        } else {
            header("Location: ../instructors.php?status=error");
        }
        exit();

    } catch (mysqli_sql_exception $e) {
        
        if ($e->getCode() == 1451) {
            header("Location: ../instructors.php?status=instructor_fk_error");
        } else {
        
            header("Location: ../instructors.php?status=error&msg=" . urlencode($e->getMessage()));
        }
        exit();
    }
} else {
    header("Location: ../instructors.php?status=error");
    exit();
}
?>
