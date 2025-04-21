<?
global $conn;
require_once 'connect.php';

// Sanitize and collect POST data
$name = $_POST['name'];
$age = $_POST['age'];
$gender = $_POST['gender'];
$blood_group = $_POST['blood_group'];
$address = $_POST['address'];
$phone = $_POST['phone'];
$disease = $_POST['disease'];
$donation_date = $_POST['donation_date'];
$quantity = $_POST['quantity'];
$blood_bank_id = $_POST['blood_bank_id'];

// Insert into Donor table
$sql_donor = "INSERT INTO Donor (name, age, gender, blood_group, address, disease, blood_bank_id) 
VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt_donor = $conn->prepare($sql_donor);
$stmt_donor->bind_param("sisssssi", $name, $age, $gender, $blood_group, $address, $phone, $disease, $blood_bank_id);

if ($stmt_donor->execute()) {
    $donor_id = $stmt_donor->insert_id;

    // Insert into Blood table
    $sql_blood = "INSERT INTO Blood (donor_id, quantity, donation_date, blood_bank_id) 
    VALUES (?, ?, ?, ?)";

    $stmt_blood = $conn->prepare($sql_blood);
    $stmt_blood->bind_param("iisi", $donor_id, $quantity, $donation_date, $blood_bank_id);

    if ($stmt_blood->execute()) {
        echo "<script>alert('Donor Registered Successfully'); window.location.href='../register.html';</script>";
    } else {
        echo "Error inserting blood record: " . $stmt_blood->error;
    }

    $stmt_blood->close();
} else {
    echo "Error inserting donor: " . $stmt_donor->error;
}

$stmt_donor->close();
$conn->close();
?>
