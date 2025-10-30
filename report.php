<?php
require_once('actions/start_session.php');
require_once('alert.php');
require_once('baseConnect/dbConnect.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Reports - IPMC INVENTORY MANAGER</title>
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

    <div class="mx-auto" style="margin-top: 4rem; width:85%">
        <div class="d-flex justify-content-between align-items-center">
            <h3><ion-icon name="document-text-outline"></ion-icon> Reports</h3>
        </div>
        <hr style="margin-bottom: 3rem;">

        <div class="g-3 mb-5">
            <form class="row g-3 border bg-light shadow-sm p-3 pb-5" id="ReportForm" method="POST" action="actions/generate_report.php">
                <div class="col-md-4">
                    <label class="form-label">Report Type</label>
                    <select id="reportType" name="report_type" class="form-select" required>
                        <option value="">Select Report Type</option>
                        <option value="examination">Examinations</option>
                        <option value="computers">Computers</option>
                        <option value="instructors">Instructors</option>
                        <option value="issues">Issues</option>
                        <option value="lab">Labs</option>                      
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Start Date</label>
                    <input type="date" name="start_date" class="form-control">
                </div>

                <div class="col-md-4">
                    <label class="form-label">End Date</label>
                    <input type="date" name="end_date" class="form-control">
                </div>
                <div class="d-flex justify-content-between">
                    <div class="my-2">
                        <button type="submit" name="generate_report" class="btn btn-danger px-4">Generate Report</button>
                    </div>
                    <div class="my-2">
                        <a href="#" id="exportExcel" class="text-decoration-none">
                            <i class="bi bi-file-earmark-spreadsheet-fill fs-3 text-success" title="Export to Excel"></i>
                            Export Report | &nbsp;
                        </a>
                        <a href="#" id="printReport" class="text-decoration-none">
                            <i class="bi bi-printer-fill fs-3 btn-print" title="Print Generated Report  "></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <div class="card shadow">
            <div class="card-body">
                <h5 class="text-center text-secondary">Generated Report Print Preview</h5>
                <hr>
                <div class="table-responsive">
                    <table class="table table-striped align-middle reportTable">
                        <caption id="reportCaption"></caption>
                        <thead id="reportHead"></thead>
                        <tbody id="reportTable"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/main.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

    <!-- export report to excel -->
    <?php   include('actions/export_xslx.php'); ?>

    <script>
        $(document).ready(function() {
            $('#ReportForm').on('submit', function(e) {
                e.preventDefault();

                // Add manual key to emulate submit button name
                let formData = $(this).serialize() + '&generate_report=true';

                $.ajax({
                    url: 'actions/system_report_action.php',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        const parts = response.split('<!--SPLIT-->');
                        $('#reportCaption').html(parts[0]);
                        $('#reportHead').html(parts[1]);
                        $('#reportTable').html(parts[2]);
                    },

                    error: function(xhr, status, error) {
                        console.error("Error:", status, error, xhr.responseText);
                        alert('Error generating report. Check console for details.');
                    }
                });
            });
        });
    </script>

    <script>
        document.getElementById('printReport').addEventListener('click', function() {
            let printCaption = document.getElementById('reportCaption').innerHTML;
            let printHead = document.getElementById('reportHead').innerHTML;
            let printContent = document.getElementById('reportTable').innerHTML;
            let originalContent = document.body.innerHTML;

            document.body.innerHTML = `
        <html>
        <head>
            <title>Print Report</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        </head>
        <body class="p-4 mt-5">
            <h3 class="text-center mb-4">${printCaption}</h3>
            <table class="table  table-striped">
                <thead> ${printHead} </thead>
                <tbody> ${printContent} </tbody>
            </table>
        </body>
        </html>
    `;

            window.print();
            document.body.innerHTML = originalContent;
            location.reload();
        });
    </script>

</body>

</html>