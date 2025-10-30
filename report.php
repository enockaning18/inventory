<?php
require_once('actions/start_session.php');
require_once('alert.php');

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
    <div class=" mx-auto" style="margin-top: 4rem; width:85%">
        <div class="d-flex justify-content-between align-items-center">
            <h3><ion-icon name="document-text-outline"></ion-icon> Report</h3>
        </div>
        <hr style="margin-bottom: 3rem;">
        <div class="g-3" style="margin-bottom: 7rem">
            <form class="row g-3 border rounded bg-light shadow-sm p-3 pb-5" id="Form" method="POST" action="">

                <div class="col-md-4">
                    <label class="form-label">Report Type</label>
                    <select required id="Type" name="_type" class="form-select">
                        <option value="">Choose Report Type</option>
                        <option value=""> </option>
                    </select>
                </div>
                
                <div class="col-md-4">
                    <label class="form-label">Start Date</label>
                    <input required type="date" name="" value="" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">End Date</label>
                    <input required type="date" name="" value="" class="form-control">
                </div>
                
                <button type="submit" form="Form" class="btn btn-primary text-white mt-4 ms-2 px-4 col-4" >Generate Report</button>
            </form>
        </div>
    </div>

    <!-- script files inclusion -->
    <script src="assets/js/main.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

    <script>
        // show/hide filed based on type selected
        document.getElementById("Type").addEventListener("change", function() {
            const Field = document.getElementById("Field");
            Field.style.display = this.value === "none" ? "none" : "block";
        });

        $(document).ready(function() {
            function loads(search = '', reporttype = '') {
                $.ajax({
                    url: "actions/",
                    type: "POST",
                    data: {
                        search: search,
                        reporttype: reporttype
                    },
                    success: function(data) {
                        $("#Table").html(data);
                    }
                });
            }

            // on page load, fetch data
            loads();

            // search data
            $("#searchBox").on("keyup", function() {
                let search = $(this).val();
                let reporttype = $("#reporttype").val();
                loads(search, reporttype);
            });

            // filter data
            $("#reporttype").on("change", function() {
                let search = $("#searchBox").val();
                let reporttype = $(this).val();
                loads(search, reporttype);
            });
        });
    </script>
</body>

</html>