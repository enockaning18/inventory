<?php
require_once('../../baseConnect/dbConnect.php');

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid request");
}
$donation_id = intval($_GET['id']);

// verify record exists (optional)
$stmt = $conn->prepare("SELECT donation_id FROM donationfrm WHERE donation_id = ?");
$stmt->bind_param("i", $donation_id);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows !== 1) {
    die("Donation not found");
}
$stmt->close();

// redirect to the main page with edit id
header("Location: ../donations.php?edit_id=" . $donation_id);
exit;
