<?php
ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);


require_once('../baseConnect/dbConnect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id                = mysqli_real_escape_string($conn, $_POST['id']);
    $device_type       = mysqli_real_escape_string($conn, $_POST['device_type']);
    $issue_type        = mysqli_real_escape_string($conn, $_POST['issue_type']);
    $lab               = mysqli_real_escape_string($conn, $_POST['lab']);
    $issue_date        = mysqli_real_escape_string($conn, $_POST['issue_date']);
    $issue_description = mysqli_real_escape_string($conn, $_POST['issue_description']);
    $serial_number     = mysqli_real_escape_string($conn, $_POST['serial_number']);
    $issue_status      = mysqli_real_escape_string($conn, $_POST['issue_status']);
    $sent_to_accra     = mysqli_real_escape_string($conn, $_POST['sent_to_accra']);
    $device_category   = mysqli_real_escape_string($conn, $_POST['device_category']);
    $resolved_type     = isset($_POST['resolved_type']) ? mysqli_real_escape_string($conn, $_POST['resolved_type']) : null;

    // Determine which column to use based on category
    $system_id = ($device_category == 'system') ? $device_type : null;
    $monitor_id = ($device_category == 'monitor') ? $device_type : null;


    // ============================================================
    // UPDATE ISSUE
    // ============================================================
    if (!empty($id)) {

        $stmt = $conn->prepare("UPDATE issues 
                                SET `system` = ?, 
                                    monitor = ?, 
                                    issue_type = ?, 
                                    lab = ?, 
                                    issue_date = ?, 
                                    issue_description = ?, 
                                    serial_number = ?, 
                                    issue_status = ?, 
                                    sent_to_accra = ?,  
                                    device_category = ?,
                                    resolved_type = ?  
                                WHERE id = ?");

        if ($stmt) {

            $stmt->bind_param(
                "iisissssissi",
                $system_id,
                $monitor_id,
                $issue_type,
                $lab,
                $issue_date,
                $issue_description,
                $serial_number,
                $issue_status,
                $sent_to_accra,
                $device_category,
                $resolved_type,
                $id
            );

            if ($stmt->execute()) {
                header("Location: ../issues_list.php?status=update");
            } else {
                header("Location: ../issues_list.php?status=error");
            }

            $stmt->close();
            exit();
        }

        header("Location: ../issues.php?status=error");
        exit();
    }


    // ============================================================
    // INSERT ISSUE
    // ============================================================

    $stmt = $conn->prepare("INSERT INTO issues 
                            (`system`, monitor, issue_type, lab, issue_date, issue_description, serial_number, issue_status, sent_to_accra, device_category, resolved_type)
                            VALUES (?,?,?,?,?,?,?,?,?,?,?)");
    
    if ($stmt) {

        $stmt->bind_param(
            "iisisssssss",
            $system_id,
            $monitor_id,
            $issue_type,
            $lab,
            $issue_date,
            $issue_description,
            $serial_number,
            $issue_status,
            $sent_to_accra,
            $device_category,
            $resolved_type
        );

        if ($stmt->execute()) {
            header("Location: ../issues.php?status=save");
        } else {
            header("Location: ../issues.php?status=error");
        }

        $stmt->close();
        exit();
    }

    header("Location: ../issues.php?status=error");
    exit();
}
?>
