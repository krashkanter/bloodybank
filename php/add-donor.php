<?php
global $conn;
require_once 'connect.php';

$name = $conn->real_escape_string($_POST['name']);
$age = (int)$_POST['age'];
$gender = $conn->real_escape_string($_POST['gender']);
$blood_group = $conn->real_escape_string($_POST['blood_group']);
$phone = $conn->real_escape_string($_POST['phone']);
$address = $conn->real_escape_string($_POST['address']);

// Insert into Donor table
$sql = "INSERT INTO Donor (Name, Age, Gender, Blood_Group, Contact, Address) 
        VALUES ('$name', $age, '$gender', '$blood_group', '$phone', '$address')";

if ($conn->query($sql) === TRUE) {
    echo "<script>alert('Donor registered successfully!'); window.location.href='donor.html';</script>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
