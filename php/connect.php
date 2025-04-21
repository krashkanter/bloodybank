<?php
$host = "localhost";
$username = "root";
$password = "root_password";
$database = "blood_bank"; // Must match the database you created
$port = 7990;
$conn = new mysqli($host, $username, $password, $database, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
