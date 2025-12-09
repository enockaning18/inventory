<?php
session_start();
require_once('../baseConnect/dbConnect.php');

$instructorid = intval($_SESSION['instructorid']);
$usertype     = $_SESSION['type'];

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_POST['generate_report'])) {

    $table = $_POST['report_type'];
    $start = $_POST['start_date'];
    $issue_status = $_POST['issue_status'];
    $end   = $_POST['end_date'];

    if (empty($table)) {
        echo "<!--SPLIT--><tr><td colspan='10' class='text-center text-danger'>Choose Report</td></tr>";
        exit;
    }

    // Valid tables
    $validTables = ['system', 'monitor', 'lab', 'issues', 'instructors', 'users', 'examination'];
    if (!in_array($table, $validTables)) {
        echo "<!--SPLIT--><tr><td colspan='10' class='text-center text-danger'>Invalid Report</td></tr>";
        exit;
    }

    // Build SQL
    switch ($table) {

        case 'system':
            $sql = "SELECT system.id, system_name, brand.brand_name AS brand_name, serial_number, memory_size, hard_drive_size, 
                    processor_type, iseries, speed, generation, lab.lab_name AS lab_name, system.date_added FROM system
                    INNER JOIN lab   ON system.lab   = lab.id
                    INNER JOIN brand ON system.brand = brand.id 
                    WHERE system.date_added BETWEEN ? AND ?";
            break;

        case 'monitor':
            $sql = "SELECT monitor.id, monitor_name, `size`, monitor_serial, brand.brand_name AS brand_name, lab.lab_name AS lab_name, monitor.date_added FROM monitor 
                    INNER JOIN lab   ON monitor.lab   = lab.id
                    INNER JOIN brand ON monitor.brand = brand.id 
                    WHERE monitor.date_added BETWEEN ? AND ?";
            break;

        case 'lab':
            $sql = "SELECT lab.*, course.course_name
                    FROM lab 
                    INNER JOIN course ON course.id = lab.course_id
                    WHERE lab.date_added BETWEEN ? AND ?";
            break;

        case 'issues':
            $sql = "SELECT issues.id, issues.serial_number, issues.issue_type, issues.resolved_type, issues.lab, issues.issue_status,
                    issues.issue_date, issues.issue_description,issues.sent_to_accra,issues.date_added, issues.date_returned, issues.device_category,
                    system.system_name AS system_name, monitor.monitor_name AS monitor_name,lab.lab_name
                    FROM issues 
                    LEFT JOIN system ON issues.system = system.id 
                    LEFT JOIN monitor ON issues.monitor = monitor.id
                    LEFT JOIN lab ON issues.lab = lab.id 
                    WHERE issues.date_added BETWEEN ? AND ? AND issues.issue_status = '$issue_status'";
            break;

        case 'instructors':
            $sql = "SELECT instructors.id AS instructid,
                           CONCAT(instructors.first_name, ' ', instructors.last_name) AS instructname,
                           instructors.email, instructors.phone, instructors.date_added,
                           course.course_name, lab.lab_name
                    FROM instructors
                    INNER JOIN course ON instructors.course_id = course.id
                    LEFT JOIN lab     ON instructors.lab_id    = lab.id
                    WHERE instructors.date_added BETWEEN ? AND ?";
            break;

        case 'examination':
            if ($usertype != 'admin') {
                $sql = "SELECT examination.*,
                               course.course_name AS course,
                               module.name AS module,
                               CONCAT(instructors.first_name,' ',instructors.last_name) AS instructor_name
                        FROM examination
                        INNER JOIN course      ON examination.course_id    = course.id
                        INNER JOIN module      ON examination.module_id    = module.id
                        INNER JOIN instructors ON examination.instructor_id = instructors.id
                        WHERE examination.examination_date BETWEEN ? AND ?
                        AND examination.instructor_id = $instructorid";
            } else {
                $sql = "SELECT examination.*,
                               course.course_name AS course,
                               module.name AS module,
                               CONCAT(instructors.first_name,' ',instructors.last_name) AS instructor_name
                        FROM examination
                        INNER JOIN course      ON examination.course_id    = course.id
                        INNER JOIN module      ON examination.module_id    = module.id
                        INNER JOIN instructors ON examination.instructor_id = instructors.id
                        WHERE status = 'approve'
                        AND examination.examination_date BETWEEN ? AND ?";
            }
            break;
    }

    // Prepare & run
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $start, $end);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {

        $caption = '';
        $thead   = '';
        $tbody   = '';
        $counter = 1;
        $dateGenerated = date("Y-m-d H:i:s");

        // CAPTION
        if ($table == 'examination' && $usertype == 'admin') {
            $caption  = "<h3 class='text-center mb-2 fw-bold'>IPMC COLLEGE OF TECHNOLOGY</h3>";
            $caption .= "<h4 class='text-center mb-2 fw-bold'>IPMC ADMINISTRATOR - KUMASI BRANCH</h4>";
            $caption .= "<h4 class='text-center mb-3 fw-bold'>FROM: $start TO $end</h4>";
        } else {
            $caption  = "<h5 class='text-center mb-2 text-danger fw-bold'>" . ucfirst($table) . " Report</h5>";
            $caption .= "<p class='text-center text-secondary small'>This Report was Generated on: $dateGenerated</p>";
        }

        // TABLE PROCESSING
        switch ($table) {

            case 'monitor':
                $thead = "<tr>
                    <th>#</th><th>Monitor Name</th><th>Size</th>
                    <th>Serial Number</th><th>Brand</th><th>Lab</th><th>Date Added</th>
                </tr>";

                while ($row = $result->fetch_assoc()) {
                    $tbody .= "<tr>
                        <td>{$counter}</td>
                        <td>{$row['monitor_name']}</td>
                        <td>{$row['size']}</td>
                        <td>{$row['monitor_serial']}</td>
                        <td>{$row['brand_name']}</td>
                        <td>{$row['lab_name']}</td>
                        <td>{$row['date_added']}</td>
                    </tr>";
                    $counter++;
                }
                break;

            case 'system':
                $thead = "<tr>
                    <th>#</th><th>SystemInfo</th><th>Brand</th><th>Series</th>
                    <th>Processor</th><th>Speed</th><th>Generation</th><th>Memory</th>
                    <th>HDD/SSD</th><th>Lab</th><th>Date Added</th>
                </tr>";

                while ($row = $result->fetch_assoc()) {
                    $tbody .= "<tr>
                        <td>{$counter}</td>
                        <td>{$row['serial_number']}</td>
                        <td>{$row['brand_name']}</td>
                        <td>{$row['iseries']}</td>
                        <td>{$row['processor_type']}</td>
                        <td>{$row['speed']}</td>
                        <td>{$row['generation']}</td>
                        <td>{$row['memory_size']}</td>
                        <td>{$row['hard_drive_size']}</td>
                        <td>{$row['lab_name']}</td>
                        <td>{$row['date_added']}</td>
                    </tr>";
                    $counter++;
                }
                break;

            case 'lab':
                $thead = "<tr>
                    <th>#</th><th>Lab Name</th><th>Course</th><th>No. of Computers</th><th>Date Created</th>
                </tr>";

                while ($row = $result->fetch_assoc()) {
                    $tbody .= "<tr>
                        <td>{$counter}</td>
                        <td>{$row['lab_name']}</td>
                        <td>{$row['course_name']}</td>
                        <td>{$row['number_computers']} Computer(s)</td>
                        <td>{$row['date_added']}</td>
                    </tr>";
                    $counter++;
                }
                break;

            case 'issues':
                $thead = "<tr>
                    <th>#</th>
                    <th>Category</th>
                    <th>Device</th>
                    <th>Issue</th>
                    <th>Lab</th>
                    <th>Issue Description</th>
                    <th>Issue Status</th>
                    <th>Resolved Type</th>
                    <th>Date Returned</th>
                    <th>Sent to Accra</th>
                    <th>Date Added</th>
                </tr>";

                while ($row = $result->fetch_assoc()) {
                    $sentToAccra = (!empty($row['sent_to_accra']) && $row['sent_to_accra'] !== '0') ? 'Yes' : 'No';
                    $deviceName = $row['system_name'] ?? $row['monitor_name'] ?? 'N/A';
                    $dateReturned = ($row['date_returned'] ?? 'N/A');

                    $tbody .= "<tr>
                        <td>{$counter}</td>
                        <td>{$row['device_category']}</td>
                        <td>
                            <div>{$deviceName}</div>
                            <div class='text-muted small'>{$row['serial_number']}</div>                
                        </td>
                        <td>{$row['issue_type']}</td>
                        <td>{$row['lab_name']}</td>
                        <td>{$row['issue_description']}</td>
                        <td>{$row['issue_status']}</td>
                        <td>{$row['resolved_type']}</td>
                        <td>{$dateReturned}</td>
                        <td>{$sentToAccra}</td>
                        <td>{$row['date_added']}</td>
                    </tr>";
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
                        <td>{$counter}</td>
                        <td>{$row['instructname']}</td>
                        <td>{$row['phone']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['lab_name']}</td>
                        <td>{$row['course_name']}</td>
                        <td>{$row['date_added']}</td>
                    </tr>";
                    $counter++;
                }
                break;

            case 'users':
                // ❗IMPORTANT: inst_name must come from SQL join (missing in your original query)
                $thead = "<tr>
                    <th>#</th><th>Email</th><th>User Type</th><th>Assigned Instructor</th><th>Date Added</th>
                </tr>";

                while ($row = $result->fetch_assoc()) {
                    $tbody .= "<tr>
                        <td>{$counter}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['user_type']}</td>
                        <td>{$row['inst_name']}</td>
                        <td>{$row['date_added']}</td>
                    </tr>";
                    $counter++;
                }
                break;

            case 'examination':

                if ($usertype != 'admin') {

                    $thead = "<tr>
                        <th>#</th><th>ExamsDate</th><th>Course</th><th>Module</th>
                        <th>BatchTime</th><th>Session</th><th>StartTime</th>
                        <th>Semester</th><th>Instructor</th><th>DateBooked</th>
                    </tr>";

                    while ($row = $result->fetch_assoc()) {
                        $tbody .= "<tr>
                            <td>{$counter}</td>
                            <td>{$row['examination_date']}</td>
                            <td>{$row['course']}</td>
                            <td>{$row['module']}</td>
                            <td>{$row['batch_time']}</td>
                            <td>{$row['session']}</td>
                            <td>{$row['start_time']}</td>
                            <td>{$row['batch_semester']}</td>
                            <td>{$row['instructor_name']}</td>
                            <td>{$row['date_booked']}</td>
                        </tr>";
                        $counter++;
                    }
                } else {

                    $thead = "<tr>
                        <th>#</th><th>DAY</th><th>EXAMDATE</th><th>EXAMTIME</th>
                        <th>MODULE_NAME</th><th>CLASS_LEVEL</th><th>LECTURE_TIME</th>
                        <th>INSTRUCTOR</th><th>SESSION</th>
                    </tr>";

                    while ($row = $result->fetch_assoc()) {

                        // Convert date to day name
                        $exam_date = $row['examination_date'];
                        $dayOfWeek = date("l", strtotime($exam_date));

                        $tbody .= "<tr>
                            <td>{$counter}</td>
                            <td>{$dayOfWeek}</td>
                            <td>{$row['examination_date']}</td>
                            <td>{$row['start_time']}</td>
                            <td>{$row['module']}</td>
                            <td>{$row['course']}</td>
                            <td>{$row['batch_time']}</td>
                            <td>{$row['instructor_name']}</td>
                            <td>{$row['session']}</td>
                        </tr>";
                        $counter++;
                    }
                }
                break;
        }

        echo $caption . "<!--SPLIT-->" . $thead . "<!--SPLIT-->" . $tbody;
    } else {
        echo "<!--SPLIT--><tr><td colspan='10' class='text-center text-danger'>No records found for this date.</td></tr>";
    }

    $stmt->close();
}
$conn->close();
