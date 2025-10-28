
<?php
require_once('../baseConnect/dbConnect.php');

// Ensure connection is valid
if (!$conn) {
    echo "<tr><td colspan='7' class='text-center text-danger'>Database connection failed</td></tr>";
    exit;
}

// Collect filters
$search     = isset($_POST['search']) ? trim($_POST['search']) : '';

$sql = "SELECT issues.id, computers.computer_name, issue_type, lab.lab_name, issue_date, issue_description, issues.date_added FROM issues
        INNER JOIN computers ON issues.id = issues.id
        INNER JOIN lab ON issues.id = issues.id  WHERE 1 ";

if (!empty($search)) {
    $search = $conn->real_escape_string($search);
    $sql .= " AND (computer_name LIKE '%$search%' 
              OR issue_type LIKE '%$search%' 
              OR lab LIKE '%$search%' 
              OR issue_date LIKE '%$search%' 
              OR issue_description LIKE '%$search%'";
}

$sql .= " ORDER BY issues.id DESC";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $counter = 1;
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <th scope='row'>" . $counter++ . "</th>
                <td>" . htmlspecialchars($row['computer_name']) .  "</td>
                <td>" . htmlspecialchars($row['issue_type']) . "</td>
                <td>" . htmlspecialchars($row['lab_name']) . "</td>
                <td>" . htmlspecialchars($row['issue_date']) . "</td>
                <td>" . htmlspecialchars($row['issue_description']) . "</td>
                <td>" . htmlspecialchars($row['issue_date']) . "</td>
                <td>" . htmlspecialchars($row['date_added']) . "</td>
                <td>
                <a class='text-decoration-none'href='actions/edit_issue.php?id=" . $row['id'] . "'>
                        <i class='bi bi-pencil-square text-primary fs-5 me-2'></i>
                    </a>
                    <a class='text-decoration-none'href='actions/delete_issue.php?id=" . $row['id'] . "' onclick=\"return confirm('DO YOU WANT TO DELETE THIS DATA?');\">
                        <i class='bi bi-trash-fill text-danger fs-5 ms-1'></i>
                    </a>
                </td>
            </tr>";
    }
} else {
    echo "<tr><td colspan='10' class='text-center' style='color: maroon; font-size: 18px;'>Opps! No Lab Record(s) Found</td></tr>";
}

$conn->close();
