<?php
require_once('actions/start_session.php');
require_once('alert.php');
require_once('baseConnect/dbConnect.php');

// Initialize form variables
$id = $email = $userkey = $usertype = $instructor_id = "";

// Load existing user for editing
if (isset($_GET['edit_id']) && is_numeric($_GET['edit_id'])) {
    $edit_id = intval($_GET['edit_id']);
    $stmt = $conn->prepare("SELECT id, email, user_type, user_key, instructor_id FROM users WHERE id = ?");
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $id = $row['id'];
        $email = $row['email'];
        $userkey = $row['user_key'];
        $usertype = $row['user_type'];
        $instructor_id = $row['instructor_id'];
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Users - IPMC INVENTORY MANAGER</title>
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
            <h3><ion-icon name="people-circle-outline"></ion-icon> System Users</h3>
            <button type="submit" form="Form" class="btn text-white px-4" style="background-color:rgb(200, 72, 105)">Save / Update User</button>
        </div>
        <hr style="margin-bottom: 3rem;">

        <div class="g-3" style="margin-bottom: 5rem">
            <form class="row g-3 border rounded bg-light shadow-sm p-3 pb-5" id="Form" method="POST" action="actions/user_action.php">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">

                <div class="col-md-4">
                    <label class="form-label">Email</label>
                    <input required type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" class="form-control" autocomplete="off">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Password</label>
                    <input required type="password" name="userkey" id="userkey" value="<?php echo htmlspecialchars($userkey); ?>" class="form-control" autocomplete="off">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Usertype</label>
                    <select required name="usertype" id="usertype" class="form-select" onchange="setUserTypeText()">
                        <option value="">Choose usertype</option>
                        <option value="admin" <?php echo ($usertype == "admin") ? 'selected' : ''; ?>>Admin</option>
                        <option value="instructor" <?php echo ($usertype == "instructor") ? 'selected' : ''; ?>>Instructor</option>
                        <option value="student" <?php echo ($usertype == "student") ? 'selected' : ''; ?>>Student</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Assign Instructor</label>
                    <?php
                    $query_command = "SELECT id, first_name, last_name FROM instructors";
                    $result = $conn->query($query_command);
                    ?>
                    <select required name="instructor_id" class="form-select">
                        <option value="">Select instructor</option>
                        <?php while ($inst = $result->fetch_assoc()) { ?>
                            <option value="<?php echo $inst['id']; ?>" <?php echo ($instructor_id == $inst['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($inst['first_name'] . ' ' . $inst['last_name']); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
            </form>
        </div>
    </div>

    <div class="mt-5 mx-auto" style="width: 95%">
        <div class="row">
            <div class="col">
                <div class="card shadow">
                    <div class="card-header d-flex justify-content-between align-items-center border-0 px-4 py-3">
                        <h5 class="mb-0" style="color: maroon;">List of System Users</h5>
                        <form id="filterForm" class="d-flex gap-2">
                            <input type="search" class="form-control px-4" id="searchBox" name="search" placeholder="Search..">
                        </form>
                    </div>

                    <div class="table-responsive" style="height: 300px">
                        <table class="table table-striped align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Email</th>
                                    <th>Usertype</th>
                                    <th>Assigned Instructor</th>
                                    <th>DateCreated</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="users_table">
                                <!-- AJAX-loaded users -->
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

    <!-- Scripts -->
    <script src="assets/js/set_default_password.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/jquery.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

    <script>
        $(document).ready(function() {
            function load_users(search = '') {
                $.ajax({
                    url: "actions/fetch_user.php",
                    type: "POST",
                    data: { search: search },
                    success: function(data) {
                        $("#users_table").html(data);
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error: " + error);
                    }
                });
            }

            load_users();

            $("#searchBox").on("keyup", function() {
                load_users($(this).val());
            });
        });
    </script>

    <?php
    $title = "Users";
    successAlert($title);
    ?>
</body>
</html>
