<?php
global $conn;
require_once 'connect.php';

// Total Blood Units (from Donor or Donation table)
$bloodUnits = $conn->query("SELECT SUM(Quantity_of_Blood) AS total FROM Donor")->fetch_assoc()['total'] ?? 0;

// Distinct Blood Types (from Blood table or Donor)
$bloodTypes = $conn->query("SELECT GROUP_CONCAT(DISTINCT Blood_Group ORDER BY Blood_Group ASC SEPARATOR ', ') AS types FROM Donor")->fetch_assoc()['types'] ?? "N/A";

// Total Donors
$totalDonors = $conn->query("SELECT COUNT(*) AS count FROM Donor")->fetch_assoc()['count'] ?? 0;

// Pending Requests (transfusions without matching donations — simulated here)
$pendingRequests = $conn->query("SELECT COUNT(*) AS pending FROM Transfusion WHERE Date IS NULL")->fetch_assoc()['pending'] ?? 0;

// Output cards
echo "
  <div class='card'>
    <h3>Total Blood Units</h3>
    <p>{$bloodUnits}</p>
  </div>
  <div class='card'>
    <h3>Available Blood Types</h3>
    <p>{$bloodTypes}</p>
  </div>
  <div class='card'>
    <h3>Donors Registered</h3>
    <p>{$totalDonors}</p>
  </div>
  <div class='card'>
    <h3>Pending Requests</h3>
    <p>{$pendingRequests}</p>
  </div>
";

$conn->close();
?>
