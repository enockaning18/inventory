<?php
require_once('../baseConnect/dbConnect.php');
session_start();



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id         = mysqli_real_escape_string($conn, $_POST['id'] ?? '');
    $email      = mysqli_real_escape_string($conn, $_POST['email'] ?? '');
    $userkey    = trim($conn, $_POST['userkey'] ?? '');
    $userkey    = password_hash($userkey, PASSWORD_BCRYPT);


    $statement = $conn->prepare("SELECT  email, user_key FROM users WHERE (email =? AND user_key = ?)");
    $statement->bind_param('ss',  $email, $userkey);
    $statement->execute();
    $statement->store_result();

    if ($statement->num_rows > 0) {
        $query = "SELECT * FROM users WHERE email = '" . $email . "'  ";
        $result = $conn->query($query);
        if ($result && $result->num_rows > 0) 
            {
                $row = $result->fetch_assoc();
                $_SESSION['id'] = $row['id'];
                $_SESSION['logged_in'] = true;
            }
            header("Location: ../dashboard.php?status=login");
            exit();
            } 
        else {
            header("Location: ../index.php?status=incorrect_password");
            exit();
        }
}
