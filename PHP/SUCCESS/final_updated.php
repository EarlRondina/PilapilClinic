<?php
include "../connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['PatientID'])) {
    $PatientID = intval($_GET['PatientID']);

    // Update SQL statement
    $sql = "UPDATE patient SET Final = 'YES' WHERE PatientID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $PatientID);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        // Redirect to index.php or any other page after successful update
        header("location: index.php");
        exit;
    } else {
        // Handle error if update failed
        echo "Error updating record: " . $conn->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
} else {
    // Redirect if PatientID is not set or request method is not GET
    header("location: index.php");
    exit;
}
?>