<?php
require_once('../baseConnect/dbConnect.php');
session_start();

// Set JSON header early
header('Content-Type: application/json');

// Ensure database connection is valid
if (!$conn) {
    echo json_encode(['error' => 'Database connection failed.']);
    exit;
}

try {
    $query = "
        SELECT 
            e.examination_date AS exam_date,
            e.batch_time,
            e.status,
            c.course_name,
            m.name AS module_name,
            i.first_name,
            i.last_name
        FROM examination e
        JOIN course c ON e.course_id = c.id
        JOIN module m ON e.module_id = m.id
        JOIN instructors i ON e.instructor_id = i.id
        ORDER BY e.examination_date ASC
    ";

    $result = $conn->query($query);

    if (!$result) {
        throw new Exception("Query failed: " . $conn->error);
    }

    $exams = [];

    while ($row = $result->fetch_assoc()) {
        $exams[] = [
            'exam_date'       => $row['exam_date'],
            'course_name'     => $row['course_name'],
            'module_name'     => $row['module_name'],
            'batch_time'      => $row['batch_time'],
            'instructor_name' => trim($row['first_name'] . ' ' . $row['last_name']),
            'status'          => $row['status']
        ];
    }

    echo json_encode($exams);

} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
