<?php
require_once('actions/start_session.php');
require_once('alert.php');
require_once('baseConnect/dbConnect.php');


// initialize variables used in the form when edit btn is called

if (isset($_GET['edit_id']) && is_numeric($_GET['edit_id'])) {
    $edit_id = intval($_GET['edit_id']);
    $stmt = $conn->prepare("SELECT id, issue_type, lab, issue_date, issue_description, `system`, monitor, sent_to_accra, device_category, serial_number, issue_status FROM issues WHERE id = ?");
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    if ($row) {
        $id = $row['id'];
        $system = $row['system'];
        $monitor = $row['monitor'];
        $issue_type = $row['issue_type'];
        $lab = $row['lab'];
        $issue_date = $row['issue_date'];
        $issue_description = $row['issue_description'];
        $sent_to_accra = $row['sent_to_accra'];
        $device_category = $row['device_category'];
        $serial_number = $row['serial_number'];
        $issue_status = $row['issue_status'];
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
        <div class="d-flex justify-content-between ">
            <div class="d-flex justify-content-between align-items-center">
                <h3><ion-icon name="alert-circle-outline"></ion-icon> Issues </h3>
            </div>
            <div class="d-flex align-items-center">
                <button type="submit" form="Form" class="btn text-white px-4" style="background-color:rgb(200, 72, 105)">Save/Update Issue </button>
                <a href="issues_list.php" class="ms-3 btn text-white px-4 bg-success ">View Issues</a>
            </div>
        </div>
        <hr style="margin-bottom: 3rem;">
        <div class="g-3" style="margin-bottom: 7rem">
            <form class="row g-3 border rounded bg-light shadow-sm p-3 pb-5" id="Form" method="POST" action="actions/issues_action.php">
                <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">

                <div class="col-md-4">
                    <label class="form-label">Device Category</label>
                    <select required id="deviceCategory" name="device_category" class="form-select">
                        <option value="">Select Category</option>
                        <option value="system" <?php echo (isset($device_category) && $device_category == 'system') ? 'selected' : '' ?>>System</option>
                        <option value="monitor" <?php echo (isset($device_category) && $device_category == 'monitor') ? 'selected' : '' ?>>Monitor</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Device</label>
                    <select required id="deviceType" name="device_type" class="form-select" disabled>
                        <option value="">Select Device </option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Serial Number</label>
                    <input type="text" id="serialNumber" name="serial_number" class="form-control" value="<?php echo isset($serial_number) ? htmlspecialchars($serial_number) : '' ?>" readonly>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Issue Type</label>
                    <select required id="Type" name="issue_type" class="form-select">
                        <option value="">Select</option>
                        <option value="Software" <?php echo (isset($issue_type) && $issue_type == 'Software') ? 'selected' : '' ?>>Software</option>
                        <option value="Hardware" <?php echo (isset($issue_type) && $issue_type == 'Hardware') ? 'selected' : '' ?>>Hardware</option>
                    </select>
                </div>


                <div class="col-md-4">
                    <label class="form-label">Lab</label>
                    <select required id="labSelect" name="lab_select_ui" class="form-select">
                        <option value="">Choose Lab</option>
                        <?php
                        $query_command = "SELECT * FROM lab ";
                        $result = $conn->query($query_command);
                        ?>
                        <?php while ($row = $result->fetch_assoc()) { ?>
                            <option value="<?php echo $row['id'] ?>" <?php echo (isset($lab) && $lab ==  $row['id']) ? 'selected' : '' ?>><?php echo $row['lab_name'] ?></option>
                        <?php } ?>
                    </select>
                    <input type="hidden" id="labHidden" name="lab" value="<?php echo isset($lab) ? $lab : '' ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Issue Status</label>
                    <select required id="issueStatus" name="issue_status" class="form-select">
                        <option value="Pending" <?php echo (isset($issue_status) && $issue_status == 'Pending') ? 'selected' : '' ?>>Pending</option>
                        <option value="Resolved" <?php echo (isset($issue_status) && $issue_status == 'Resolved') ? 'selected' : '' ?>>Resolved</option>
                    </select>
                </div>

                <div class="col-md-4" id="resolutionTypeDiv" style="display: none;">
                    <label class="form-label">Resolution Type</label>
                    <select id="resolutionType" name="resolved_type	" class="form-select">
                        <option value="">Select</option>
                        <option value="Repaired & Returned">Repaired & Returned</option>
                        <option value="Unrepaired & Replaced">Unrepaired & Replaced</option>
                    </select>
                </div>


                <div class="col-md-4">
                    <label class="form-label">Issue Date</label>
                    <input required type="date" name="issue_date" value="<?php echo isset($issue_date) ? $issue_date  : '' ?>" class="form-control">
                </div>

                <div class="col-md-3 " style="margin-left: 50px; margin-top: 30px">
                    <label class="form-label">Sent to Accra</label>
                    <div class="d-flex gap-4">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="sent_to_accra" id="sentYes" value="1" <?php echo (isset($sent_to_accra) && $sent_to_accra == 1 ? 'checked' : '') ?>>
                            <label class="form-check-label" for="sentYes">Yes</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="sent_to_accra" id="sentNo" value="0" <?php echo (isset($sent_to_accra) && $sent_to_accra == 0 ? 'checked' : '') ?>>
                            <label class="form-check-label" for="sentNo">No</label>
                        </div>
                    </div>
                </div>


                <div class="col-md-12">
                    <label class="form-label">Issue Description</label>
                    <textarea class="form-control" style="resize:none;" rows=5 name="issue_description" id=""><?php echo isset($issue_description) ? $issue_description  : '' ?></textarea>
                </div>



            </form>
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

    <script>
        // Pass PHP variables to JavaScript for edit mode
        <?php if (isset($id)): ?>
        window.editMode = {
            deviceCategory: "<?php echo isset($device_category) ? $device_category : ''; ?>",
            deviceId: "<?php echo isset($system) ? $system : (isset($monitor) ? $monitor : ''); ?>",
            serialNumber: "<?php echo isset($serial_number) ? htmlspecialchars($serial_number) : ''; ?>",
            labId: "<?php echo isset($lab) ? $lab : ''; ?>",
            issueStatus: "<?php echo isset($issue_status) ? $issue_status : ''; ?>"
        };
        <?php else: ?>
        window.editMode = null;
        <?php endif; ?>
    </script>

</body>

</html> 