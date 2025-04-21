<?php
global $conn;
require_once 'connect.php';

// Get all distinct blood groups
$bloodGroups = $conn->query("SELECT DISTINCT Blood_Group FROM Blood");

while ($bg = $bloodGroups->fetch_assoc()) {
  $group = $bg['Blood_Group'];

  // Total Donated
  $donated = $conn->query("SELECT SUM(Quantity_of_Blood) AS total FROM Donor WHERE Blood_Group = '$group'")
                  ->fetch_assoc()['total'] ?? 0;

  // Total Issued
  $issued = $conn->query("SELECT COUNT(*) AS total FROM Transfusion t JOIN Patient p ON t.Patient_ID = p.Patient_ID WHERE p.Blood_Group = '$group'")
                 ->fetch_assoc()['total'] ?? 0;

  // Remaining = Donated - Issued (assuming 1 unit issued per transfusion)
  $remaining = $donated - $issued;

  echo "<tr>
          <td>{$group}</td>
          <td>{$donated}</td>
          <td>{$issued}</td>
          <td>{$remaining}</td>
        </tr>";
}

$conn->close();
?>
