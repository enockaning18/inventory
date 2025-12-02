<?php
require_once('../baseConnect/dbConnect.php');

header('Content-Type: application/json; charset=utf-8');

$devices = [];

if (isset($_POST['category']) && !empty($_POST['category'])) {
    $category = trim($_POST['category']);
    
    if ($category === 'system') {
        // Fetch from system table
        $query = "SELECT id, system_name AS name FROM `system` ORDER BY system_name ASC";
        $result = $conn->query($query);
        
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $devices[] = [
                    'id' => $row['id'],
                    'name' => $row['name']
                ];
            }
        }
    } elseif ($category === 'monitor') {
        // Fetch from monitor table
        $query = "SELECT id, monitor_name AS name FROM monitor ORDER BY monitor_name ASC";
        $result = $conn->query($query);
        
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $devices[] = [
                    'id' => $row['id'],
                    'name' => $row['name']
                ];
            }
        }
    }
}

echo json_encode($devices);
$conn->close();
?>
