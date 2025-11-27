<?php

// fetch instructor name
if (!isset($_SESSION['type'])) {
    die("Invalid User!");
}
$usertype = $_SESSION['type'];

// Count approved exams
$statusApproved = 'approve';
$stmt = $conn->prepare("SELECT COUNT(*) AS total_approved FROM examination WHERE status = ?");
$stmt->bind_param("s", $statusApproved);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$totalApproved = $row['total_approved'] ?? 0;

// Count pending exams
$statusPending = 'pending';
$stmt = $conn->prepare("SELECT COUNT(*) AS total_pending FROM examination WHERE status = ?");
$stmt->bind_param("s", $statusPending);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$totalPending = $row['total_pending'] ?? 0;

// Count cancelled exams
$statusCancelled = 'cancelled';
$stmt = $conn->prepare("SELECT COUNT(*) AS total_cancelled FROM examination WHERE status = ?");
$stmt->bind_param("s", $statusCancelled);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$totalCancelled = $row['total_cancelled'] ?? 0;

// Count total computers
$stmt = $conn->prepare("SELECT COUNT(*) AS total_computers FROM computers");
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$totalComputers = $row['total_computers'] ?? 0;

// Count total faulty computers
$stmt = $conn->prepare("SELECT COUNT(*) AS total_issues FROM issues");
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$totalIssues = $row['total_issues'] ?? 0;

// Count total instructors
$stmt = $conn->prepare("SELECT COUNT(*) AS total_instructors FROM instructors");
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$totalInstructors = $row['total_instructors'] ?? 0;

// Count total courses
$stmt = $conn->prepare("SELECT COUNT(*) AS total_courses FROM course");
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$totalCourses = $row['total_courses'] ?? 0;

// Count total replacement computers
$word = 'replace';
$word = "%{$word}%";
$stmt = $conn->prepare("SELECT COUNT(*) AS total_replacement FROM issues WHERE issue_description LIKE ?");
$stmt->bind_param("s", $word);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$totalReplacements = $row['total_replacement'] ?? 0;



// Count total monitoers
$stmt = $conn->prepare("SELECT COUNT(monitor_name) AS total_monitors FROM computers");
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$totalMonitors = $row['total_monitors'] ?? 0;


// Count system
$stmt = $conn->prepare("SELECT COUNT(computer_name) AS total_systems FROM computers");
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$totalSystems  = $row['total_systems'] ?? 0;


// Count computers
$stmt = $conn->prepare("SELECT COUNT(*) AS total_computers FROM computers");
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$totalComputers  = $row['total_computers'] ?? 0;





$stmt->close();
// $conn->close();

?>

<style>
    a {
        text-decoration: none !important;
    }
</style>

<div class="cardBox">
    <!-- first section -->
    <div class="card-toFlex">
        <div class="cardContent">
            <div class="numbers"><?php echo isset($totalApproved) ? $totalApproved : '0'; ?></div>
            <a href="approved_exams.php">
                <div class="cardName" title="View Confirmed Exams">ApprovedExams</div>
            </a>
        </div>
        <div class="iconBx">
            <ion-icon name="checkmark-circle-outline"></ion-icon>
        </div>
    </div>

    <div class="card-toFlex">
        <div class="cardContent">
            <div class="numbers"><?php echo isset($totalCancelled) ? $totalCancelled : '0'; ?></div>
            <a href="cancelled_exams.php">
                <div class="cardName" title="View Cancelled Exams">CancelledExams</div>
            </a>
        </div>
        <div class="iconBx">
            <ion-icon name="close-circle-outline"></ion-icon>
        </div>
    </div>

    <div class="card-toFlex">
        <div class="cardContent">
            <div class="numbers"><?php echo isset($totalPending) ? $totalPending : '0'; ?></div>
            <?php
            // if($usertype == 'admin')
            // {
            ?>
            <!-- <a href="pending_exams.php"> -->
            <?php //} 
            ?>
            <a href="pending_exams.php">
                <div class="cardName" title="View Pending Exams">PendingExams</div>
            </a>
        </div>
        <div class="iconBx">
            <ion-icon name="time-outline"></ion-icon>
        </div>
    </div>

    <div class="card-toFlex">
        <div class="cardContent">
            <div class="numbers"><?php echo isset($totalComputers) ? $totalComputers : '0'; ?></div>
            <?php
            if ($usertype == 'admin') {
            ?>
                <a href="computers.php">
                <?php } else { ?>
                    <a href="#">
                    <?php } ?>
                    <div class="cardName" title="View All Computers">Computers</div>
                    <div class="d-flex gap-5 mt-2">
                        <div class="cardName" style="font-size: 13px;" title="View All Computers">No. Monitors <?php echo $totalMonitors ?></div>
                        <div class="cardName" style="font-size: 13px;" title="View All Computers">No. Computers <?php echo $totalSystems ?></div>
                    </div>
                    </a>
        </div>
        <div class="iconBx">
            <ion-icon name="laptop-outline"></ion-icon>
        </div>
    </div>

    <!-- second section -->
    <div class="card-toFlex">
        <div class="cardContent">
            <div class="numbers"><?php echo isset($totalInstructors) ? $totalInstructors : '0'; ?></div>
            <?php
            if ($usertype == 'admin') {
            ?>
                <a href="instructors.php">
                <?php } else { ?>
                    <a href="#">
                    <?php } ?>
                    <div class="cardName" title="View Instructors">Instructors</div>
                    </a>
        </div>
        <div class="iconBx">
            <ion-icon name="eye-outline"></ion-icon>
        </div>
    </div>

    <div class="card-toFlex">
        <div class="cardContent">
            <div class="numbers"><?php echo isset($totalCourses) ? $totalCourses : '0'; ?></div>
            <?php
            if ($usertype == 'admin') {
            ?>
                <a href="labs.php">
                <?php } else { ?>
                    <a href="#">
                    <?php } ?>
                    <div class="cardName" title="View Courses">Courses</div>
                    </a>
        </div>
        <div class="iconBx">
            <ion-icon name="book-outline"></ion-icon>
        </div>
    </div>

    <div class="card-toFlex">
        <div class="cardContent">
            <div class="numbers"><?php echo isset($totalReplacements) ? $totalReplacements : '0'; ?></div>
            <?php
            if ($usertype == 'admin') {
            ?>
                <a href="issues.php">
                <?php } else { ?>
                    <a href="#">
                    <?php } ?>
                    <div class="cardName" title="View Computers to be Replaced">Replacements</div>
                    </a>
        </div>
        <div class="iconBx">
            <ion-icon name="refresh-outline"></ion-icon>
        </div>
    </div>

    <div class="card-toFlex">
        <div class="cardContent">
            <div class="numbers"><?php echo isset($totalIssues) ? $totalIssues : '0'; ?></div>
            <?php
            if ($usertype == 'admin') {
            ?>
                <a href="issues.php">
                <?php } else { ?>
                    <a href="#">
                    <?php } ?>
                    <div class="cardName" title="View Faulty Computers">Issues</div>
                    </a>
        </div>
        <div class="iconBx">
            <ion-icon name="alert-circle-outline"></ion-icon>
        </div>
    </div>
</div>