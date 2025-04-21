<?php
require_once 'php/connect.php'; // You can change this to just 'connect.php' if it's in the same directory
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reports - Blood Bank</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<header>
    <h1>System Reports</h1>
    <nav>
        <ul>
            <li><a href="index.html">🏠 Home</a></li>
            <li><a href="donor.html">Donors</a></li>
            <li><a href="patient.php">Patients</a></li>
            <li><a href="hospital.php">Hospitals</a></li>
            <li><a href="stock-view.php">Stock</a></li>
        </ul>
    </nav>
</header>

<main>
    <!-- Report Summary Cards -->
    <section class="report-cards">
        <?php
        // Example summary cards (customize these based on your DB schema)
        $donors = $conn->query("SELECT COUNT(*) AS total FROM Donor")->fetch_assoc()['total'];
        $patients = $conn->query("SELECT COUNT(*) AS total FROM Patient")->fetch_assoc()['total'];
        $hospitals = $conn->query("SELECT COUNT(*) AS total FROM Hospital")->fetch_assoc()['total'];

        echo "
        <div class='card'>Total Donors: <strong>{$donors}</strong></div>
        <div class='card'>Total Patients: <strong>{$patients}</strong></div>
        <div class='card'>Total Hospitals: <strong>{$hospitals}</strong></div>
      ";
        ?>
    </section>

    <!-- Stock Summary Table -->
    <section class="table-section">
        <h2>Blood Stock Report</h2>
        <table>
            <thead>
            <tr>
                <th>Blood Group</th>
                <th>Total Donated</th>
                <th>Total Issued</th>
                <th>Remaining Units</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $bloodGroups = $conn->query("SELECT DISTINCT Blood_Group FROM Blood");

            while ($bg = $bloodGroups->fetch_assoc()) {
                $group = $bg['Blood_Group'];

                $donated = $conn->query("SELECT SUM(Quantity_of_Blood) AS total FROM Donor WHERE Blood_Group = '$group'")
                    ->fetch_assoc()['total'] ?? 0;

                $issued = $conn->query("SELECT COUNT(*) AS total FROM Transfusion t JOIN Patient p ON t.Patient_ID = p.Patient_ID WHERE p.Blood_Group = '$group'")
                    ->fetch_assoc()['total'] ?? 0;

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
