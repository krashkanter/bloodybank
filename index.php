<?php
include 'php/connect.php';
// Total donors
$donorCount = $conn->query("SELECT COUNT(*) AS count FROM Donor")->fetch_assoc()['count'];

// A+ units
//$aPlusUnits = $conn->query("SELECT SUM(units) AS total FROM blood_inventory WHERE blood_group = 'A+'")->fetch_assoc()['total'] ?? 0;

// Samples analyzed today
$today = date('Y-m-d');
//$samplesToday = $conn->query("SELECT COUNT(*) AS count FROM samples WHERE DATE(analyzed_on) = '$today'")->fetch_assoc()['count'] ?? 0;

// Requests pending
//$pendingRequests = $conn->query("SELECT COUNT(*) AS count FROM requests WHERE status = 'pending'")->fetch_assoc()['count'] ?? 0;

// O- units low (assume low if less than 5)
//$oMinusUnits = $conn->query("SELECT SUM(units) AS total FROM blood_inventory WHERE blood_group = 'O-'")->fetch_assoc()['total'] ?? 0;
?>
