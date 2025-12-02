<?php
require_once('actions/start_session.php');
require_once('alert.php');
require_once('baseConnect/dbConnect.php');


// initialize variables used in the form when edit btn is called

if (isset($_GET['edit_id']) && is_numeric($_GET['edit_id'])) {
    $edit_id = intval($_GET['edit_id']);
    $stmt = $conn->prepare("SELECT id, issue_type, lab, issue_date, issue_description, computer, sent_to_accra, device_category, serial_number, issue_status FROM issues WHERE id = ?");
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
        <div class="d-flex justify-content-between align-items-center">
            <h3><ion-icon name="alert-circle-outline"></ion-icon> Issues </h3>
            <button type="submit" form="Form" class="btn text-white px-4" style="background-color:rgb(200, 72, 105)">Save/Update Issue </button>
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
                    <select required id="deviceType" name="system" class="form-select" disabled>
                        <option value="">Select Device Category </option>
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

                <div class="col-md-7" id="resolutionTypeDiv" style="display: none;">
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

                <div class="col-md-4 " style="margin-left: 50px; margin-top: 30px">
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
                    <textarea class="form-control" cols='5' name="issue_description" id=""><?php echo isset($issue_description) ? $issue_description  : '' ?></textarea>
                </div>



            </form>
        </div>
    </div>


    <!-- =========== Scripts =========  -->
    <script src="assets/js/main.js"></script>
    <script src="assets/js/jquery.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script>
        $(document).ready(function() {
            // Function to load issues
            function load_issues(search = '', issue_type = '', lab_type = '') {
                $.ajax({
                    url: "actions/fetch_issue.php",
                    type: "POST",
                    data: {
                        search: search,
                        issue_type: issue_type,
                        lab_type: lab_type
                    },
                    success: function(data) {
                        $("#issues_table").html(data);
                    }
                });
            }

            // Load all on start
            load_issues();

            // Search input event by search type
            $("#searchBox").on("keyup", function() {
                const search = $(this).val();
                const issue_type = $("#issue_type").val();
                const lab_type = $("#lab_type").val();
                load_issues(search, issue_type, lab_type);
            });

            // Filter dropdown change event by issue_type
            $("#issue_type").on("change", function() {
                const issue_type = $(this).val();
                const search = $("#searchBox").val();
                const lab_type = $("#lab_type").val();
                load_issues(search, issue_type, lab_type);
            });
            // Filter dropdown change event by lab type
            $("#lab_type").on("change", function() {
                const issue_type = $("#issue_type").val();
                const search = $("#searchBox").val();
                const lab_type = $(this).val();
                load_issues(search, issue_type, lab_type);
            });

            // Show/hide resolution type dropdown based on issue status
            $("#issueStatus").on("change", function() {
                if ($(this).val() === "Resolved") {
                    $("#resolutionTypeDiv").show();
                    $("#resolutionType").prop("required", true);
                } else {
                    $("#resolutionTypeDiv").hide();
                    $("#resolutionType").prop("required", false);
                    $("#resolutionType").val("");
                }
            });

            // Fetch devices based on category selection
            $("#deviceCategory").on("change", function() {
                const category = $(this).val();
                const deviceTypeSelect = $("#deviceType");
                const selectedDeviceId = "<?php echo isset($computer) ? $computer : '' ?>";

                if (category) {
                    $.ajax({
                        url: "actions/fetch_devices_by_category.php",
                        type: "POST",
                        dataType: "json",
                        data: {
                            category: category
                        },
                        success: function(data) {
                            deviceTypeSelect.html('<option value="">Select Device</option>');
                            if (data.length > 0) {
                                data.forEach(function(device) {
                                    const isSelected = selectedDeviceId && device.id == selectedDeviceId ? 'selected' : '';
                                    deviceTypeSelect.append(
                                        '<option value="' + device.id + '" ' + isSelected + '>' +
                                        device.name +
                                        '</option>'
                                    );
                                });
                                deviceTypeSelect.prop("disabled", false);

                                // If editing, trigger device change to populate serial and lab
                                if (selectedDeviceId) {
                                    deviceTypeSelect.trigger("change");
                                }
                            } else {
                                deviceTypeSelect.html('<option value="">No devices found</option>');
                                deviceTypeSelect.prop("disabled", true);
                            }
                        },
                        error: function() {
                            deviceTypeSelect.html('<option value="">Error loading devices</option>');
                            deviceTypeSelect.prop("disabled", true);
                        }
                    });
                } else {
                    deviceTypeSelect.html('<option value="">Select Device Category First</option>');
                    deviceTypeSelect.prop("disabled", true);
                    $("#serialNumber").val("");
                    $("#labSelect").prop("disabled", false);
                    $("#labHidden").val("");
                }
            });

            // Fetch serial number and lab when device type is selected
            $("#deviceType").on("change", function() {
                const deviceId = $(this).val();
                const category = $("#deviceCategory").val();

                if (deviceId && category) {
                    $.ajax({
                        url: "actions/fetch_device_serial.php",
                        type: "POST",
                        dataType: "json",
                        data: {
                            device_id: deviceId,
                            category: category
                        },
                        success: function(data) {
                            // set serial
                            $("#serialNumber").val(data.serial_number || "");

                            // if server returned a lab id, set hidden input and select, then disable UI select
                            if (data.lab_id) {
                                $("#labSelect").val(data.lab_id);
                                $("#labHidden").val(data.lab_id);
                                $("#labSelect").prop("disabled", true);
                            } else {
                                // no lab found for device: allow user to choose
                                $("#labSelect").prop("disabled", false);
                                $("#labHidden").val("");
                            }
                        },
                        error: function() {
                            $("#serialNumber").val("");
                            $("#labSelect").prop("disabled", false);
                            $("#labHidden").val("");
                        }
                    });
                } else {
                    // no device chosen: enable lab selection and clear serial & hidden lab
                    $("#serialNumber").val("");
                    $("#labSelect").prop("disabled", false);
                    $("#labHidden").val("");
                }
            });

            // If a device is already selected on load (edit flow), trigger change to populate lab & serial
            if ($("#deviceType").val()) {
                $("#deviceType").trigger("change");
            }

            // If device category is already selected on load (edit flow), trigger change to populate devices
            if ($("#deviceCategory").val()) {
                $("#deviceCategory").trigger("change");
            }

            // If user manually changes lab (when enabled), keep hidden input in sync
            $("#labSelect").on("change", function() {
                if (!$(this).prop("disabled")) {
                    $("#labHidden").val($(this).val());
                }
            });

            // Handle issue status modal
            $('#issueModal').on('show.bs.modal', function(e) {
                const issueId = $(e.relatedTarget).data('issue-id');
                $("#modalIssueId").val(issueId);
                $.ajax({
                    url: "actions/fetch_issue_details.php",
                    type: "POST",
                    data: {
                        issue_id: issueId
                    },
                    success: function(data) {
                        const issue = JSON.parse(data);
                        $("#dateReceived").val(issue.date_added);
                        $("#dateReturned").val(issue.date_returned);
                        $("#modalIssueStatus").val(issue.issue_status || '');
                        $("#modalResolutionType").val(issue.resolved_type || '');

                        // Show/hide resolution type based on status
                        if (issue.issue_status === 'Resolved') {
                            $("#modalResolutionTypeDiv").show();
                        } else {
                            $("#modalResolutionTypeDiv").hide();
                        }
                    },
                    error: function() {
                        alert("Error loading issue details");
                    }
                });
            });

            // Show/hide resolution type in modal based on status change
            $("#modalIssueStatus").on("change", function() {
                if ($(this).val() === "Resolved") {
                    $("#modalResolutionTypeDiv").show();
                    $("#modalResolutionType").prop("required", true);
                } else {
                    $("#modalResolutionTypeDiv").hide();
                    $("#modalResolutionType").prop("required", false);
                    $("#modalResolutionType").val("");
                }
            });
        });
    </script>


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