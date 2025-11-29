<?php

require_once('actions/start_session.php');
require_once('alert.php');
require_once('baseConnect/dbConnect.php');


// initialize variables used in the form when edit btn is called

if (isset($_GET['edit_id']) && is_numeric($_GET['edit_id'])) {
    $edit_id = intval($_GET['edit_id']);
    $stmt = $conn->prepare("SELECT id, computer_name, brand, serial_number, memory_size, hard_drive_size, lab, 
                            monitor_name, size, monitor_serial, processor, generation, speed, processor_type, monitor_brand FROM computers WHERE id = ?");
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    if ($row) {
        $id = $row['id'];
        $computer_name = $row['computer_name'];
        $brand  = $row['brand'];
        $serial_number = $row['serial_number'];
        $memory_size = $row['memory_size'];
        $hard_drive_size = $row['hard_drive_size'];
        $lab = $row['lab'];
        $monitor = $row['monitor_name'];
        $size = $row['size'];
        $monitor_serial = $row['monitor_serial'];
        $processor = $row['processor'];
        $generation = $row['generation'];
        $speed = $row['speed'];
        $processor_type = $row['processor_type'];
        $monitor_brand = $row['monitor_brand'];
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Computers - IPMC INVENTORY MANAGER</title>
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
                Computers
            </h3>
            <div>
                <button type="submit" form="Form" class="btn text-white px-4" style="background-color:rgb(200, 72, 105)">Save/Update System</button>
                <a href="monitors.php" class="btn text-white px-4 bg-success">Add Monitor</a>
            </div>
        </div>
        <hr style="margin-bottom: 3rem;">
        <div class="g-3" style="margin-bottom: 4rem">
            <form class="row g-3 border rounded bg-light shadow-sm p-3 pb-5" id="Form" method="POST" action="actions/computer_action.php">
                <!-- system info -->
                <div style="display: flex; align-items: center;">
                    <span style="margin-right: 10px; color: maroon;">System Information</span>
                    <hr style="flex: 1; border: 1px solid #000;">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Device Name/Model</label>
                    <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>" class="form-control">
                    <input required type="text" name="system_name" value="<?php echo isset($computer_name) ? $computer_name : '' ?>" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">System Brand</label>
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
                    <label class="form-label">Serial Number</label>
                    <input required type="text" name="serial_number" value="<?php echo isset($serial_number) ? $serial_number : '' ?>" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Memory Size</label>
                    <input required type="number" name="memory_size" value="<?php echo isset($memory_size) ? $memory_size : '' ?>" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">HDD/SSD Size</label>
                    <input required type="text" name="hard_drive_size" value="<?php echo isset($hard_drive_size) ? $hard_drive_size : '' ?>" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Device Series</label>
                    <select name="iseries" id="" class="form-select">
                        <option value="">Select</option>
                        <option value="i3">i3</option>
                        <option value="i5">i5</option>
                        <option value="i7">i7</option>
                        <option value="i9">i9</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Generation </label>
                    <input required type="text" name="generation" value="<?php echo isset($generation) ? $generation : '' ?>" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Speed GHz</label>
                    <input required type="text" name="speed" value="<?php echo isset($speed) ? $speed : '' ?>" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Processor</label>
                    <input required type="text" name="processor_type" value="<?php echo isset($processor_type) ? $processor_type : '' ?>" class="form-control">
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
    $title = "Computer";
    successAlert($title);
    ?>
</body>

</html>