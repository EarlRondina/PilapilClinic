<?php
session_start();
include "../connection.php";

if(isset($_GET["PatientID"])){
    $PatientID = $_GET["PatientID"];

    $sql = "DELETE FROM patientgeneralinformation
            WHERE PatientID = $PatientID";

    if ($conn->query($sql) === TRUE) {
        header("location: activity.php");
        exit;
    } else {
        echo "Error deleting record: " . $conn->error;
    }
} else {
    echo "PatientID not set.";
}

$conn->close();
?>