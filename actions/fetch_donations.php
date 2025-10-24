<?php
require_once('../../baseConnect/dbConnect.php');

// Ensure connection is valid
if (!$conn) {
    echo "<tr><td colspan='7' class='text-center text-danger'>Database connection failed</td></tr>";
    exit;
}

// Collect filters
$search     = isset($_POST['search']) ? trim($_POST['search']) : '';
$reporttype = isset($_POST['reporttype']) ? trim($_POST['reporttype']) : '';

$sql = "SELECT * FROM donationfrm WHERE 1";

if (!empty($search)) {
    $search = $conn->real_escape_string($search);
    $sql .= " AND (donor_firstname LIKE '%$search%' 
              OR donor_lastname LIKE '%$search%' 
              OR donor_phone LIKE '%$search%' 
              OR donation_date LIKE '%$search%' 
              OR item_donated LIKE '%$search%')";
}

if (!empty($reporttype)) {
    $reporttype = $conn->real_escape_string($reporttype);
    $sql .= " AND donation_type = '$reporttype'";
}

$sql .= " ORDER BY donation_date DESC";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $counter = 1;
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <th scope='row'>" . $counter++ . "</th>
                <td>" . htmlspecialchars($row['donor_firstname'] . ' ' . $row['donor_lastname']) . "</td>
                <td>" . htmlspecialchars($row['donor_phone']) . "</td>
                <td class='text-capitalize'>" . htmlspecialchars($row['donation_type']) . "</td>
                <td class='text-capitalize'>" . htmlspecialchars($row['item_donated']) . "</td>
                <td>" . htmlspecialchars($row['donation_date']) . "</td>
                <td>
                <a class='text-decoration-none'href='actions/edit_donation.php?id=" . $row['donation_id'] . "'>
                        <i class='bi bi-pencil-square text-primary fs-5 me-2'></i>
                    </a>
                    <a class='text-decoration-none'href='actions/delete_donation.php?id=" . $row['donation_id'] . "' onclick=\"return confirm('DO YOU WANT TO DELETE THIS DATA?');\">
                        <i class='bi bi-trash-fill text-danger fs-5 ms-1'></i>
                    </a>
                </td>
            </tr>";
    }
} else {
    echo "<tr><td colspan='7' class='text-center' style='color: maroon; font-size: 18px;'>Opps! No Donation Record(s) Found</td></tr>";
}

$conn->close();
