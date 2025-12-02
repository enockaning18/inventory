<?php
require_once('../baseConnect/dbConnect.php');

// Ensure connection is valid
if (!$conn) {
    echo "<tr><td colspan='7' class='text-center text-danger'>Database connection failed</td></tr>";
    exit;
}

// Collect filters
$search     = isset($_POST['search']) ? trim($_POST['search']) : '';
$lab_type = isset($_POST['lab_type']) ? trim($_POST['lab_type']) : '';
$brand_type = isset($_POST['brand_type']) ? trim($_POST['brand_type']) : '';

$sql = " SELECT monitor.id, monitor_name, monitor_serial, lab.lab_name, brand.brand_name, monitor.date_added, monitor_name, `size`, monitor_serial FROM monitor 
         INNER JOIN lab  ON monitor.lab = lab.id
         INNER JOIN brand  ON monitor.brand = brand.id ";

if (!empty($search)) {
    $search = $conn->real_escape_string($search);
    $sql .= " AND (monitor_name LIKE '%$search%' 
              OR brand.brand_name LIKE '%$search%' 
              OR monitor_serial LIKE '%$search%' 
              OR `size` LIKE '%$search%' 
              OR lab.lab_name LIKE '%$search%') ";
}

//  Filter by lab
if (!empty($memory_type)) {
    $memory_type = $conn->real_escape_string($memory_type);
    $sql .= " AND memory_size = '$memory_type' ";
}
if (!empty($lab_type)) {
    $lab_type = $conn->real_escape_string($lab_type);
    $sql .= " AND lab.lab_name = '$lab_type' ";
}
if (!empty($drive_type)) {
    $drive_type = $conn->real_escape_string($drive_type);
    $sql .= " AND hard_drive_size = '$drive_type' ";
}
if (!empty($brand_type)) {
    $brand_type = $conn->real_escape_string($brand_type);
    $sql .= " AND brand.brand_name = '$brand_type' ";
}

$sql .= " ORDER BY monitor.id DESC ";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $counter = 1;
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <th scope='row'>" . $counter++ . "</th>
                <td style='white-space: nowrap;'>" . htmlspecialchars($row['monitor_name']) . "</td>
                <td style='white-space: nowrap;'>" . htmlspecialchars($row['brand_name']) . "</td>
                <td style='white-space: nowrap;'>" . htmlspecialchars($row['monitor_serial']) . "</td>
                <td style='white-space: nowrap;'>" . htmlspecialchars($row['size']) . "</td>
                <td style='white-space: nowrap;'>" . htmlspecialchars($row['lab_name']) . "</td>
                <td style='white-space: nowrap;'>" . htmlspecialchars($row['date_added']) . "</td>
                <td style='white-space: nowrap;'>
                <a class='text-decoration-none'href='actions/edit_monitor.php?id=" . $row['id'] . "'>
                        <i class='bi bi-pencil-square text-primary fs-5 me-2'></i>
                    </a>
                    <a class='text-decoration-none'href='actions/delete_monitor.php?id=" . $row['id'] . "' onclick=\"return confirm('DO YOU WANT TO DELETE THIS MONITOR?');\">
                        <i class='bi bi-trash-fill text-danger fs-5 ms-1'></i>
                    </a>
                </td>
            </tr>";
    }
} else {
    echo "<tr><td colspan='14' class='text-center' style='color: maroon; font-size: 18px;'>Opps! No monitor Record(s) Found</td></tr>";
}

$conn->close();
