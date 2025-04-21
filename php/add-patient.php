<?php
global $conn;
require_once 'connect.php';

$name = $_POST['pname'];
$age = $_POST['page'];
$gender = $_POST['pgender'];
$blood_group = $_POST['pblood_group'];
$hospital = $_POST['hospital'];
$reason = $_POST['reason'];

$sql = "INSERT INTO Patient (name, age, gender, blood_group, hospital, reason)
        VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sissss", $name, $age, $gender, $blood_group, $hospital, $reason);

if ($stmt->execute()) {
    header("Location: ../patient.php");
    exit();
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
