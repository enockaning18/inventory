<?php

// require your database connection
require_once "../../baseConnect/dbConnect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $donor_id           = mysqli_real_escape_string($conn, $_POST['id']);
    $donor_firstname    = mysqli_real_escape_string($conn, $_POST['firstname']);
    $donor_lastname     = mysqli_real_escape_string($conn, $_POST['lastname']);
    $donor_phone        = mysqli_real_escape_string($conn, $_POST['phone']);
    $donation_type      = mysqli_real_escape_string($conn, $_POST['donation_type']);
    $item_donated       = mysqli_real_escape_string($conn, $_POST['item_donated']);
    $donation_date      = mysqli_real_escape_string($conn, $_POST['donation_date']);


    // check whether donation id exists
    if ($donor_id != "") {

        // update the record
        $stmt = $conn->prepare("UPDATE donationfrm SET donor_firstname = ?, donor_lastname = ?, donor_phone = ?, donation_type = ?, item_donated = ?, donation_date = ? WHERE donation_id = ?");

        if ($stmt) {
            $stmt->bind_param("ssssssi", $donor_firstname, $donor_lastname, $donor_phone, $donation_type, $item_donated, $donation_date, $donor_id);

            if ($stmt->execute()) {
                header("Location: ../donations.php?status=update");
                exit();
            } else {
                header("Location: ../donations.php?status=error");
                exit();
            }

            $stmt->close();
        } else {
            // SQL error (wrong table/columns)
            header("Location: ../donations.php?status=error");
            exit();
        }
    } else {

        // insert a new record
        $stmt = $conn->prepare("INSERT INTO donationfrm (donor_firstname, donor_lastname, donor_phone, donation_type, item_donated, donation_date) 
                VALUES (?, ?, ?, ?, ?, ?)");

        if ($stmt) {
            $stmt->bind_param("ssssss", $donor_firstname, $donor_lastname, $donor_phone, $donation_type, $item_donated, $donation_date);

            if ($stmt->execute()) {
                header("Location: ../donations.php?status=save");
                exit();
            } else {
                header("Location: ../donations.php?status=error");
                exit();
            }

            $stmt->close();
        } else {
            // SQL error (wrong table/columns)
            header("Location: ../donations.php?status=error");
            exit();
        }
    }
}
