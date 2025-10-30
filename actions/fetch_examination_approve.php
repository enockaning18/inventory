<?php
require_once('../baseConnect/dbConnect.php');

// Ensure connection is valid
if (!$conn) {
    echo "<tr><td colspan='11' class='text-center text-danger'>Database connection failed</td></tr>";
    exit;
}

// Collect filters
$search = isset($_POST['search']) ? trim($_POST['search']) : '';

$sql = "SELECT examination.*, 
               course.course_name AS course, 
               module.name AS module, 
               CONCAT(first_name,' ',last_name) AS instructor_name 
        FROM examination
        INNER JOIN course ON examination.course_id = course.id
        INNER JOIN module ON examination.module_id = module.id
        INNER JOIN instructors ON examination.instructor_id = instructors.id
        WHERE status = 'approve'";

if (!empty($search)) {
    $search = $conn->real_escape_string($search);
    $sql .= " AND (
        course.course_name LIKE '%$search%' 
        OR module.name LIKE '%$search%' 
        OR CONCAT(first_name,' ',last_name) LIKE '%$search%' 
        OR examination.batch_time LIKE '%$search%' 
        OR examination.session LIKE '%$search%'
    )";
}

$sql .= " ORDER BY examination.id DESC";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $counter = 1;
    while ($row = $result->fetch_assoc()) 
        {
            // setting bg color based on status
            $status = htmlspecialchars($row['status']);
            $badgeClass = '';

            switch (ucfirst($status)) {
                case 'Approve':
                    $badgeClass = 'bg-success'; 
                    break;
                case 'Pending':
                    $badgeClass = 'bg-warning text-dark'; 
                    break;
                case 'Cancelled':
                    $badgeClass = 'bg-danger'; 
                    break;
                default:
                    $badgeClass = 'bg-secondary'; 
            }
        echo "<tr>
                <th scope='row'>" . $counter++ . "</th>
                <td>" . htmlspecialchars($row['examination_date']) . "</td>
                <td>" . htmlspecialchars($row['course']) . "</td>
                <td>" . htmlspecialchars($row['module']) . "</td>
                <td>" . htmlspecialchars($row['batch_time']) . "</td>
                <td>" . htmlspecialchars($row['session']) . "</td>            
                <td>" . htmlspecialchars($row['start_time']) . "</td>
                <td>" . htmlspecialchars($row['batch_semester']) . "</td>
                <td>" . htmlspecialchars($row['instructor_name']) . "</td>
                <td><span class='badge $badgeClass'>" . htmlspecialchars($status) . "</span></td>
                <td>" . htmlspecialchars($row['date_booked']) . "</td>
                <td>
                    <a href='examination.php' class='text-decoration-none'>
                        <i class='bi bi-eye text-primary fs-5 me-2'></i>
                    </a>
                </td>
            </tr>";
    }
} else {
    echo "<tr><td colspan='12' class='text-center' style='color: maroon; font-size: 18px;'>Oops! No Approved Exam(s) Found</td></tr>";
}

$conn->close();
?>
