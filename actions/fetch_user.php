<?php
require_once('../baseConnect/dbConnect.php');

// Ensure DB connection is valid
if (!$conn) {
    echo "<tr><td colspan='7' class='text-center text-danger'>Database connection failed</td></tr>";
    exit;
}

// Collect search input
$search = isset($_POST['search']) ? trim($_POST['search']) : '';

<<<<<<< Updated upstream
// Base query with LEFT JOIN to instructors
$sql = "
    SELECT 
        u.id AS userid,
        u.email,
        u.user_type,
        u.user_key,
        u.date_created,
        CONCAT(i.first_name, ' ', i.last_name) AS inst_name
    FROM users u
    LEFT JOIN instructors i ON u.instructor_id = i.id
    WHERE 1
";
=======
// Base query
$sql = "SELECT users.*, users.id AS userid, CONCAT(first_name,' ',last_name) 
AS inst_name FROM users INNER JOIN instructors ON users.instructor_id = instructors.id WHERE 1";

// Apply search filter if not empty
if (!empty($search)) {
    $search = $conn->real_escape_string($search);
    $sql .= " AND (
        u.email LIKE '%$search%' 
        OR u.user_type LIKE '%$search%'
        OR CONCAT(i.first_name, ' ', i.last_name) LIKE '%$search%'
        OR DATE(u.date_created) LIKE '%$search%'
    )";
}

$sql .= " ORDER BY u.id DESC";

$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $counter = 1;
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <th scope='row'>" . $counter++ . "</th>
                <td>" . htmlspecialchars($row['inst_name']) . "</td>
                <td>" . htmlspecialchars($row['user_type']) . "</td>
                <td>" . htmlspecialchars($row['date_created']) . "</td>
                <td>
                    <a class='text-decoration-none' href='users.php?edit_id=" . $row['userid'] . "' title='Edit'>
                        <i class='bi bi-pencil-square text-primary fs-5 me-2'></i>
                    </a>
                    <a class='text-decoration-none' href='actions/delete_user.php?id=" . $row['userid'] . "' 
                       onclick=\"return confirm('DO YOU WANT TO DELETE THIS USER?');\" title='Delete'>
                        <i class='bi bi-trash-fill text-danger fs-5 ms-1'></i>
                    </a>
                </td>
            </tr>";
    }
} else {
    echo "<tr><td colspan='6' class='text-center text-danger fs-6'>No User Record(s) Found!</td></tr>";
}

$conn->close();
?>
