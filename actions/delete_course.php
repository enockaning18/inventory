<?php
require_once('../baseConnect/dbConnect.php');

// Enable exceptions globally
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    try {
        $stmt = $conn->prepare("DELETE FROM course WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            header("Location: ../viewcourse.php?status=deleted");
        } else {
            header("Location: ../viewcourse.php?status=notfound");
        }
        exit();

    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1451) {
            header("Location: ../viewcourse.php?status=course_fk_error");
        } else {
            header("Location: ../viewcourse.php?status=error&msg=" . urlencode($e->getMessage()));
        }
        exit();
    }
} else {
    header("Location: ../viewcourse.php?status=invalid");
    exit();
}
?>
