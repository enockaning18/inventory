<?php
require_once('actions/start_session.php');
require_once('alert.php');
require_once('baseConnect/dbConnect.php');

// Count total monitoers
$stmt = $conn->prepare("SELECT COUNT(*) AS total_monitors FROM monitor");
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$totalMonitors = $row['total_monitors'] ?? 0;


// Count system
$stmt = $conn->prepare("SELECT COUNT(*) AS total_systems FROM `system`");
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$totalSystems  = $row['total_systems'] ?? 0;


// Count computers
$stmt = $conn->prepare("SELECT 
                        (SELECT COUNT(*) FROM monitor) +
                        (SELECT COUNT(*) FROM system) 
                        AS total_computers;");
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$totalComputers  = $row['total_computers'] ?? 0;




$stmt->close();
