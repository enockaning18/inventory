
<?php
// require_once('../baseConnect/dbConnect.php');

// if (isset($_GET['id'])) {
//     $id = intval($_GET['id']); 

//     $stmt = $conn->prepare("DELETE FROM lab WHERE id = ?");
//     $stmt->bind_param("i", $id);

//     if ($stmt->execute()) {
//         header("Location: ../labs.php?status=delete");
//         exit();
//     } else {
//         header("Location: ../labs.php?status=error");
//         exit();
//     }
// } 
// else {
//     // SQL error (wrong table/columns)
//     header("Location: ../labs.php?status=error");
//     exit();
// }


require_once('../baseConnect/dbConnect.php');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    try {
        $stmt = $conn->prepare("DELETE FROM lab WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        // Check if any rows were affected
        if ($stmt->affected_rows > 0) {
            header("Location: ../labs.php?status=deleted");
            exit();
        } else {
            header("Location: ../labs.php?status=notfound");
            exit();
        }
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1451) {
            header("Location: ../labs.php?status=fk_error");
            exit();
        } else {
            header("Location: ../labs.php?status=error");
            exit();
        }
    }
} else {
    header("Location: ../labs.php?status=invalid");
    exit();
}
?>