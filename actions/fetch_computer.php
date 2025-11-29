<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once('../baseConnect/dbConnect.php');

if (!$conn) {
    echo "<tr><td colspan='14' class='text-center text-danger'>Database connection failed</td></tr>";
    exit;
}

// Collect filters
$search      = isset($_POST['search']) ? trim($_POST['search']) : '';
$lab_type    = isset($_POST['lab_type']) ? trim($_POST['lab_type']) : '';
$memory_type = isset($_POST['memory_type']) ? trim($_POST['memory_type']) : '';
$drive_type  = isset($_POST['drive_type']) ? trim($_POST['drive_type']) : '';
$brand_type  = isset($_POST['brand_type']) ? trim($_POST['brand_type']) : '';

$sql = "SELECT * FROM (
        SELECT s.id AS id, s.system_name AS device_name, b.brand_name AS brand_name, s.serial_number AS serial_number,
            s.memory_size AS memory_size, s.hard_drive_size AS hard_drive_size, NULL AS monitor_name, NULL AS size,
            NULL AS monitor_serial, l.lab_name AS lab_name, s.date_added AS date_added, 'system' AS device_type
        FROM system s
        LEFT JOIN brand b ON s.brand = b.id
        LEFT JOIN lab l ON s.lab = l.id

        UNION ALL

    SELECT m.id AS id, m.monitor_name AS device_name, b2.brand_name AS brand_name, m.monitor_serial AS serial_number,
        NULL AS memory_size, NULL AS hard_drive_size, m.monitor_name AS monitor_name, m.size AS size, m.monitor_serial AS monitor_serial,
        l2.lab_name AS lab_name, m.date_added AS date_added, 'monitor' AS device_type
    FROM monitor m
    LEFT JOIN brand b2 ON m.brand = b2.id
    LEFT JOIN lab l2 ON m.lab = l2.id
    ) AS computers WHERE 1";

// Apply filters
if (!empty($search)) {
    $search_esc = $conn->real_escape_string($search);
    $sql .= " AND (
        device_name LIKE '%$search_esc%' OR
        brand_name LIKE '%$search_esc%' OR
        serial_number LIKE '%$search_esc%' OR
        memory_size LIKE '%$search_esc%' OR
        hard_drive_size LIKE '%$search_esc%' OR
        monitor_name LIKE '%$search_esc%' OR
        size LIKE '%$search_esc%' OR
        monitor_serial LIKE '%$search_esc%' OR
        lab_name LIKE '%$search_esc%'
    )";
}

if (!empty($memory_type)) {
    $sql .= " AND memory_size = '" . $conn->real_escape_string($memory_type) . "'";
}
if (!empty($lab_type)) {
    $sql .= " AND lab_name = '" . $conn->real_escape_string($lab_type) . "'";
}
if (!empty($drive_type)) {
    $sql .= " AND hard_drive_size = '" . $conn->real_escape_string($drive_type) . "'";
}
if (!empty($brand_type)) {
    $sql .= " AND brand_name = '" . $conn->real_escape_string($brand_type) . "'";
}

$sql .= " ORDER BY device_type, id DESC";


$result = $conn->query($sql);

if (!$result) {
    echo "<tr><td colspan='14' class='text-center text-danger'>SQL Error: " . $conn->error . "</td></tr>";
    exit;
}

if ($result->num_rows > 0) {
    $counter = 1;
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
            <th scope='row'>" . $counter++ . "</th>
            <td>" . htmlspecialchars($row['device_name']) . "</td>
            <td>" . htmlspecialchars($row['brand_name'] ?? '-') . "</td>
            <td>" . htmlspecialchars($row['serial_number']) . "</td>
            <td>" . ($row['memory_size'] ? htmlspecialchars($row['memory_size']) . ' GB' : '-') . "</td>
            <td>" . ($row['hard_drive_size'] ? htmlspecialchars($row['hard_drive_size']) . ' GB' : '-') . "</td>
            <td>" . ($row['monitor_name'] ? htmlspecialchars($row['monitor_name']) : '-') . "</td>
            <td>" . ($row['size'] ? htmlspecialchars($row['size']) : '-') . "</td>
            <td>" . ($row['monitor_serial'] ? htmlspecialchars($row['monitor_serial']) : '-') . "</td>
            <td>" . htmlspecialchars($row['lab_name'] ?? '-') . "</td>
            <td>" . htmlspecialchars($row['date_added']) . "</td>
            <td>
                <a class='text-decoration-none' href='actions/edit_computer.php?id=" . $row['id'] . "'>
                    <i class='bi bi-pencil-square text-primary fs-5 me-2'></i>
                </a>
                <a class='text-decoration-none' href='actions/delete_computer.php?id=" . $row['id'] . "'
                   onclick=\"return confirm('DO YOU WANT TO DELETE THIS COMPUTER?');\">
                    <i class='bi bi-trash-fill text-danger fs-5 ms-1'></i>
                </a>
            </td>
        </tr>";
    }
} else {
    echo "<tr><td colspan='14' class='text-center text-danger' style='font-size:18px;'>Oops! No computer Record(s) Found</td></tr>";
}

$conn->close();
?>
