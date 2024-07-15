<?php
// Include your database connection script
include "../connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['PatientID'])) {
    // Sanitize the input
    $PatientID = intval($_GET['PatientID']); // Assuming PatientID is an integer

    // Update SQL statement
    $sql = "UPDATE patient SET Final = 'YES' WHERE PatientID = ?";
    
    // Prepare the statement
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $PatientID);

    // Execute the statement
    $stmt->execute();

    // Check if the update was successful
    if ($stmt->affected_rows > 0) {
        // Redirect to index.php or any other page
        header("location: status.php");
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
    header("location: status.php");
    exit;
}
?>