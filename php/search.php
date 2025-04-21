<?php
global $conn;
require_once 'connect.php';


if (isset($_GET['query'])) {
  $query = trim($_GET['query']);
  $query = mysqli_real_escape_string($conn, $query);

  // Search donors, patients, and blood by name, ID, or blood group
  $sql = "SELECT * FROM Donor WHERE donor_id LIKE '%$query%' OR name LIKE '%$query%' OR blood_group LIKE '%$query%'
          UNION
          SELECT * FROM Patient WHERE patient_id LIKE '%$query%' OR name LIKE '%$query%' OR blood_group LIKE '%$query%'";

  $result = mysqli_query($conn, $sql);

  echo "<h2>Search Results for: <em>" . htmlspecialchars($query) . "</em></h2>";

  if (mysqli_num_rows($result) > 0) {
    echo "<table border='1'><tr><th>ID</th><th>Name</th><th>Blood Group</th><th>Type</th></tr>";
    while ($row = mysqli_fetch_assoc($result)) {
      echo "<tr>
              <td>{$row['donor_id'] ?? $row['patient_id']}</td>
              <td>{$row['name']}</td>
              <td>{$row['blood_group']}</td>
              <td>" . (isset($row['donor_id']) ? 'Donor' : 'Patient') . "</td>
            </tr>";
    }
    echo "</table>";
  } else {
    echo "<p>No matching records found.</p>";
  }
}
?>
