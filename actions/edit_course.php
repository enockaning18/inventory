<?php
require_once('../baseConnect/dbConnect.php');

if (!isset($_GET['id_course']) || empty($_GET['id_course'])) {
    die("Invalid request");
}
$course_id = intval($_GET['id_course']);

// verify record exists (optional)
$stmt = $conn->prepare("SELECT id FROM `course` WHERE id = ?");
$stmt->bind_param("i", $course_id);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows !== 1) {
    //  echo $course_id;
    die("Course not found ");
   
}
$stmt->close();

// redirect to the main page with edit id
header("Location: ../labs.php?edit_course_id=" . $course_id);
exit;
