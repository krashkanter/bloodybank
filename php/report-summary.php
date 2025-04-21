<?php
global $conn;
require_once 'connect.php';


// Donors
$donors = $conn->query("SELECT COUNT(*) AS total FROM Donor")->fetch_assoc()['total'] ?? 0;

// Patients
$patients = $conn->query("SELECT COUNT(*) AS total FROM Patient")->fetch_assoc()['total'] ?? 0;

// Hospitals
$hospitals = $conn->query("SELECT COUNT(*) AS total FROM Hospital")->fetch_assoc()['total'] ?? 0;

// Total Units Donated
$donated = $conn->query("SELECT SUM(Quantity_of_Blood) AS total FROM Donor")->fetch_assoc()['total'] ?? 0;

echo "
  <div class='card'>
    <h3>Total Donors</h3>
    <p>{$donors}</p>
  </div>
  <div class='card'>
    <h3>Total Patients</h3>
    <p>{$patients}</p>
  </div>
  <div class='card'>
    <h3>Total Hospitals</h3>
    <p>{$hospitals}</p>
  </div>
  <div class='card'>
    <h3>Total Units in Stock</h3>
    <p>{$donated}</p>
  </div>
";
$conn->close();
?>
