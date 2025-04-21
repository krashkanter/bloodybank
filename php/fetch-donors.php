<?php
global $conn;
require_once 'connect.php';

$sql = "SELECT * FROM Donor ORDER BY Donor_ID DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table>
            <thead>
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Blood Group</th>
                <th>Gender</th>
                <th>Age</th>
                <th>Phone</th>
                <th>Address</th>
              </tr>
            </thead>
            <tbody>";
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['Donor_ID']}</td>
                <td>{$row['Name']}</td>
                <td>{$row['Blood_Group']}</td>
                <td>{$row['Gender']}</td>
                <td>{$row['Age']}</td>
                <td>{$row['Contact']}</td>
                <td>{$row['Address']}</td>
              </tr>";
    }
    echo "</tbody></table>";
} else {
    echo "<p>No donors registered yet.</p>";
}

$conn->close();
?>
