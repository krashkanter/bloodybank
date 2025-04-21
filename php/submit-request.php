<?php

global $conn;
require_once "connect.php";
$hospital_name = $_POST['hospital_name'];
$contact_person = $_POST['contact_person'];
$phone = $_POST['phone'];
$blood_group = $_POST['blood_group'];
$units = $_POST['units'];
$reason = $_POST['reason'];

$sql = "INSERT INTO Hospital_Requests (Hospital_Name, Contact_Person, Phone, Blood_Group, Units_Requested, Reason, Status, Request_Date)
        VALUES (?, ?, ?, ?, ?, ?, 'Pending', NOW())";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssss", $hospital_name, $contact_person, $phone, $blood_group, $units, $reason);

if ($stmt->execute()) {
    echo "<script>alert('Blood request submitted successfully!'); window.location.href='../hospital.php';</script>";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
