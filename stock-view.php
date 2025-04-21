<?php
global $host, $username, $password, $database, $port;
require_once "php/connect.php";

$conn = new mysqli($host, $username, $password, $database, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Stock View - Blood Bank</title>
    <link rel="stylesheet" href="styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<header>
    <h1>Blood Stock Availability</h1>
    <nav>
        <ul>
            <li><a href="index.html">🏠 Home</a></li>
            <li><a href="donor.html">Donors</a></li>
            <li><a href="patient.php">Patients</a></li>
            <li><a href="hospital.php">Hospital</a></li>
            <li><a href="report.php">Reports</a></li>
        </ul>
    </nav>
</header>

<main>
    <section class="table-section">
        <h2>Current Blood Stock (By Blood Bank)</h2>
        <table class="stock-table">
            <thead>
            <tr>
                <th>Blood Bank Name</th>
                <th>Location</th>
                <th>Available Blood Groups</th>
                <th>Total Units</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $sql = "SELECT Name, Location, Available_Blood_Groups, Quantity FROM Blood_Bank";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                      <td>" . htmlspecialchars($row["Name"]) . "</td>
                      <td>" . htmlspecialchars($row["Location"]) . "</td>
                      <td>" . htmlspecialchars($row["Available_Blood_Groups"]) . "</td>
                      <td>" . htmlspecialchars($row["Quantity"]) . "</td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No blood stock data available.</td></tr>";
            }

            $conn->close();
            ?>
            </tbody>
        </table>
    </section>
</main>

<footer>
    <p>&copy; 2025 Blood Bank Management System</p>
</footer>

<script src="script.js"></script>
</body>
</html>
