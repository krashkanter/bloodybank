<?php
global $conn;
require_once 'connect.php';


$sql = "SELECT d.Donation_ID, b.Blood_Group, d.Quantity, 'Donor' AS Source, d.Date
        FROM Donation d
        JOIN Blood b ON d.Blood_ID = b.ID
        ORDER BY d.Date DESC
        LIMIT 10";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
            <td>{$row['Donation_ID']}</td>
            <td>{$row['Blood_Group']}</td>
            <td>{$row['Quantity']}</td>
            <td>{$row['Source']}</td>
            <td>{$row['Date']}</td>
          </tr>";
    }
} else {
    echo "<tr><td colspan='5'>No recent entries found.</td></tr>";
}

$conn->close();
?>
