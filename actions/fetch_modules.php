<?php
require_once('../baseConnect/dbConnect.php');

header('Content-Type: text/html; charset=UTF-8');

if(isset($_POST['course_id']) && !empty($_POST['course_id']) && isset($_POST['semester']) && !empty($_POST['semester'])) 
{
    $course_id = intval($_POST['course_id']);
    $semester = $conn->real_escape_string($_POST['semester']);

    $stmt = $conn->prepare("SELECT id, name FROM module WHERE course_id = ? AND semester = ?");
    if(!$stmt) {
        echo '<option value="">Error fetching modules</option>';
        exit;
    }

    $stmt->bind_param("is", $course_id, $semester);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0) {
        echo '<option value="">Select Module</option>';
        while($row = $result->fetch_assoc()) {
            echo "<option value='{$row['id']}'>{$row['name']}</option>";
        }
    } 
    else {
        echo '<option value="">No modules available</option>';
    }

    $stmt->close();
}
?>
