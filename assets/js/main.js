// add hovered class to selected list item
let list = document.querySelectorAll(".navigation li");

function activeLink() {
  list.forEach((item) => {
    item.classList.remove("hovered");
  });
  this.classList.add("hovered");
}

list.forEach((item) => item.addEventListener("mouseover", activeLink));

// Menu Toggle
let toggle = document.querySelector(".toggle");
let navigation = document.querySelector(".navigation");
let main = document.querySelector(".main");

toggle.onclick = function () {
  navigation.classList.toggle("active");
  main.classList.toggle("active");
};
// Dropdown Menu
document.querySelectorAll(".dropdown-toggle").forEach((drop) => {
  drop.addEventListener("click", function (e) {
    e.preventDefault();
    this.parentElement.classList.toggle("open");
  });
}); // Dropdown Menu

















/* =============== issue scripts ================ */
$(document).ready(function () {
  // Function to load issues
  function load_issues(search = "", issue_type = "", lab_type = "") {
    $.ajax({
      url: "actions/fetch_issue.php",
      type: "POST",
      data: {
        search: search,
        issue_type: issue_type,
        lab_type: lab_type,
      },
      success: function (data) {
        $("#issues_table").html(data);
      },
    });
  }

  // Load all on start
  load_issues();

  // Search input event by search type
  $("#searchBox").on("keyup", function () {
    const search = $(this).val();
    const issue_type = $("#issue_type").val();
    const lab_type = $("#lab_type").val();
    load_issues(search, issue_type, lab_type);
  });

  // Filter dropdown change event by issue_type
  $("#issue_type").on("change", function () {
    const issue_type = $(this).val();
    const search = $("#searchBox").val();
    const lab_type = $("#lab_type").val();
    load_issues(search, issue_type, lab_type);
  });
  // Filter dropdown change event by lab type
  $("#lab_type").on("change", function () {
    const issue_type = $("#issue_type").val();
    const search = $("#searchBox").val();
    const lab_type = $(this).val();
    load_issues(search, issue_type, lab_type);
  });

  // Show/hide resolution type dropdown based on issue status
  $("#issueStatus").on("change", function () {
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
  $("#deviceCategory").on("change", function () {
    const category = $(this).val();
    const deviceTypeSelect = $("#deviceType");
    const selectedDeviceId = "<?php echo isset($computer) ? $computer : '' ?>";

    if (category) {
      $.ajax({
        url: "actions/fetch_devices_by_category.php",
        type: "POST",
        dataType: "json",
        data: {
          category: category,
        },
        success: function (data) {
          deviceTypeSelect.html('<option value="">Select Device</option>');
          if (data.length > 0) {
            data.forEach(function (device) {
              const isSelected =
                selectedDeviceId && device.id == selectedDeviceId
                  ? "selected"
                  : "";
              deviceTypeSelect.append(
                '<option value="' +
                  device.id +
                  '" ' +
                  isSelected +
                  ">" +
                  device.name +
                  "</option>"
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
        error: function () {
          deviceTypeSelect.html(
            '<option value="">Error loading devices</option>'
          );
          deviceTypeSelect.prop("disabled", true);
        },
      });
    } else {
      deviceTypeSelect.html(
        '<option value="">Select Device Category First</option>'
      );
      deviceTypeSelect.prop("disabled", true);
      $("#serialNumber").val("");
      $("#labSelect").prop("disabled", false);
      $("#labHidden").val("");
    }
  });

  // Fetch serial number and lab when device type is selected
  $("#deviceType").on("change", function () {
    const deviceId = $(this).val();
    const category = $("#deviceCategory").val();

    if (deviceId && category) {
      $.ajax({
        url: "actions/fetch_device_serial.php",
        type: "POST",
        dataType: "json",
        data: {
          device_id: deviceId,
          category: category,
        },
        success: function (data) {
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
        error: function () {
          $("#serialNumber").val("");
          $("#labSelect").prop("disabled", false);
          $("#labHidden").val("");
        },
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
  $("#labSelect").on("change", function () {
    if (!$(this).prop("disabled")) {
      $("#labHidden").val($(this).val());
    }
  });

  // Handle issue status modal
  $("#issueModal").on("show.bs.modal", function (e) {
    const issueId = $(e.relatedTarget).data("issue-id");
    $("#modalIssueId").val(issueId);
    $.ajax({
      url: "actions/fetch_issue_details.php",
      type: "POST",
      data: {
        issue_id: issueId,
      },
      success: function (data) {
        const issue = JSON.parse(data);
        $("#dateReceived").val(issue.date_added);
        $("#dateReturned").val(issue.date_returned);
        $("#modalIssueStatus").val(issue.issue_status || "");
        $("#modalResolutionType").val(issue.resolved_type || "");

        // Show/hide resolution type based on status
        if (issue.issue_status === "Resolved") {
          $("#modalResolutionTypeDiv").show();
        } else {
          $("#modalResolutionTypeDiv").hide();
        }
      },
      error: function () {
        alert("Error loading issue details");
      },
    });
  });

  // Show/hide resolution type in modal based on status change
  $("#modalIssueStatus").on("change", function () {
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

/* =============== issue scripts ================ */
