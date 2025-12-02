<?php
require_once('actions/start_session.php');
require_once('alert.php');
require_once('baseConnect/dbConnect.php');


// initialize variables used in the form when edit btn is called

if (isset($_GET['edit_id']) && is_numeric($_GET['edit_id'])) {
    $edit_id = intval($_GET['edit_id']);
    $stmt = $conn->prepare("SELECT id, issue_type, lab, issue_date, issue_description, computer FROM issues WHERE id = ?");
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    if ($row) {
        $id = $row['id'];
        $computer = $row['computer'];
        $issue_type = $row['issue_type'];
        $lab = $row['lab'];
        $issue_date = $row['issue_date'];
        $issue_description = $row['issue_description'];
    }
    $stmt->close();
} ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Issues - IPMC INVENTORY MANAGER</title>
    <link rel="icon" type="image/ico" href="assets/imgs/inventory_logo.png" />

    <link rel="stylesheet" href="assets/css/style.css">
    <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap-icons.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Exo+2&family=Montserrat&family=Raleway&family=Roboto&display=swap" rel="stylesheet" />
    <script src="assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="assets/js/sweetalert.min.js"></script>
</head>

<body>

<?php
    require("includes/sidebar.php");
    require("includes/topbar.php");
    ?>
    <div class=" mx-auto" style="margin-top: 4rem; width:85%">
        <div class="d-flex justify-content-between align-items-center">
            <h3><ion-icon name="alert-circle-outline"></ion-icon> Issues List</h3>
            <a href="issues.php"><button class="btn text-white px-4" style="background-color:rgb(200, 72, 105)">New Issue </button></a>
        </div>
        <hr style="margin-bottom: 3rem;">
    </div>

    <div class=" mt-5 mx-auto" style="width: 95%">
        <div class="row">
            <div class="col">
                <div class="card shadow">
                    <div class="card-header d-flex justify-content-between align-items-center border-0 px-4 py-3">
                        <h5 class="mb-0" style="color: maroon;">List of Issues </h5>
                        <form id="filterForm" class="d-flex gap-2">
                            <input type="search" class="form-control" style="padding-right: 150px" id="searchBox" name="search" placeholder="Search..">

                            <select name="issue_type" id="issue_type" class="form-select">
                                <option value="">All Issues </option>
                                <option value="Software">Software </option>
                                <option value="Hardware">Hardware </option>
                            </select>

                            <select name="lab_type" id="lab_type" class="form-select">
                                <option value="">All Labs </option>
                                <?php
                                $query_command = "SELECT * FROM lab ";
                                $result = $conn->query($query_command);
                                ?>
                                <?php while ($lab_result = $result->fetch_assoc()) { ?>
                                    <option value="<?php echo $lab_result['lab_name'] ?>"><?php echo $lab_result['lab_name'] ?></option>
                                <?php } ?>
                            </select>
                        </form>
                    </div>
                    <div class="table-responsive" style="overflow-x: auto; height: 300px;">
                        <table class="table table-striped align-middle" style="min-width: 1200px;">
                            <thead class="table-light">
                                <tr>
                                    <th style="white-space: nowrap;">#</th>
                                    <th style="white-space: nowrap;">Category</th>
                                    <th style="white-space: nowrap;">Device</th>
                                    <th style="white-space: nowrap;">Issue</th>
                                    <th style="white-space: nowrap;">Lab</th>
                                    <th style="white-space: nowrap;">Issue Date</th>
                                    <th style="white-space: nowrap;">Issue Description</th>
                                    <th style="white-space: nowrap;">Issue Date</th>
                                    <th style="white-space: nowrap;">Issue Status</th>
                                    <th style="white-space: nowrap;">Resolved Type</th>
                                    <th style="white-space: nowrap;">Date Returned</th>
                                    <th style="white-space: nowrap;">Sent to Accra</th>
                                    <th style="white-space: nowrap;">Date Added</th>
                                    <th style="white-space: nowrap;">Action</th>
                                </tr>
                            </thead>
                            <tbody id="issues_table">
                                <!-- fetch the data using the ajax -->
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <nav>
                            <ul class="pagination justify-content-center mb-0">
                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- =========== Scripts =========  -->
    <script src="assets/js/main.js"></script>
    <script src="assets/js/jquery.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>



    <?php
    $title = "Issue ";
    successAlert($title);
    ?>

    <!-- Issue Status Modal -->
    <div class="modal fade" id="issueModal" tabindex="-1" aria-labelledby="issueModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="issueModalLabel">Issue Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="issueStatusForm" method="POST" action="actions/update_issue_status.php">
                    <div class="modal-body">
                        <input type="hidden" id="modalIssueId" name="issue_id">

                        <div class="mb-3">
                            <label class="form-label"><strong>Date Returned:</strong></label>
                            <input type="date" id="dateReturned" name="date_returned" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label"><strong>Issue Status</strong></label>
                            <select id="modalIssueStatus" name="issue_status" class="form-select" required>
                                <option value="">Select</option>
                                <option value="Pending">Pending</option>
                                <option value="Resolved">Resolved</option>
                            </select>
                        </div>

                        <div class="mb-3" id="modalResolutionTypeDiv" style="display: none;">
                            <label class="form-label"><strong>Resolution Type</strong></label>
                            <select id="modalResolutionType" name="resolved_type" class="form-select">
                                <option value="">Select</option>
                                <option value="Repaired & Returned">Repaired & Returned</option>
                                <option value="Unrepaired & Replaced">Unrepaired & Replaced</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update Status</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>

</html>