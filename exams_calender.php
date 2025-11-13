<?php
require_once('actions/start_session.php');
require_once('alert.php');
require_once('baseConnect/dbConnect.php');

// Ensure instructor session is valid
if (empty($_SESSION['instructorid']) || empty($_SESSION['type'])) {
    die("Instructor not logged in.");
}

$instid = $_SESSION['instructorid'];
$usertype = $_SESSION['type'];

// Fetch instructor info
$stmt = $conn->prepare("SELECT first_name, last_name FROM instructors WHERE id = ?");
$stmt->bind_param("i", $instid);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $instructor = $result->fetch_assoc();
    $inst_name = $instructor['first_name'] . " " . $instructor['last_name'];
} else {
    echo "Instructor not found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Exams Calendar - IPMC INVENTORY MANAGER</title>

  <link rel="icon" type="image/ico" href="assets/imgs/inventory_logo.png" />
  <link rel="stylesheet" href="assets/css/style.css">
  <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="assets/bootstrap/css/bootstrap-icons.min.css" />
  <link href="https://fonts.googleapis.com/css2?family=Exo+2&family=Montserrat&family=Raleway&family=Roboto&display=swap" rel="stylesheet" />

  <!-- FullCalendar CSS -->
  <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css" rel="stylesheet" />

  <style>
      #examCalendar {
          width: 90%;
          margin: 30px auto;
          background: #fff;
          border-radius: 10px;
          padding: 15px;
          box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
          min-height: 650px;
      }

      .fc-toolbar-title {
          font-weight: 600;
      }

      .fc-daygrid-event {
          border-radius: 6px;
          padding: 3px 5px;
      }
      .notice {
        color: maroon;
        font-weight: bold;
        margin-bottom: 20px;
      }
  </style>
</head>

<body>
  <?php
  require("includes/sidebar.php");
  require("includes/topbar.php");
  ?>

  <div class="mx-auto" style="margin-top: 4rem; width:85%">
  <p class="notice"><ion-icon name="information-circle-outline"></ion-icon>
    Note: Green represents Approved, Gold represents Pending, and Red represents Cancelled Exams!</p>
    
    <div class="d-flex justify-content-between align-items-center">
      <h3 class="my-auto">
        <ion-icon name="calendar-outline"></ion-icon>
        Exams Schedule Calendar
      </h3>
      <div>
        <button class="btn text-white px-2" style="background-color:green;">Approved</button>
        <button class="btn text-white px-2" style="background-color:gold;">Pending</button>
        <button class="btn text-white px-2" style="background-color:red;">Cancelled</button>
      </div>
      <a href="examination.php">
        <button class="btn text-white px-4" style="background-color:rgb(200, 72, 105)">Book Examination</button>
      </a>
    </div>
    <hr class="mb-4">
  </div>

  <!-- Calendar Section -->
  <div id="examCalendar"></div>

<!-- jQuery must be first -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- Bootstrap Bundle -->
<script src="assets/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- SweetAlert -->
<script src="assets/js/sweetalert.min.js"></script>

<!-- FullCalendar JS must be BEFORE your script -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>

<!-- Now safely initialize -->
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Check that FullCalendar is loaded
  if (typeof FullCalendar === 'undefined') {
    console.error("FullCalendar failed to load!");
    return;
  }

  var calendarEl = document.getElementById('examCalendar');
  var calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth'
  });

  calendar.render();

  // Load exams
  $.ajax({
    url: "actions/fetch_examscalenderdata.php",
    type: "POST",
    dataType: "json",
    success: function(data) {
      calendar.removeAllEvents();
      if (Array.isArray(data)) {
        data.forEach(function(exam) {
          let color = 'gray';
          if (exam.status.toLowerCase() === 'pending') color = 'gold';
          else if (exam.status.toLowerCase() === 'approved' || exam.status.toLowerCase() === 'approve') color = 'green';
          else if (exam.status.toLowerCase() === 'cancelled') color = 'red';

          calendar.addEvent({
            title: exam.module_name + ' (' + exam.course_name + ')',
            start: exam.exam_date,
            backgroundColor: color,
            borderColor: color,
            textColor: '#fff'
          });
        });
      }
    },
    error: function(err) {
      console.error("Error loading exam data:", err);
    }
  });
});
</script>


  <script src="assets/js/main.js"></script>
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

  <?php
  $title = "Exams Calendar";
  successAlert($title);
  ?>
</body>
</html>
