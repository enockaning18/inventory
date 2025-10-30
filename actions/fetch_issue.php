<?php
require_once('../baseConnect/dbConnect.php');

// Ensure connection is valid
if (!$conn) {
    echo "<tr><td colspan='9' class='text-center text-danger'>Database connection failed</td></tr>";
    exit;
}

// Collect filters
$search      = isset($_POST['search']) ? trim($_POST['search']) : '';
$issue_type  = isset($_POST['issue_type']) ? trim($_POST['issue_type']) : '';
$lab_type    = isset($_POST['lab_type']) ? trim($_POST['lab_type']) : '';

$sql = "SELECT 
            issues.*, 
            computers.computer_name AS pc, 
            lab.lab_name AS labname 
        FROM issues
        INNER JOIN computers ON issues.computer = computers.id
        INNER JOIN lab ON issues.lab = lab.id
        WHERE 1";

//  Filter by search
if (!empty($search)) {
    $search = $conn->real_escape_string($search);
    $sql .= " AND (
                computers.computer_name LIKE '%$search%' 
                OR issues.issue_type LIKE '%$search%' 
                OR lab.lab_name LIKE '%$search%' 
                OR issues.issue_date LIKE '%$search%' 
                OR issues.issue_description LIKE '%$search%'
            )";
}

//  Filter by lab
if (!empty($lab_type)) {
    $lab_type = $conn->real_escape_string($lab_type);
    $sql .= " AND lab.lab_name = '$lab_type' ";
}


// Filter by issue type
if (!empty($issue_type)) {
    $issue_type = $conn->real_escape_string($issue_type);
    $sql .= " AND issues.issue_type = '$issue_type' ";
}

$sql .= " ORDER BY issues.id DESC";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $counter = 1;
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <th scope='row'>" . $counter++ . "</th>
                <td>" . htmlspecialchars($row['pc']) . "</td>
                <td>" . htmlspecialchars($row['issue_type']) . "</td>
                <td>" . htmlspecialchars($row['labname']) . "</td>
                <td>" . htmlspecialchars($row['issue_date']) . "</td>
                <td>" . htmlspecialchars($row['issue_description']) . "</td>
                <td>" . htmlspecialchars($row['issue_date']) . "</td>
                <td>" . htmlspecialchars($row['date_added']) . "</td>
                <td>
                    <a class='text-decoration-none' href='actions/edit_issue.php?id=" . $row['id'] . "'>
                        <i class='bi bi-pencil-square text-primary fs-5 me-2'></i>
                    </a>
                    <a class='text-decoration-none' href='actions/delete_issue.php?id=" . $row['id'] . "' onclick=\"return confirm('DO YOU WANT TO DELETE THIS ISSUE?');\">
                        <i class='bi bi-trash-fill text-danger fs-5 ms-1'></i>
                    </a>
                </td>
            </tr>";
    }
} else {
    echo "<tr><td colspan='9' class='text-center' style='color: maroon; font-size: 18px;'>Oops! No Issue Record(s) Found</td></tr>";
}

$conn->close();
