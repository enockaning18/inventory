<?php 

    if (!$conn || $conn->connect_error) {
        die("Database connection failed: " . $conn->connect_error);
    }

    // setting time zone
    date_default_timezone_set('UTC'); // or your correct timezone

    // upcoming exams
    $e_sql = "SELECT 
                examination.*,
                course.course_name AS course, 
                module.name AS module, 
                CONCAT(instructors.first_name, ' ', instructors.last_name) AS instructor_name
            FROM examination
            INNER JOIN course ON examination.course_id = course.id
            INNER JOIN module ON examination.module_id = module.id
            INNER JOIN instructors ON examination.instructor_id = instructors.id
            WHERE status = 'approve' AND examination_date BETWEEN CURDATE() 
            AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)
            ORDER BY examination_date DESC";

    $e_result = $conn->query($e_sql);
?>

<!-- ================ Order Details List ================= -->
<div class="details">
    <div class="recentOrders" style="overflow-x: auto;">
        <div class="cardHeader">
            <h2>Upcoming Exams</h2>
            <a href="approved_exams.php" class="btn">View All</a>
        </div>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <td>Course</td>
                    <td>Module</td>
                    <td>Instructor</td>
                    <td>DateTime</td>
                    <td>Status</td>
                </tr>
            </thead>
        <tbody>
        <?php
            if ($e_result && $result->num_rows > 0) {
                while ($row = $e_result->fetch_assoc()) {
                    ?>
                <tr>
                    <td class="text-capitalize"> <?php echo $row['course']; ?></td>
                    <td class="text-capitalize"><?php echo $row['module']; ?></td>
                    <td class="text-capitalize"><?php echo $row['instructor_name']; ?></td>
                    <td class="text-capitalize"><?php echo $row['examination_date'].' '.$row['batch_time']; ?></td>
                    <td><span class="status delivered"><?php echo $row['status']; ?></span></td>
                </tr>
            <?php
                }
            }
            ?>
            </tbody>
        </table>
    </div>

    <!-- ================= computer ================ -->
    <div class="recentCustomers" style="height: 400px;">
        <div class="cardHeader">
            <h2>Computers Info</h2>
        </div>

        <table>
        <?php
            // computers info
            $sql = "SELECT * FROM `system` ORDER BY id DESC";
            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    ?>
            <tr>
                <td width="60px">
                    <ion-icon name="desktop-outline" class="fs-3"></ion-icon>
                </td>
                <td>
                    <h4 class="text-primary"><?php echo $row['system_name']; ?> <span><?php echo 'RAM:'.$row['memory_size'].'GB'; ?></span></h4>
                    <h4><span><?php echo 'HDD: '.$row['hard_drive_size'].'GB  | '; ?></span><span class="text-danger"> <?php echo $row['serial_number']; ?></span></h4>
                <?php
                    }
                } 
                else {
                    echo "No Computer Info";
                }
                ?>
            </td>
        </tr>
    </table>
</div>
</div>
</div>
</div>