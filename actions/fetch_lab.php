
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

$sql = " SELECT lab_name, course.course_name, CONCAT(first_name, ' ', last_name) AS full_name, lab.number_computers, lab.date_added, 
CONCAT(lab.id) AS lab_id, CONCAT(course.id) AS course_id FROM instructors
INNER JOIN course ON course.id = instructors.id
LEFT JOIN lab ON lab.id = instructors.lab_id WHERE 1 ";

if (!empty($search)) {
    $search = $conn->real_escape_string($search);
    $sql .= " AND (lab_name LIKE '%$search%' 
              OR course_id LIKE '%$search%' 
              OR number_computers LIKE '%$search%')";
}


$sql .= " ORDER BY lab_id DESC ";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $counter = 1;
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <th scope='row'>" . $counter++ . "</th>
                <td>" . htmlspecialchars($row['lab_name']) . "</td>
                <td>" . htmlspecialchars($row['course_name']) . "</td>
                <td>" . htmlspecialchars($row['full_name'] ?? '') . "</td>
                <td>" . htmlspecialchars($row['number_computers']) . 'pcs'. "</td>
                <td>" . htmlspecialchars($row['date_added']) . "</td>
                <td>
                <a class='text-decoration-none'href='actions/edit_lab.php?id=" . $row['lab_id'] . "'>
                    <i class='bi bi-pencil-square text-primary fs-5 me-2'></i>
                </a> 
                
                <a class='text-decoration-none'href='actions/delete_lab.php?id=" . $row['lab_id'] . "' onclick=\"return confirm('DO YOU WANT TO DELETE THIS LAB?');\">
                    <i class='bi bi-trash-fill text-primary fs-5 ms-1'></i> |
                </a>

                <a class='text-decoration-none 'href='actions/edit_course.php?id_course=" . $row['course_id'] . "'>
                    <i class='bi bi-pencil-square text-success fs-5 me-2'></i>
                </a>
                <a class='text-decoration-none'href='actions/delete_course.php?id=" . $row['course_id'] . "' onclick=\"return confirm('DO YOU WANT TO DELETE COURSE?');\">
                    <i class='bi bi-trash-fill text-success fs-5 ms-1'></i>
                </a>
                </td>
            </tr>";
    }
} else {
    echo "<tr><td colspan='7' class='text-center' style='color: maroon; font-size: 18px;'>Opps! No Lab Record(s) Found</td></tr>";
}

$conn->close();
