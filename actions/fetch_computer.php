<?php
require_once('../baseConnect/dbConnect.php');

// Ensure connection is valid
if (!$conn) {
    echo "<tr><td colspan='7' class='text-center text-danger'>Database connection failed</td></tr>";
    exit;
}

// Collect filters
$search     = isset($_POST['search']) ? trim($_POST['search']) : '';
$reporttype = isset($_POST['reporttype']) ? trim($_POST['reporttype']) : '';

$sql = " SELECT computers.id, computer_name, serial_number, memory_size, lab.lab_name, brand.brand_name, computers.date_added, hard_drive_size
         FROM computers 
         INNER JOIN lab  ON computers.lab = lab.id
         INNER JOIN brand  ON computers.brand = brand.id ";

if (!empty($search)) {
    $search = $conn->real_escape_string($search);
    $sql .= " AND (computer_name LIKE '%$search%' 
              OR brand.brand_name LIKE '%$search%' 
              OR serial_number LIKE '%$search%' 
              OR memory_size LIKE '%$search%' 
              OR lab.lab_name LIKE '%$search%')";
}



$sql .= " ORDER BY computers.id DESC ";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $counter = 1;
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <th scope='row'>" . $counter++ . "</th>
                <td>" . htmlspecialchars($row['computer_name']) . "</td>
                <td>" . htmlspecialchars($row['brand_name']) . "</td>
                <td>" . htmlspecialchars($row['serial_number']) . "</td>
                <td>" . htmlspecialchars($row['memory_size']) . '(gig)'. "</td>
                <td>" . htmlspecialchars($row['hard_drive_size']) . '(gig)'. "</td>
                <td>" . htmlspecialchars($row['lab_name']) . "</td>
                <td>" . htmlspecialchars($row['date_added']) . "</td>
                <td>
                <a class='text-decoration-none'href='actions/edit_computer.php?id=" . $row['id'] . "'>
                        <i class='bi bi-pencil-square text-primary fs-5 me-2'></i>
                    </a>
                    <a class='text-decoration-none'href='actions/delete_computer.php?id=" . $row['id'] . "' onclick=\"return confirm('DO YOU WANT TO DELETE THIS DATA?');\">
                        <i class='bi bi-trash-fill text-danger fs-5 ms-1'></i>
                    </a>
                </td>
            </tr>";
    }
} else {
    echo "<tr><td colspan='10' class='text-center' style='color: maroon; font-size: 18px;'>Opps! No computers Record(s) Found</td></tr>";
}

$conn->close();
