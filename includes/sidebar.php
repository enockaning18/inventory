<?php
// fetch instructor name
if (!isset($_SESSION['instructorid']) && !isset($_SESSION['type'])) {
    die("Invalid User!");
}

$usertype = $_SESSION['type'];

// Function to set active link
function active($page)
{
    return basename($_SERVER['PHP_SELF']) == $page ? 'active' : '';
}
?>


<div class="navigation" style="overflow-y: scroll;">
    <ul class="m-0 p-0">

        <li style="margin-bottom: 0px;">
            <a href="nav_link.php">
                <img src="assets/imgs/inventory_logo.png" width="100%" alt="logo">
            </a>
        </li>

        <li class="<?php echo active('dashboard.php'); ?>">
            <a href="dashboard.php">
                <span class="icon"><ion-icon name="speedometer-outline"></ion-icon></span>
                <span class="title">Dashboard</span>
            </a>
        </li>

        <!-- DROPDOWN: Examination -->
        <li class="dropdown  <?php echo active('examination.php') || active('exams_list.php') || active('exams_calender.php') ? 'open active' : ''; ?>">
            <a href="#" class="dropdown-toggle">
                <span class="icon"><ion-icon name="book-outline"></ion-icon></span>
                <span class="title">Examination</span>
                <ion-icon name="chevron-down-outline" class="arrow p-3"></ion-icon>
            </a>

            <ul class="dropdown-menu">
                <li class="<?php echo active('examination.php'); ?>">
                    <a href="examination.php">New Examination</a>
                </li>

                <li class="<?php echo active('exams_list.php'); ?>">
                    <a href="exams_list.php">View Exams</a>
                </li>

                <li class="<?php echo active('exams_calender.php'); ?>">
                    <a href="exams_calender.php">Exams Calendar</a>
                </li>

            </ul>
        </li>

        <?php if ($usertype == 'admin') { ?>

            <li class="<?php echo active('labs.php'); ?>">
                <a href="labs.php">
                    <span class="icon"><ion-icon name="home-outline"></ion-icon></span>
                    <span class="title">Labs / Course</span>
                </a>
            </li>

            <li class="<?php echo active('modules.php'); ?>">
                <a href="modules.php">
                    <span class="icon"><ion-icon name="document-outline"></ion-icon></span>
                    <span class="title">Course Modules</span>
                </a>
            </li>

            <li class="<?php echo active('instructors.php'); ?>">
                <a href="instructors.php">
                    <span class="icon"><ion-icon name="school-outline"></ion-icon></span>
                    <span class="title">Instructors</span>
                </a>
            </li>

            <!-- DROPDOWN: COmputer -->
            <li class="dropdown  <?php echo active('system.php') || active('monitors.php') ? 'open active' : ''; ?>">
                <a href="#" class="dropdown-toggle">
                    <span class="icon"><ion-icon name="laptop-outline"></ion-icon></span>
                    <span class="title">Computers</span>
                    <ion-icon name="chevron-down-outline" class="arrow p-3"></ion-icon>
                </a>

                <ul class="dropdown-menu">
                    <li class="<?php echo active('examination.php'); ?>">
                        <a href="system.php">New System Unit</a>
                    </li>
                    <li class="<?php echo active('system_list.php'); ?>">
                        <a href="system_list.php">View Systems </a>
                    </li>

                    <li class="<?php echo active('exams_list.php'); ?>">
                        <a href="monitors.php">New Monitor</a>
                    </li>
                    <li class="<?php echo active('monitors_list.php'); ?>">
                        <a href="monitor_list.php">View Monitors </a>
                    </li>
                </ul>
            </li>
            <li class="<?php echo active('brands.php'); ?>">
                <a href="brands.php">
                    <span class="icon"><ion-icon name="pricetag-outline"></ion-icon></span>
                    <span class="title">Brand</span>
                </a>
            </li>
            <li class="dropdown  <?php echo active('issues.php') || active('issues_list.php') ? 'open active' : ''; ?>">
                <a href="#" class="dropdown-toggle">
                    <span class="icon"><ion-icon name="alert-circle-outline"></ion-icon></span>
                    <span class="title">Manage Issues</span>
                    <ion-icon name="chevron-down-outline" class="arrow p-3"></ion-icon>
                </a>

                <ul class="dropdown-menu">
                    <li class="<?php echo active('issues.php'); ?>">
                        <a href="issues.php">New Issue</a>
                    </li>

                    <li class="<?php echo active('issues_list.php'); ?>">
                        <a href="issues_list.php">View Issues</a>
                    </li>
                </ul>
            </li>

        <?php } ?>

        <li class="<?php echo active('report.php'); ?>">
            <a href="report.php">
                <span class="icon"><ion-icon name="document-text-outline"></ion-icon></span>
                <span class="title">Report</span>
            </a>
        </li>

        <li class="<?php echo active('logout.php'); ?>">
            <a href="logout.php">
                <span class="icon"><ion-icon name="exit-outline"></ion-icon></span>
                <span class="title">Logout</span>
            </a>
        </li>

    </ul>
</div>