<?php
require_once('../baseConnect/dbConnect.php');

// Ensure connection is valid
if (!$conn) {
    echo "<tr><td colspan='7' class='text-center text-danger'>Database connection failed</td></tr>";
    exit;
}

// Collect filters
$search = isset($_POST['search']) ? trim($_POST['search']) : '';
$reporttype = isset($_POST['reporttype']) ? trim($_POST['reporttype']) : '';

// Base query with join
$sql = "
    SELECT 
        lab.*, 
        lab.id AS labid, 
        course.course_name, 
        course.id AS courseid 
    FROM lab 
    INNER JOIN course ON course.id = lab.course_id 
    WHERE 1
";

// Apply search filter if provided
if (!empty($search)) {
    $search = $conn->real_escape_string($search);
    $sql .= " AND (
        lab.lab_name LIKE '%$search%' 
        OR course.course_name LIKE '%$search%' 
        OR lab.number_computers LIKE '%$search%' 
        OR lab.date_added LIKE '%$search%'
    )";
}

// Apply report type filter if provided
if (!empty($reporttype)) {
    $reporttype = $conn->real_escape_string($reporttype);
    $sql .= " AND lab.memory_size = '$reporttype'";
}

// Order by newest first
$sql .= " ORDER BY lab.id DESC";

$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $counter = 1;
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <th scope='row'>" . $counter++ . "</th>
                <td style='white-space: nowrap;'>" . htmlspecialchars($row['lab_name']) . "</td>
                <td style='white-space: nowrap;'>" . htmlspecialchars($row['course_name']) . "</td>
                <td style='white-space: nowrap;'>" . htmlspecialchars($row['number_computers']) . " pcs</td>
                <td style='white-space: nowrap;'>" . htmlspecialchars($row['date_added']) . "</td>
                <td style='white-space: nowrap;'>
                    <a class='text-decoration-none' href='actions/edit_lab.php?id=" . $row['labid'] . "' title='Edit'>
                        <i class='bi bi-pencil-square text-primary fs-5 me-2'></i>
                    </a>
                    <a class='text-decoration-none' href='actions/delete_lab.php?id=" . $row['labid'] . "' 
                       onclick=\"return confirm('DO YOU WANT TO DELETE THIS LAB?');\" title='Delete'>
                        <i class='bi bi-trash-fill text-danger fs-5 ms-1'></i>
                    </a>
                </td>
            </tr>";
    }
} else {
    echo "<tr><td colspan='7' class='text-center' style='color: maroon; font-size: 18px;'>Oops! No Lab Record(s) Found</td></tr>";
}

$conn->close();
?>
