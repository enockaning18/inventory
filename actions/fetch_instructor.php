<?php
require_once('../baseConnect/dbConnect.php');


if (!$conn) {
    echo "<tr><td colspan='8' class='text-center text-danger'>Database connection failed</td></tr>";
    exit;
}

$search = isset($_POST['search']) ? trim($_POST['search']) : '';

$sql = "
    SELECT 
        instructors.id AS instructid,
        CONCAT(instructors.first_name, ' ', instructors.last_name) AS instructname,
        instructors.email,
        instructors.phone,
        instructors.date_added,
        course.course_name,
        lab.lab_name
    FROM instructors
    INNER JOIN course ON instructors.course_id = course.id
    LEFT JOIN lab ON instructors.lab_id = lab.id
    WHERE 1
";


$params = [];
$types  = '';

if (!empty($search)) {
    $sql .= " AND (
        instructors.first_name LIKE ? 
        OR instructors.last_name LIKE ? 
        OR instructors.phone LIKE ? 
        OR instructors.email LIKE ? 
        OR course.course_name LIKE ? 
        OR lab.lab_name LIKE ?
    )";
    $searchTerm = "%$search%";
    // Add same param for all LIKE fields
    for ($i = 0; $i < 6; $i++) {
        $params[] = $searchTerm;
        $types   .= 's';
    }
}

$sql .= " ORDER BY instructors.id DESC";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo "<tr><td colspan='8' class='text-center text-danger'>Failed to prepare SQL query</td></tr>";
    exit;
}

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $counter = 1;
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <th scope='row'>" . $counter++ . "</th>
                <td>" . htmlspecialchars($row['instructname']) . "</td>
                <td>" . htmlspecialchars($row['phone']) . "</td>
                <td>" . htmlspecialchars($row['email']) . "</td>
                <td>" . htmlspecialchars($row['lab_name']) . "</td>
                <td>" . htmlspecialchars($row['course_name']) . "</td>
                <td>" . htmlspecialchars($row['date_added']) . "</td>
                <td>
                    <a class='text-decoration-none' href='actions/edit_instructor.php?id=" . intval($row['instructid']) . "'>
                        <i class='bi bi-pencil-square text-primary fs-5 me-2'></i>
                    </a>
                    <a class='text-decoration-none' href='actions/delete_instructor.php?id=" . intval($row['instructid']) . "' 
                       onclick=\"return confirm('DO YOU WANT TO DELETE THIS INSTRUCTOR ?');\">
                        <i class='bi bi-trash-fill text-danger fs-5 ms-1'></i>
                    </a>
                </td>
            </tr>";
    }
} else {
    echo "<tr><td colspan='8' class='text-center' style='color: maroon; font-size: 18px;'>Oops! No Instructor Record(s) Found</td></tr>";
}

$stmt->close();
$conn->close();
?>
