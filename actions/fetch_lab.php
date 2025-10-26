
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

$sql = "SELECT lab.id, instructors.first_name, instructors.last_name, lab_name, lab_course, instructor, number_computers, lab.date_added FROM lab 
        INNER JOIN instructors ON lab.instructor = lab.instructor WHERE 1";

if (!empty($search)) {
    $search = $conn->real_escape_string($search);
    $sql .= " AND (lab_name LIKE '%$search%' 
              OR lab_course LIKE '%$search%' 
              OR instructor LIKE '%$search%' 
              OR number_computers LIKE '%$search%')";
}

if (!empty($reporttype)) {
    $reporttype = $conn->real_escape_string($reporttype);
    $sql .= " AND lab_course = '$reporttype'";
}

$sql .= " ORDER BY id DESC";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $counter = 1;
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <th scope='row'>" . $counter++ . "</th>
                <td>" . htmlspecialchars($row['lab_name']) . "</td>
                <td>" . htmlspecialchars($row['lab_course']) . "</td>
                <td>" . htmlspecialchars($row['first_name']) . ' ' . $row['last_name'] . "</td>
                <td>" . htmlspecialchars($row['number_computers']) . "</td>
                <td>" . htmlspecialchars($row['date_added']) . "</td>
                <td>
                <a class='text-decoration-none'href='actions/edit_lab.php?id=" . $row['id'] . "'>
                        <i class='bi bi-pencil-square text-primary fs-5 me-2'></i>
                    </a>
                    <a class='text-decoration-none'href='actions/delete_lab.php?id=" . $row['id'] . "' onclick=\"return confirm('DO YOU WANT TO DELETE THIS DATA?');\">
                        <i class='bi bi-trash-fill text-danger fs-5 ms-1'></i>
                    </a>
                </td>
            </tr>";
    }
} else {
    echo "<tr><td colspan='7' class='text-center' style='color: maroon; font-size: 18px;'>Opps! No Lab Record(s) Found</td></tr>";
}

$conn->close();
