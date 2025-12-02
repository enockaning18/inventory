<?php
session_start();
require_once('../baseConnect/dbConnect.php');

// Get user info safely
$usertype     = $_SESSION['type'] ?? '';
$instructorid = intval($_SESSION['instructorid'] ?? 0);

// Check connection
if (!$conn) {
    echo "<tr><td colspan='11' class='text-center text-danger'>Database connection failed</td></tr>";
    exit;
}

// Collect filters
$search   = trim($_POST['search'] ?? '');
$module   = trim($_POST['module'] ?? '');
$course   = trim($_POST['course'] ?? '');
$semester = trim($_POST['semester'] ?? '');

// base query
$sql = "SELECT examination.*, 
        course.course_name AS course, 
        module.name AS module, 
        CONCAT(instructors.first_name,' ',instructors.last_name) AS instructor_name, 
        course.id AS course_id, 
        module.id AS module_id
    FROM examination
    INNER JOIN course ON examination.course_id = course.id
    INNER JOIN module ON examination.module_id = module.id
    INNER JOIN instructors ON examination.instructor_id = instructors.id
    WHERE 1=1";

// Restrict for non-admins
if ($usertype !== 'admin') {
    $sql .= " AND examination.instructor_id = $instructorid";
}

// default show approve if no status selected
$sql .= " AND examination.status = 'approve'";

if (!empty($search)) {
    $search = $conn->real_escape_string($search);
    $sql .= " AND (
        course.course_name LIKE '%$search%' 
        OR module.name LIKE '%$search%' 
        OR CONCAT(instructors.first_name,' ',instructors.last_name) LIKE '%$search%' 
        OR examination.batch_time LIKE '%$search%' 
        OR examination.session LIKE '%$search%'
    )";
}

if (!empty($module)) {
    $sql .= " AND examination.module_id = '" . $conn->real_escape_string($module) . "'";
}

if (!empty($course)) {
    $sql .= " AND examination.course_id = '" . $conn->real_escape_string($course) . "'";
}

if (!empty($semester)) {
    $sql .= " AND examination.batch_semester = '" . $conn->real_escape_string($semester) . "'";
}

$sql .= " ORDER BY examination.id DESC";


$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $counter = 1;
    while ($row = $result->fetch_assoc()) {

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
            <th scope='row'>{$counter}</th>
            <td style='white-space: nowrap;'>" . htmlspecialchars($row['examination_date']) . "</td>
            <td style='white-space: nowrap;'>" . htmlspecialchars($row['course']) . "</td>
            <td style='white-space: nowrap;'>" . htmlspecialchars($row['module']) . "</td>
            <td style='white-space: nowrap;'>" . htmlspecialchars($row['batch_time']) . "</td>
            <td style='white-space: nowrap;'>" . htmlspecialchars($row['session']) . "</td>            
            <td style='white-space: nowrap;'>" . htmlspecialchars($row['start_time']) . "</td>
            <td style='white-space: nowrap;'>" . htmlspecialchars($row['batch_semester']) . "</td>
            <td style='white-space: nowrap;'>" . htmlspecialchars($row['instructor_name']) . "</td>
            <td style='white-space: nowrap;'><span class='badge {$badgeClass}'>" . htmlspecialchars(ucfirst($status)) . "</span></td>
            <td style='white-space: nowrap;'>" . htmlspecialchars($row['date_booked']) . "</td>
            <td>";

        // Role-based actions
        if ($usertype === 'admin') {
            echo "
                <a href='actions/edit_examination.php?id={$row['id']}' class='text-decoration-none'>
                    <i class='bi bi-pencil-square text-primary fs-5 me-2'></i>
                </a>
                <a href='actions/delete_examination.php?id={$row['id']}' onclick=\"return confirm('Delete this record?');\" class='text-decoration-none'>
                    <i class='bi bi-trash-fill text-danger fs-5 ms-1'></i>
                </a>
                <a href='actions/update_exam_status.php?id={$row['id']}&status=approve' class='text-decoration-none'>
                    <i class='bi bi-check-circle-fill text-success fs-5 ms-1'></i>
                </a>
                <a href='actions/update_exam_status.php?id={$row['id']}&status=pending' class='text-decoration-none'>
                    <i class='bi bi-hourglass-split text-warning fs-5 ms-1'></i>
                </a>
                <a href='actions/update_exam_status.php?id={$row['id']}&status=cancelled' class='text-decoration-none'>
                    <i class='bi bi-x-circle-fill text-danger fs-5 ms-1'></i>
                </a>
            ";
        } else {
            // Non-admin (instructor)
            if ($status === 'pending') {
                echo "
                    <a href='actions/edit_examination.php?id={$row['id']}' class='text-decoration-none'>
                        <i class='bi bi-pencil-square text-primary fs-5 me-2'></i>
                    </a>
                    <a href='actions/delete_examination.php?id={$row['id']}' onclick=\"return confirm('Delete this record?');\" class='text-decoration-none'>
                        <i class='bi bi-trash-fill text-danger fs-5 ms-1'></i>
                    </a>
                    <a href='actions/update_exam_status.php?id={$row['id']}&status=cancelled' class='text-decoration-none'>
                        <i class='bi bi-x-circle-fill text-danger fs-5 ms-1'></i>
                    </a>
                ";
            } elseif ($status === 'cancelled') {
                echo "
                    <a href='actions/update_exam_status.php?id={$row['id']}&status=pending' class='text-decoration-none'>
                    <i class='bi bi-hourglass-split text-warning fs-5 ms-1'></i>
                    </a>
                    <a href='actions/delete_examination.php?id={$row['id']}' onclick=\"return confirm('Delete this record?');\" class='text-decoration-none'>
                        <i class='bi bi-trash-fill text-danger fs-5 ms-1'></i>
                    </a>
                ";
            } elseif ($status === 'approve') {
                echo "
                    <a href='examination.php' class='text-decoration-none'>
                        <i class='bi bi-eye text-primary fs-5 me-2'></i>
                    </a>
                ";
            }
        }

        echo "</td></tr>";
        $counter++;
    }
} else {
    echo "<tr><td colspan='12' class='text-center' style='color: maroon; font-size: 18px;'>Oops! No Pending Exam(s) Found</td></tr>";
}

$conn->close();
