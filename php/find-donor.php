<?php
global $conn;
require_once 'connect.php';

$name = isset($_GET['name']) ? $_GET['name'] : '';
$blood_group = isset($_GET['blood_group']) ? $_GET['blood_group'] : '';
$location = isset($_GET['location']) ? $_GET['location'] : '';

$sql = "SELECT * FROM Donor WHERE 1=1";

if (!empty($name)) {
  $sql .= " AND Name LIKE '%" . $conn->real_escape_string($name) . "%'";
}
if (!empty($blood_group)) {
  $sql .= " AND Blood_Group = '" . $conn->real_escape_string($blood_group) . "'";
}
if (!empty($location)) {
  $sql .= " AND Address LIKE '%" . $conn->real_escape_string($location) . "%'";
}

$result = $conn->query($sql);

if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
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
} else {
  echo "<tr><td colspan='7'>No matching donors found.</td></tr>";
}

$conn->close();
?>
