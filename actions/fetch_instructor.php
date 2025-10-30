
<?php
require_once('../baseConnect/dbConnect.php');

// Ensure connection is valid
if (!$conn) {
    echo "<tr><td colspan='7' class='text-center text-danger'>Database connection failed</td></tr>";
    exit;
}

// Collect filters
$search     = isset($_POST['search']) ? trim($_POST['search']) : '';

<<<<<<< Updated upstream
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
=======
$sql = "SELECT instructors.*, instructors.id AS instructid, CONCAT(first_name,' ',last_name) AS instructname, 
course_name, lab_name, instructors.date_added FROM instructors
INNER JOIN course ON instructors.course_id = course.id  
INNER JOIN lab ON instructors.lab_id = lab.id WHERE 1 ";

if (!empty($search)) {
    $search = $conn->real_escape_string($search);
    $sql .= " AND (
        instructors.first_name LIKE '%$search%' 
        OR instructors.last_name LIKE '%$search%' 
        OR instructors.phone LIKE '%$search%' 
        OR instructors.email LIKE '%$search%'
        OR course.course_name LIKE '%$search%' 
        OR lab.lab_name LIKE '%$search%'
    )";
}

$sql .= " ORDER BY instructors.id DESC";

$result = $conn->query($sql);


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
                <a class='text-decoration-none'href='actions/edit_instructor.php?id=" . $row['instructid'] . "'>
                        <i class='bi bi-pencil-square text-primary fs-5 me-2'></i>
                    </a>
                    <a class='text-decoration-none'href='actions/delete_instructor.php?id=" . $row['instructid'] . "' onclick=\"return confirm('DO YOU WANT TO DELETE THIS INSTRUCTOR?');\">
                        <i class='bi bi-trash-fill text-danger fs-5 ms-1'></i>
                    </a>
                </td>
            </tr>";
    }
} else {
    echo "<tr><td colspan='8' class='text-center' style='color: maroon; font-size: 18px;'>Opps! No Instructor Record(s) Found</td></tr>";
}

$conn->close();
