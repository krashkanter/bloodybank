<?php
global $conn;
require_once 'php/connect.php'; // centralized connection file

// --- Form Submission Handler ---
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $hospital_name = $_POST['hospital_name'];
    $contact_person = $_POST['contact_person'];
    $phone = $_POST['phone'];
    $blood_group = $_POST['blood_group'];
    $units = $_POST['units'];
    $reason = $_POST['reason'];

    $stmt = $conn->prepare("INSERT INTO Hospital_Requests (Hospital_Name, Contact_Person, Phone, Blood_Group, Units_Requested, Reason, Request_Date, Status)
                          VALUES (?, ?, ?, ?, ?, ?, NOW(), 'Pending')");
    $stmt->bind_param("ssssss", $hospital_name, $contact_person, $phone, $blood_group, $units, $reason);

    if ($stmt->execute()) {
        $msg = "Request submitted successfully!";
    } else {
        $msg = "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hospital Request - Blood Bank</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<header>
    <h1>Hospital Blood Request</h1>
    <nav>
        <ul>
            <li><a href="index.html">🏠 Home</a></li>
            <li><a href="donor.html">Donors</a></li>
            <li><a href="patient.php">Patients</a></li>
            <li><a href="stock-view.php">Stock</a></li>
        </ul>
    </nav>
</header>

<main>
    <!-- Hospital Request Form -->
    <section class="form-section">
        <h2>Request Blood</h2>
        <?php if (!empty($msg)) echo "<p style='color: green;'>$msg</p>"; ?>
        <form method="POST" class="form-grid">
            <label for="hospital_name">Hospital Name:</label>
            <input type="text" id="hospital_name" name="hospital_name" required>

            <label for="contact_person">Contact Person:</label>
            <input type="text" id="contact_person" name="contact_person" required>

            <label for="phone">Contact Number:</label>
            <input type="tel" id="phone" name="phone" maxlength="10" pattern="[6-9]{1}[0-9]{9}" required>

            <label for="blood_group">Required Blood Group:</label>
            <select id="blood_group" name="blood_group" required>
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

            <label for="units">Number of Units:</label>
            <input type="number" id="units" name="units" min="1" required>

            <label for="reason">Reason/Remarks:</label>
            <textarea id="reason" name="reason" rows="3" required></textarea>

            <button type="submit">Submit Request</button>
        </form>
    </section>

    <!-- Previous Requests Table -->
    <section class="table-section">
        <h2>Previous Blood Requests</h2>
        <table>
            <thead>
            <tr>
                <th>ID</th>
                <th>Hospital</th>
                <th>Blood Group</th>
                <th>Units</th>
                <th>Contact</th>
                <th>Status</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $sql = "SELECT ID, Hospital_Name, Blood_Group, Units_Requested, Phone, Status 
                  FROM Hospital_Requests 
                  ORDER BY Request_Date DESC 
                  LIMIT 10";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                      <td>" . htmlspecialchars($row['ID']) . "</td>
                      <td>" . htmlspecialchars($row['Hospital_Name']) . "</td>
                      <td>" . htmlspecialchars($row['Blood_Group']) . "</td>
                      <td>" . htmlspecialchars($row['Units_Requested']) . "</td>
                      <td>" . htmlspecialchars($row['Phone']) . "</td>
                      <td>" . htmlspecialchars($row['Status']) . "</td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No requests found.</td></tr>";
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
