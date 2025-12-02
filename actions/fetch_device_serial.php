<?php
require_once('../baseConnect/dbConnect.php');

header('Content-Type: application/json; charset=utf-8');

$response = ['serial_number' => '', 'lab_id' => null];

if (isset($_POST['device_id']) && is_numeric($_POST['device_id']) && isset($_POST['category'])) {
    $device_id = intval($_POST['device_id']);
    $category = trim($_POST['category']);
    
    if ($category === 'system') {
        // Fetch from system table
        $stmt = $conn->prepare("SELECT serial_number, lab FROM `system` WHERE id = ?");
        $stmt->bind_param("i", $device_id);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        
        if ($res) {
            $response['serial_number'] = $res['serial_number'] ?? '';
            $response['lab_id'] = isset($res['lab']) ? (int)$res['lab'] : null;
        }
    } elseif ($category === 'monitor') {
        // Fetch from monitor table
        $stmt = $conn->prepare("SELECT monitor_serial AS serial_number, lab FROM monitor WHERE id = ?");
        $stmt->bind_param("i", $device_id);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        
        if ($res) {
            $response['serial_number'] = $res['serial_number'] ?? '';
            $response['lab_id'] = isset($res['lab']) ? (int)$res['lab'] : null;
        }
    }
}

echo json_encode($response);
?>
