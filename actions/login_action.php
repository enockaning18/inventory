<?php
require_once('../baseConnect/dbConnect.php');
session_start();



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = mysqli_real_escape_string($conn, $_POST['id'] ?? '');
    $email = mysqli_real_escape_string($conn, $_POST['email'] ?? '');
    $usertype = mysqli_real_escape_string($conn, $_POST['usertype'] ?? '');
    $user_key = mysqli_real_escape_string($conn, $_POST['userkey'] ?? '');


    $statement = $conn->prepare("SELECT  email, user_key FROM users WHERE (email =? AND user_key = ? AND user_type = ? )");
    $statement->bind_param('sss',  $email, $user_key, $usertype);
    $statement->execute();
    $statement->store_result();

    if ($statement->num_rows > 0) {
        $query = "SELECT * FROM users WHERE email = '" . $email . "'  ";
        $result = $conn->query($query);
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $_SESSION['id'] = $row['id'];
            $_SESSION['logged_in'] = true;
        }
        header("Location: ../dashboard.php?status=login");
        exit();
    } else {
        header("Location: ../index.php?status=incorrect_password");
        exit();
    }
}
