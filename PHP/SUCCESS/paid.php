<?php
include "../connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (!isset($_GET['PatientID'])) {
        header("location: status.php");
        exit;
    }

    $PatientID = $_GET["PatientID"];

    // Check current payment status and amount
    $sql = "SELECT Payment, Amount FROM patient WHERE PatientID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $PatientID);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($PaymentStatus, $Amount);
        $stmt->fetch();

        // Update payment status to Paid only if it's not already Paid or Amount is not zero
        if ($PaymentStatus != 'Paid' && $Amount != 0) {
            $updateSql = "UPDATE patient SET Payment = 'Paid' WHERE PatientID = ?";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bind_param("i", $PatientID);
            $updateStmt->execute();

            if ($updateStmt->affected_rows > 0) {
                // Redirect to status.php after successful update
                header("location: status.php");
                exit;
            } else {
                echo "Error updating payment status";
            }
        } else {
            // Redirect to status.php if already Paid and Amount is zero
            header("location: status.php");
            exit;
        }
    } else {
        echo "Patient not found";
    }
}

$conn->close();
?>