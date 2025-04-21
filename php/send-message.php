<?php
require_once 'connect.php';

$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'blood_bank';

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Collect and sanitize inputs
$name = htmlspecialchars(trim($_POST['name']));
$email = htmlspecialchars(trim($_POST['email']));
$subject = htmlspecialchars(trim($_POST['subject']));
$message = htmlspecialchars(trim($_POST['message']));

// Insert into message table
$sql = "INSERT INTO Contact_Messages (Name, Email, Subject, Message, Timestamp) VALUES (?, ?, ?, ?, NOW())";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $name, $email, $subject, $message);

if ($stmt->execute()) {
  echo "<script>alert('Message sent successfully!'); window.location.href='../contact.html';</script>";
} else {
  echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
