<?php
require_once('../baseConnect/dbConnect.php');

// Ensure connection is valid
if (!$conn) {
    echo "<tr><td colspan='7' class='text-center text-danger'>Database connection failed</td></tr>";
    exit;
}

// Collect search input
$search = isset($_POST['search']) ? trim($_POST['search']) : '';

// Base query
$sql = "SELECT id, name AS modname, date_created FROM module WHERE 1";

// Apply search filter if not empty
if (!empty($search)) {
    $search = $conn->real_escape_string($search);
    $sql .= " AND (name LIKE '%$search%' OR date_created LIKE '%$search%')";
}

// Order by newest first
$sql .= " ORDER BY id DESC";

$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $counter = 1;
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <th scope='row'>" . $counter++ . "</th>
                <td>" . htmlspecialchars($row['modname']) . "</td>
                <td>" . htmlspecialchars($row['date_created']) . "</td>
                <td>
                    <a class='text-decoration-none' href='actions/edit_module.php?id=" . $row['id'] . "'>
                        <i class='bi bi-pencil-square text-primary fs-5 me-2'></i>
                    </a>
                    <a class='text-decoration-none' href='actions/delete_module.php?id=" . $row['id'] . "' onclick=\"return confirm('DO YOU WANT TO DELETE THIS MODULE?');\">
                        <i class='bi bi-trash-fill text-danger fs-5 ms-1'></i>
                    </a>
                </td>
            </tr>";
    }
} else {
    echo "<tr><td colspan='10' class='text-center' style='color: maroon; font-size: 18px;'>Oops! No Module Record(s) Found</td></tr>";
}

$conn->close();
?>
