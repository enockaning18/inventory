<?php
require_once('../baseConnect/dbConnect.php');

if (!$conn) {
    echo "<tr><td colspan='5' class='text-center text-danger'>Database connection failed</td></tr>";
    exit;
}

$search = isset($_POST['search']) ? trim($_POST['search']) : '';

$sql = "SELECT module.id AS modid, module.name AS modname, course.course_name AS coursename, 
        module.date_created AS datecreated, semester 
        FROM module INNER JOIN course ON module.course_id = course.id";

$params = [];
$types = "";
if (!empty($search)) {
    $sql .= " WHERE module.name LIKE ? OR semester LIKE ? OR course.course_name LIKE ? OR module.date_created LIKE ?";
    $searchParam = "%$search%";
    $params = [$searchParam, $searchParam, $searchParam, $searchParam];
    $types = "ssss";
}

$sql .= " ORDER BY modid DESC";

$stmt = $conn->prepare($sql);
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
                <td>" . htmlspecialchars($row['modname']) . "</td>
                <td>" . htmlspecialchars($row['semester']) . "</td>
                <td>" . htmlspecialchars($row['coursename']) . "</td>
                <td>" . htmlspecialchars($row['datecreated']) . "</td>
                <td>
                    <a class='text-decoration-none' href='actions/edit_module.php?id=" . $row['modid'] . "'>
                        <i class='bi bi-pencil-square text-primary fs-5 me-2'></i>
                    </a>
                    <a class='text-decoration-none' href='actions/delete_module.php?id=" . $row['modid'] . "' onclick=\"return confirm('DO YOU WANT TO DELETE THIS MODULE?');\">
                        <i class='bi bi-trash-fill text-danger fs-5 ms-1'></i>
                    </a>
                </td>
            </tr>";
    }
} else {
    echo "<tr><td colspan='5' class='text-center' style='color: maroon; font-size: 18px;'>Oops! No Module Record(s) Found</td></tr>";
}

$stmt->close();
$conn->close();
?>
