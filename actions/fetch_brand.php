<?php
require_once('../baseConnect/dbConnect.php');

if (!$conn) {
    echo "<tr><td colspan='7' class='text-center text-danger'>Database connection failed</td></tr>";
    exit;
}

$search = isset($_POST['search']) ? trim($_POST['search']) : '';

$sql = "SELECT id, brand_name, date_added FROM brand WHERE 1";

$params = [];
$types  = '';

if (!empty($search)) {
    $sql .= " AND brand_name LIKE ?";
    $searchTerm = "%$search%";
    $params[] = $searchTerm;
    $types .= 's';
}

$sql .= " ORDER BY id DESC";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo "<tr><td colspan='7' class='text-center text-danger'>Failed to prepare SQL statement</td></tr>";
    exit;
}

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
                <td>" . htmlspecialchars($row['brand_name']) . "</td>
                <td>" . htmlspecialchars($row['date_added'] ?? 'N/A') . "</td>
                <td>
                    <a class='text-decoration-none' href='actions/edit_brand.php?id=" . intval($row['id']) . "'>
                        <i class='bi bi-pencil-square text-primary fs-5 me-2'></i>
                    </a>
                    <a class='text-decoration-none' href='actions/delete_brand.php?id=" . intval($row['id']) . "' 
                       onclick=\"return confirm('DO YOU WANT TO DELETE THIS BRAND ?');\">
                        <i class='bi bi-trash-fill text-danger fs-5 ms-1'></i>
                    </a>
                </td>
            </tr>";
    }
} else {
    echo "<tr><td colspan='7' class='text-center' style='color: maroon; font-size: 18px;'>Oops! No Brand Record(s) Found</td></tr>";
}

$stmt->close();
$conn->close();
?>
