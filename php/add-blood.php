<?php
global $conn;
require_once 'connect.php';

$blood_group = $_POST['blood_group'];
$units = $_POST['units'];
$source = $_POST['source'];
$entry_date = $_POST['entry_date'];

// Insert into Blood table
// Assuming you have a Blood table with auto-increment ID
$sql = "INSERT INTO Blood (Blood_Group) VALUES (?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $blood_group);
$stmt->execute();
$blood_id = $conn->insert_id;
$stmt->close();

// Insert into Donation table for tracking purposes
$sql2 = "INSERT INTO Donation (Donor_ID, Blood_ID, Date, Quantity) VALUES (NULL, ?, ?, ?)";
$stmt2 = $conn->prepare($sql2);
$stmt2->bind_param("isi", $blood_id, $entry_date, $units);

if ($stmt2->execute()) {
    echo "<script>alert('Blood stock added successfully'); window.location.href='../blood-entry.html';</script>";
} else {
    echo "Error: " . $stmt2->error;
}

$stmt2->close();
$conn->close();
?>
