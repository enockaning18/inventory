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

// Base query
$sql = "SELECT c.*, u.email, c.datecreated AS coursedate 
        FROM course AS c 
        INNER JOIN users AS u ON c.createdby = u.id 
        WHERE 1";

// Add search filter
$params = [];
$types  = '';

if (!empty($search)) {
    $sql .= " AND (c.course_name LIKE ? OR c.id LIKE ?)";
    $searchTerm = "%$search%";
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $types .= 'ss';
}

// Final order
$sql .= " ORDER BY c.id DESC";

// Prepare statement
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo "<tr><td colspan='7' class='text-center text-danger'>Failed to prepare query</td></tr>";
    exit;
}

// Bind parameters if needed
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
                <td>" . htmlspecialchars($row['course_name']) . "</td>
                <td>" . htmlspecialchars($row['email']) . "</td>
                <td>" . htmlspecialchars($row['coursedate'] ?? '') . "</td>
                <td>
                    <a class='text-decoration-none' href='actions/edit_course.php?id_course=" . intval($row['id']) . "'>
                        <i class='bi bi-pencil-square text-primary fs-5 me-2'></i>
                    </a>
                    <a class='text-decoration-none' href='actions/delete_course.php?id=" . intval($row['id']) . "' 
                       onclick=\"return confirm('Do you want to delete this course?');\">
                        <i class='bi bi-trash-fill text-danger fs-5 ms-1'></i>
                    </a>
                </td>
            </tr>";
    }
} else {
    echo "<tr><td colspan='7' class='text-center' style='color: maroon; font-size: 18px;'>Oops! No Course Record(s) Found</td></tr>";
}

$stmt->close();
$conn->close();
?>
