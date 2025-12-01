<?php
require_once('../baseConnect/dbConnect.php');

header('Content-Type: application/json; charset=utf-8');

$response = ['serial_number' => '', 'lab_id' => null];

if (isset($_POST['device_id']) && is_numeric($_POST['device_id'])) {
    $device_id = intval($_POST['device_id']);
    
    // Try system table
    $stmt = $conn->prepare("SELECT serial_number, lab FROM `system` WHERE id = ?");
    $stmt->bind_param("i", $device_id);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    
    if ($res) {
        $response['serial_number'] = $res['serial_number'] ?? '';
        $response['lab_id'] = isset($res['lab']) ? (int)$res['lab'] : null;
        echo json_encode($response);
        exit;
    }

    // If not found in system, try monitors table
    $stmt = $conn->prepare("SELECT serial_number, lab FROM monitors WHERE id = ?");
    $stmt->bind_param("i", $device_id);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($res) {
        $response['serial_number'] = $res['serial_number'] ?? '';
        $response['lab_id'] = isset($res['lab']) ? (int)$res['lab'] : null;
    }
}

echo json_encode($response);
