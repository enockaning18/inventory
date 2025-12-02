<?php

require_once('../baseConnect/dbConnect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id                = mysqli_real_escape_string($conn, $_POST['id']);
    $computer          = mysqli_real_escape_string($conn, $_POST['system']);       
    $issue_type        = mysqli_real_escape_string($conn, $_POST['issue_type']);   
    $lab               = mysqli_real_escape_string($conn, $_POST['lab']);          
    $issue_date        = mysqli_real_escape_string($conn, $_POST['issue_date']);   
    $issue_description = mysqli_real_escape_string($conn, $_POST['issue_description']);
    $serial_number     = mysqli_real_escape_string($conn, $_POST['serial_number']);
    $issue_status      = mysqli_real_escape_string($conn, $_POST['issue_status']);
    $sent_to_accra     = mysqli_real_escape_string($conn, $_POST['sent_to_accra']); 
    $device_category     = mysqli_real_escape_string($conn, $_POST['device_category']); 


    // ============================================================
    // UPDATE ISSUE
    // ============================================================
    if (!empty($id)) {

        $stmt = $conn->prepare("UPDATE issues 
                                SET computer = ?, 
                                    issue_type = ?, 
                                    lab = ?, 
                                    issue_date = ?, 
                                    issue_description = ?, 
                                    serial_number = ?, 
                                    issue_status = ?, 
                                    sent_to_accra = ?,  
                                    device_category = ?  
                                WHERE id = ?");

        if ($stmt) {

            $stmt->bind_param(
                "isissssisi",
                $computer,
                $issue_type,
                $lab,
                $issue_date,
                $issue_description,
                $serial_number,
                $issue_status,
                $sent_to_accra,
                $device_category,
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
                            (computer, issue_type, lab, issue_date, issue_description, serial_number, issue_status, sent_to_accra, device_category)
                            VALUES (?,?,?,?,?,?,?,?,?)");

    if ($stmt) {

        $stmt->bind_param(
            "isissssis",
            $computer,
            $issue_type,
            $lab,
            $issue_date,
            $issue_description,
            $serial_number,
            $issue_status,
            $sent_to_accra,
            $device_category
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
