<?php
require_once('../baseConnect/dbConnect.php');

error_reporting(E_ALL);
ini_set("display_errors", 1);

if (!$conn) {
    echo "<tr><td colspan='14' class='text-center text-danger'>Database connection failed</td></tr>";
    exit;
}

// Collect filters
$search      = trim($_POST['search'] ?? '');
$lab_type    = trim($_POST['lab_type'] ?? '');
$memory_type = trim($_POST['memory_type'] ?? '');
$drive_type  = trim($_POST['drive_type'] ?? '');
$brand_type  = trim($_POST['brand_type'] ?? '');

// Base query
$sql = "
    SELECT 
        system.id,
        system.system_name,
        brand.brand_name,
        system.serial_number,
        system.memory_size,
        system.hard_drive_size,
        system.processor_type,
        system.iseries,
        system.speed,
        system.generation,
        lab.lab_name,
        system.date_added
    FROM system
    INNER JOIN brand ON system.brand = brand.id
    INNER JOIN lab ON system.lab = lab.id
    WHERE 1
";

// 🔍 SEARCH filter
if ($search !== '') {
    $search_esc = $conn->real_escape_string($search);
    $sql .= "
        AND (
            system.system_name LIKE '%$search_esc%' OR
            brand.brand_name LIKE '%$search_esc%' OR
            system.serial_number LIKE '%$search_esc%' OR
            system.memory_size LIKE '%$search_esc%' OR
            system.hard_drive_size LIKE '%$search_esc%' OR
            lab.lab_name LIKE '%$search_esc%'
        )
    ";
}

// MEMORY filter
if ($memory_type !== '') {
    $sql .= " AND system.memory_size = '" . $conn->real_escape_string($memory_type) . "' ";
}

// LAB filter
if ($lab_type !== '') {
    $sql .= " AND lab.lab_name = '" . $conn->real_escape_string($lab_type) . "' ";
}

// HDD/SSD filter
if ($drive_type !== '') {
    $sql .= " AND system.hard_drive_size = '" . $conn->real_escape_string($drive_type) . "' ";
}

// BRAND filter
if ($brand_type !== '') {
    $sql .= " AND brand.brand_name = '" . $conn->real_escape_string($brand_type) . "' ";
}

$sql .= " ORDER BY system.id DESC ";

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

                <td>" . htmlspecialchars($row['system_name'] ?? '') . "</td>
                <td>" . htmlspecialchars($row['brand_name'] ?? '') . "</td>
                <td>" . htmlspecialchars($row['serial_number'] ?? '') . "</td>
                <td>" . htmlspecialchars($row['iseries'] ?? '') . "</td>
                <td>" . htmlspecialchars($row['processor_type'] ?? '') . "</td>

                <td>" . htmlspecialchars($row['speed'].'GHz' ?? '') . "</td>
                <td>" . htmlspecialchars($row['generation'].'TH' ?? '') . "</td>

                <td>" . htmlspecialchars($row['memory_size'].'GB' ?? '') . "</td>
                <td>" . htmlspecialchars($row['hard_drive_size'] ?? '') . "</td>

                <td>" . htmlspecialchars($row['lab_name'] ?? '') . "</td>
                <td>" . htmlspecialchars($row['date_added'] ?? '') . "</td>

                <td>
                    <a class='text-decoration-none' href='actions/edit_system.php?id=" . $row['id'] . "'>
                        <i class='bi bi-pencil-square text-primary fs-5 me-2'></i>
                    </a>

                    <a class='text-decoration-none' 
                       href='actions/delete_system.php?id=" . $row['id'] . "' 
                       onclick=\"return confirm('DO YOU WANT TO DELETE THIS SYSTEM?');\">
                        <i class='bi bi-trash-fill text-danger fs-5 ms-1'></i>
                    </a>
                </td>
            </tr>";
    }
} else {
    echo "<tr><td colspan='14' class='text-center' style='color: maroon; font-size: 18px;'>Oops! No System Record(s) Found</td></tr>";
}

$conn->close();
