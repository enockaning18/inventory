<?php
require_once('baseConnect/dbConnect.php');

// Query to fetch all computers
$sql = "SELECT * FROM computers ORDER BY id DESC"; 
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    // Create an array to store all computers
    $computers = [];

    while ($row = $result->fetch_assoc()) {
        $computers[] = [
            'name'    => $row['computer_name'],
            'brand'   => $row['brand_name'],
            'memory'  => $row['memory_size'],
            'storage' => $row['hard_drive_size'],
            'serial'  => $row['serial_number']
        ];
    }

    // Now $computers contains all rows and can be processed later
} else {
    echo "<p>No computers found.</p>";
}

?>
