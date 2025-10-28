<?php
require_once('../baseConnect/dbConnect.php');

// Ensure connection is valid
if (!$conn) {
    echo "<tr><td colspan='10' class='text-center text-danger'>Database connection failed</td></tr>";
    exit;
}

// Collect filters
$search     = isset($_POST['search']) ? trim($_POST['search']) : '';
$reporttype = isset($_POST['reporttype']) ? trim($_POST['reporttype']) : '';

// Base SQL query
$sql = "SELECT 
            id, 
            examination_date, 
            batch_time, 
            session, 
            course_id, 
            lab_id, 
            date_booked, 
            start_time, 
            course_model, 
            batch_semester
        FROM examination
        -- INNER JOIN course ON course_id = id
        -- INNER JOIN lab ON lab_id = id
        WHERE 1"; // to allow dynamic filters

// Apply search filters
if (!empty($search)) {
    $search = $conn->real_escape_string($search);
    $sql .= " AND (
        course_id LIKE '%$search%' 
        OR lab_id LIKE '%$search%'
        OR batch_time LIKE '%$search%'
        OR session LIKE '%$search%'
        OR course_model LIKE '%$search%'
        OR batch_semester LIKE '%$search%'
    )";
}

$sql .= " ORDER BY id DESC";
$result = $conn->query($sql);

// Check for results
if ($result && $result->num_rows > 0) {
    $counter = 1;
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <th scope='row'>" . $counter++ . "</th>
                <td>" . htmlspecialchars($row['examination_date']) . "</td>
                <td>" . htmlspecialchars($row['batch_time']) . "</td>
                <td>" . htmlspecialchars($row['session']) . "</td>
                <td>" . htmlspecialchars($row['course_id']) . "</td>
                <td>" . htmlspecialchars($row['date_booked']) . "</td>
                <td>" . htmlspecialchars($row['start_time']) . "</td>
                <td>" . htmlspecialchars($row['course_model']) . "</td>
                <td>" . htmlspecialchars($row['batch_semester']) . "</td>
                <td>" . htmlspecialchars($row['lab_id']) . "</td>
                <td>
                    <a class='text-decoration-none' href='actions/edit_examination.php?id=" . $row['id'] . "'>
                        <i class='bi bi-pencil-square text-primary fs-5 me-2'></i>
                    </a>
                    <a class='text-decoration-none' href='actions/delete_examination.php?id=" . $row['id'] . "' onclick=\"return confirm('Do you want to delete this record?');\">
                        <i class='bi bi-trash-fill text-danger fs-5 ms-1'></i>
                    </a>
                </td>
            </tr>";
    }
} else {
    echo "<tr><td colspan='11' class='text-center' style='color: maroon; font-size: 18px;'>Oops! No Examination Record(s) Found</td></tr>";
}

$conn->close();
?>
