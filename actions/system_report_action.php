<?php
require_once('../baseConnect/dbConnect.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_POST['generate_report'])) {
    $table = $_POST['report_type'];
    $start = $_POST['start_date'];
    $end   = $_POST['end_date'];

    if (empty($table)) {
        echo "<!--SPLIT--><tr><td colspan='10' class='text-center text-danger'>Choose Report</td></tr>";
        exit;
    }

    // Whitelist valid tables
    $validTables = ['computers', 'lab', 'issues', 'instructors', 'users', 'examination'];
    if (!in_array($table, $validTables)) {
        echo "<!--SPLIT--><tr><td colspan='10' class='text-center text-danger'>Invalid Report</td></tr>";
        exit;
    }

    // Build query
    switch ($table) {
        case 'computers':
            $sql = "SELECT computers.id, computer_name, serial_number, memory_size, lab.lab_name, brand.brand_name, computers.date_added, hard_drive_size
                    FROM computers 
                    INNER JOIN lab  ON computers.lab = lab.id
                    INNER JOIN brand  ON computers.brand = brand.id 
                    WHERE computers.date_added BETWEEN ? AND ?";
            break;

        case 'lab':
            $sql = "SELECT lab.*, course.course_name 
                    FROM lab 
                    INNER JOIN course ON course.id = lab.course_id 
                    WHERE lab.date_added BETWEEN ? AND ?";
            break;

        case 'issues':
            $sql = "SELECT issues.*, computers.computer_name AS pc, lab.lab_name AS labname 
                    FROM issues
                    INNER JOIN computers ON issues.computer = computers.id
                    INNER JOIN lab ON issues.lab = lab.id
                    WHERE issues.date_added BETWEEN ? AND ?";
            break;

        case 'instructors':
            $sql = "SELECT instructors.id AS instructid, CONCAT(instructors.first_name, ' ', instructors.last_name) AS instructname,
                    instructors.email, instructors.phone, instructors.date_added, course.course_name, lab.lab_name
                    FROM instructors
                    INNER JOIN course ON instructors.course_id = course.id
                    LEFT JOIN lab ON instructors.lab_id = lab.id
                    WHERE instructors.date_added BETWEEN ? AND ?";
            break;

        case 'examination':
            $sql = "SELECT examination.*, course.course_name AS course, module.name AS module, 
                    CONCAT(instructors.first_name,' ',instructors.last_name) AS instructor_name
                    FROM examination
                    INNER JOIN course ON examination.course_id = course.id
                    INNER JOIN module ON examination.module_id = module.id
                    INNER JOIN instructors ON examination.instructor_id = instructors.id
                    WHERE examination.date_added BETWEEN ? AND ?";
            break;
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $start, $end);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $caption = '';
        $thead = '';
        $tbody = '';
        $counter = 1;
        $dateGenerated = date('Y-m-d H:i:s'); 

        // Create caption
        $caption = "<h5 class='text-center mb-3 text-danger fw-bold'>"
                 . ucfirst($table) . " Report</h5>"
                 . "<p class='text-center text-secondary small'>This Report was Generated on: "
                 . $dateGenerated."</p>";

        // Table headers and rows
        switch ($table) {
            case 'computers':
                $thead = "<tr>
                    <th>#</th><th>Computer Name</th><th>Brand</th><th>Serial Number</th>
                    <th>Memory</th><th>Drive</th><th>Lab</th><th>Date Added</th>
                </tr>";
                while ($row = $result->fetch_assoc()) {
                    $tbody .= "<tr>
                        <td>{$counter}</td><td>{$row['computer_name']}</td><td>{$row['brand_name']}</td>
                        <td>{$row['serial_number']}</td><td>{$row['memory_size']}</td>
                        <td>{$row['hard_drive_size']}</td><td>{$row['lab_name']}</td>
                        <td>{$row['date_added']}</td></tr>";
                    $counter++;
                }
                break;

            case 'lab':
                $thead = "<tr>
                    <th>#</th><th>Lab Name</th><th>Course</th><th>No. of Computers</th><th>Date Created</th>
                </tr>";
                while ($row = $result->fetch_assoc()) {
                    $tbody .= "<tr>
                        <td>{$counter}</td><td>{$row['lab_name']}</td><td>{$row['course_name']}</td>
                        <td>{$row['number_computers']} Computer(s)</td><td>{$row['date_added']}</td></tr>";
                    $counter++;
                }
                break;

            case 'issues':
                $thead = "<tr>
                    <th>#</th><th>Computer</th><th>Issue Type</th><th>Lab</th>
                    <th>Issue Date</th><th>Description</th><th>Date Added</th>
                </tr>";
                while ($row = $result->fetch_assoc()) {
                    $tbody .= "<tr>
                        <td>{$counter}</td><td>{$row['pc']}</td><td>{$row['issue_type']}</td>
                        <td>{$row['labname']}</td><td>{$row['issue_date']}</td>
                        <td>{$row['issue_description']}</td><td>{$row['date_added']}</td></tr>";
                    $counter++;
                }
                break;

            case 'instructors':
                $thead = "<tr>
                    <th>#</th><th>Name</th><th>Phone</th><th>Email</th>
                    <th>Lab</th><th>Course</th><th>Date Added</th>
                </tr>";
                while ($row = $result->fetch_assoc()) {
                    $tbody .= "<tr>
                        <td>{$counter}</td><td>{$row['instructname']}</td><td>{$row['phone']}</td>
                        <td>{$row['email']}</td><td>{$row['lab_name']}</td>
                        <td>{$row['course_name']}</td><td>{$row['date_added']}</td></tr>";
                    $counter++;
                }
                break;

            case 'users':
                $thead = "<tr>
                    <th>#</th><th>Email</th><th>User Type</th><th>Assigned Instructor</th><th>Date Added</th>
                </tr>";
                while ($row = $result->fetch_assoc()) {
                    $tbody .= "<tr>
                        <td>{$counter}</td><td>{$row['email']}</td><td>{$row['user_type']}</td>
                        <td>{$row['inst_name']}</td><td>{$row['date_added']}</td></tr>";
                    $counter++;
                }
                break;

            case 'examination':
                $thead = "<tr>
                    <th>#</th><th>ExamsDate</th><th>Course</th><th>Module</th>
                    <th>BatchTime</th><th>Session</th><th>StartTime</th>
                    <th>Semester</th><th>Instructor</th><th>DateBooked</th>
                </tr>";
                while ($row = $result->fetch_assoc()) {
                    $tbody .= "<tr>
                        <td>{$counter}</td><td>{$row['examination_date']}</td><td>{$row['course']}</td>
                        <td>{$row['module']}</td><td>{$row['batch_time']}</td><td>{$row['session']}</td>
                        <td>{$row['start_time']}</td><td>{$row['batch_semester']}</td>
                        <td>{$row['instructor_name']}</td><td>{$row['date_booked']}</td></tr>";
                    $counter++;
                }
                break;
        }

        // Send caption, headers, and body separately
        echo $caption . "<!--SPLIT-->" . $thead . "<!--SPLIT-->" . $tbody;
    } else {
        echo "<!--SPLIT--><tr><td colspan='10' class='text-center text-danger'>No records found for the selected date range.</td></tr>";
    }

    $stmt->close();
}

$conn->close();
?>
