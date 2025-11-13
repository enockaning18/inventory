<?php
    // fetch instructor name
    if (!isset($_SESSION['instructorid']) && !isset($_SESSION['type'])) {
        die("Invalid User!");
    }
    $usertype = $_SESSION['type'];
?>
    
    <div class="navigation" style="overflow-y: scroll;">
        <ul class="m-0 p-0">
            <li style="margin-bottom: 0px;">
                <a href="nav_link.php">
                    <img src="assets/imgs/inventory_logo.png" width="100%"  alt="logo">
                </a>
            </li>

            <li>
                <a href="dashboard.php">
                    <span class="icon">
                        <ion-icon name="speedometer-outline"></ion-icon>
                    </span>
                    <span class="title">Dashboard</span>
                </a>
            </li>

            <li>
                <a href="examination.php">
                    <span class="icon">
                        <ion-icon name="book-outline"></ion-icon>
                    </span>
                    <span class="title">Examination</span>
                </a>
            </li>
            <li>
                <a href="exams_list.php">
                    <span class="icon">
                        <ion-icon name="eye"></ion-icon>
                    </span>
                    <span class="title">View Exams</span>
                </a>
            </li>
            <li>
                <a href="exams_calender.php">
                    <span class="icon">
                        <ion-icon name="calendar"></ion-icon>
                    </span>
                    <span class="title">Exams Calender</span>
                </a>
            </li>
        <?php
            if($usertype == 'admin')
            {
            ?>
            <li>
                <a href="labs.php">
                    <span class="icon">
                        <ion-icon name="home-outline"></ion-icon>
                    </span>
                    <span class="title">Labs / Course </span>
                </a>
            </li>

            <li>
                <a href="modules.php">
                    <span class="icon">
                        <ion-icon name="document-outline"></ion-icon>
                    </span>
                    <span class="title">Course Modules </span>
                </a>
            </li>

            <li>
                <a href="instructors.php">
                    <span class="icon">
                        <ion-icon name="school-outline"></ion-icon>
                    </span>
                    <span class="title">Instructors</span>
                </a>
            </li>

            <li>
                <a href="computers.php">
                    <span class="icon">
                        <ion-icon name="laptop-outline"></ion-icon>
                    </span>
                    <span class="title">Computers </span>
                </a>
            </li>
	    <li>
                <a href="computers_list.php">
                    <span class="icon">
                        <ion-icon name="eye"></ion-icon>
                    </span>
                    <span class="title">View PCs</span>
                </a>
            </li>


            <li>
                <a href="brands.php">
                    <span class="icon">
                        <ion-icon name="pricetag-outline"></ion-icon>
                    </span>
                    <span class="title">Brand</span>
                </a>
            </li>
            <li>
                <a href="issues.php">
                    <span class="icon">
                        <ion-icon name="alert-circle-outline"></ion-icon>
                    </span>
                    <span class="title">Issues</span>
                </a>
            </li>
        <?php   }   ?>

            <li>
                <a href="report.php">
                    <span class="icon">
                        <ion-icon name="document-text-outline"></ion-icon>
                    </span>
                    <span class="title">Report</span>
                </a>
            </li>
            <li>
                <a href="logout.php">
                    <span class="icon">
                        <ion-icon name="exit-outline"></ion-icon>
                    </span>
                    <span class="title">Logout</span>
                </a>
            </li>
        </ul>
    </div>