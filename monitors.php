<?php
require_once('actions/start_session.php');
require_once('alert.php');
require_once('baseConnect/dbConnect.php');


// initialize variables used in the form when edit btn is called

if (isset($_GET['edit_id']) && is_numeric($_GET['edit_id'])) {
    $edit_id = intval($_GET['edit_id']);
    $stmt = $conn->prepare("SELECT * FROM monitor WHERE id = ?");
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    if ($row) {
        $id = $row['id'];
        $lab = $row['lab'];
        $monitor = $row['monitor_name'];
        $size = $row['size'];
        $monitor_serial = $row['monitor_serial'];
        $brand = $row['brand'];
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Monitors - IPMC INVENTORY MANAGER</title>
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
            <h3 class="my-auto">
                <ion-icon name="laptop-outline"></ion-icon>
                Monitors
            </h3>
            <div>
                <button type="submit" form="Form" class="btn text-white px-4" style="background-color:rgb(200, 72, 105)">Save/Update Monitor</button>

            </div>
        </div>
        <hr style="margin-bottom: 3rem;">
        <div class="g-3" style="margin-bottom: 4rem">

            <form class="row g-3 border rounded bg-light shadow-sm p-3 pb-5" id="Form" method="POST" action="actions/monitor_action.php">
                <!-- monitor info -->
                <div style="display: flex; align-items: center;">
                    <span style="margin-right: 10px; color: maroon;">Monitor Information</span>
                    <hr style="flex: 1; border: 1px solid #000;">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Device Name</label>
                    <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>" class="form-control">
                    <input required type="text" name="monitor_name" value="<?php echo isset($monitor) ? $monitor : '' ?>" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Size</label>
                    <input required type="text" name="size" value="<?php echo isset($size) ? $size : '' ?>" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Serial Number</label>
                    <input required type="text" name="monitor_serial" value="<?php echo isset($monitor_serial) ? $monitor_serial : '' ?>" class="form-control">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Select Brand</label>
                    <?php
                    $query_command = "SELECT * FROM brand ";
                    $result = $conn->query($query_command);
                    ?>
                    <select required id="Type" name="brand" class="form-select">
                        <option value="">Choose Brand</option>
                        <?php while ($row = $result->fetch_assoc()) { ?>
                            <option value="<?php echo $row['id'] ?>" <?php echo (isset($brand) && $brand ==  $row['id']) ? 'selected' : '' ?>><?php echo $row['brand_name'] ?></option>
                        <?php } ?>

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




            </form>
        </div>
    </div>



    <!-- =========== Scripts =========  -->
    <script src="assets/js/main.js"></script>
    <script src="assets/js/jquery.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

    <?php
    $title = "Monitor";
    successAlert($title);
    ?>
</body>

</html>