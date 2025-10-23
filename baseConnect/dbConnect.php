<?php
$servername = "localhost"; 
$username   = "root";       
$password   = "";         
$dbname     = "inventory_db"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    error_log("Connection failed: " . $conn->connect_error);
    die(json_encode(['error' => 'Database connection failed']));
}

$conn->set_charset("utf8");
?>
