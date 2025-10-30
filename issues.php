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
            <h3><ion-icon name="alert-circle-outline"></ion-icon> Issues </h3>
            <button type="submit" form="Form" class="btn text-white px-4" style="background-color:rgb(200, 72, 105)">Save/Update Issue </button>
        </div>
        <hr style="margin-bottom: 3rem;">
        <div class="g-3" style="margin-bottom: 7rem">
            <form class="row g-3 border rounded bg-light shadow-sm p-3 pb-5" id="Form" method="POST" action="actions/issues_action.php">
                <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">

                <div class="col-md-4">
                    <label class="form-label">Computer</label>
                    <select required id="Type" name="computers" class="form-select">
                        <option value="">Select</option>
                        <?php
                        $query_command = "SELECT * FROM computers ";
                        $result = $conn->query($query_command);
                        ?>
                        <?php while ($row = $result->fetch_assoc()) { ?>
                            <option value="<?php echo $row['id'] ?>" <?php echo (isset($computer) && $computer ==  $row['id']) ? 'selected' : '' ?>><?php echo $row['computer_name'] ?></option>
                        <?php } ?>
                    </select>
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
                    <select required id="Type" name="lab" class="form-select">
                        <option value="">Choose Lab</option>
                        <?php
                        $query_command = "SELECT * FROM lab ";
                        $result = $conn->query($query_command);
                        ?>
                        <?php while ($row = $result->fetch_assoc()) { ?>
                            <option value="<?php echo $row['id'] ?>" <?php echo (isset($lab) && $lab ==  $row['id']) ? 'selected' : '' ?>><?php echo $row['lab_name'] ?></option>
                        <?php } ?>
                    </select>
                </div>


                <div class="col-md-4">
                    <label class="form-label">Issue Date</label>
                    <input required type="date" name="issue_date" value="<?php echo isset($issue_date) ? $issue_date  : '' ?>" class="form-control">
                </div>

                <div class="col-md-8">
                    <label class="form-label">Issue Description</label>
                    <textarea class="form-control" name="issue_description" id=""><?php echo isset($issue_description) ? $issue_description  : '' ?></textarea>
                </div>

            </form>
        </div>
    </div>

    <div class=" mt-5 mx-auto" style="width: 95%">
        <div class="row">
            <div class="col">
                <div class="card shadow">
                    <div class="card-header d-flex justify-content-between align-items-center border-0 px-4 py-3">
                        <h5 class="mb-0" style="color: maroon;">List of Issues </h5>
                        <form id="filterForm" class="d-flex gap-2">
                            <input type="search" class="form-control px-4" id="searchBox" name="search" placeholder="Search..">

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
                    <div class="table-responsive" style="height: 300px">
                        <table class="table table-striped align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Compuer</th>
                                    <th>Issue </th>
                                    <th>Lab</th>
                                    <th>Issue Date </th>
                                    <th>Issue Description</th>
                                    <th>Issue Date</th>
                                    <th>Date Added</th>
                                    <th>Action</th>
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
        });
    </script>


    <?php
    $title = "Issue ";
    successAlert($title);
    ?>
</body>

</html>