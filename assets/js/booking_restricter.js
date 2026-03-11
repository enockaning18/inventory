document.addEventListener("DOMContentLoaded", function () {
  const examDate = document.getElementById("exam_date");

  // Get today's date
  let today = new Date();

  // Add 10 days
  let minDateObj = new Date();
  minDateObj.setDate(today.getDate() + 10);

  // Format to YYYY-MM-DD
  let minDate = minDateObj.toISOString().split("T")[0];

  // Disable earlier dates in calendar
  examDate.setAttribute("min", minDate);

  // Extra validation if user tries to bypass
  examDate.addEventListener("change", function () {
    let selectedDate = new Date(this.value);

    if (selectedDate < minDateObj) {
      alert("You must book an exam at least 10 days ahead.");
      this.value = "";
    }
  });
});
