<?php
// Include database connection
global $conn;
require_once 'php/connect.php';

// Fetch all patients
$sql = "SELECT * FROM Patient";
$result = $conn->query($sql);

// Check if any patients exist
if ($result->num_rows > 0) {
// Start outputting the HTML table
    echo '<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Patient Management - Blood Bank</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
<header>
  <h1>Patient Management</h1>
  <nav>
    <ul>
      <li><a href="index.html">🏠 Home</a></li>
      <li><a href="donor.html">Donors</a></li>
      <li><a href="hospital.php">Hospital</a></li>
      <li><a href="stock-view.php">Stock</a></li>
    </ul>
  </nav>
</header>

<main>
  <!-- Patient Form -->
  <section class="form-section">
    <h2>Add New Patient</h2>
    <form action="php/add-patient.php" method="POST" class="form-grid">
      <label for="pname">Full Name:</label>
      <input type="text" id="pname" name="pname" required>

      <label for="page">Age:</label>
      <input type="number" id="page" name="page" min="1" required>

      <label for="pgender">Gender:</label>
      <select id="pgender" name="pgender" required>
        <option value="">--Select--</option>
        <option value="Male">Male</option>
        <option value="Female">Female</option>
        <option value="Other">Other</option>
      </select>

      <label for="pblood_group">Blood Group:</label>
      <select id="pblood_group" name="pblood_group" required>
        <option value="">--Select--</option>
        <option value="A+">A+</option>
        <option value="A-">A-</option>
        <option value="B+">B+</option>
        <option value="B-">B-</option>
        <option value="O+">O+</option>
        <option value="O-">O-</option>
        <option value="AB+">AB+</option>
        <option value="AB-">AB-</option>
      </select>

      <label for="hospital">Hospital Name:</label>
      <input type="text" id="hospital" name="hospital" required>

      <label for="reason">Reason for Blood:</label>
      <textarea id="reason" name="reason" rows="3" required></textarea>

      <button type="submit">Add Patient</button>
    </form>
  </section>

  <!-- Patient Table -->
  <section class="table-section">
    <h2>Registered Patients</h2>
    <table>
      <thead>
      <tr>
        <th>ID</th>
        <th>Patient Name</th>
        <th>Blood Group</th>
        <th>Gender</th>
        <th>Age</th>
        <th>Hospital</th>
        <th>Reason</th>
      </tr>
      </thead>
      <tbody>';

    // Output each patient in the table
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
        <td>{$row['Patient_ID']}</td>
        <td>{$row['Name']}</td>
        <td>{$row['Blood_Group']}</td>
        <td>{$row['Gender']}</td>
        <td>{$row['Age']}</td>
        <td>{$row['Hospital']}</td>
        <td>{$row['Reason']}</td>
      </tr>";
    }

    echo '</tbody>
    </table>
  </section>
</main>

<footer>
  <p>&copy; 2025 Blood Bank Management System</p>
</footer>
<script src="script.js"></script>
</body>
</html>';
} else {
    echo "<p>No patients found.</p>";
}

// Close the database connection
$conn->close();
?>
