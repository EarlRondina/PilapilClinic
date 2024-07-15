<?php
if(isset($_POST['submit'])){
    include "connection.php";
    
    // Function to capitalize first letter of each word
    function capitalizeWords($str) {
        return ucwords(strtolower($str));
    }
    
    // Retrieving form data and applying capitalization
    $firstname = capitalizeWords($_POST['first-name']);
    $lastname = capitalizeWords($_POST['last-name']);
    $MI = strtoupper($_POST['middle-initial']); // Convert middle initial to uppercase
    $birthdate = $_POST['birth-date'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $add = $_POST['current-address'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $ECN = capitalizeWords($_POST['emer-per']);
    $ECP = $_POST['emer-num'];
    $department = capitalizeWords($_POST['department']);
    $procedure = capitalizeWords($_POST['procedure']);
    $appointment = $_POST['appointment-datetime'];
    $appointmentT = $_POST['appointment-time'];

    // Insert into patientgeneralinformation
    $sql1 = "INSERT INTO patientgeneralinformation (FirstName, LastName, MiddleInitial, BirthDate, Age, Gender, Address, Email, PhoneNumber)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt1 = mysqli_prepare($conn, $sql1);
    mysqli_stmt_bind_param($stmt1, "ssssissss", $firstname, $lastname, $MI, $birthdate, $age, $gender, $add, $email, $phone);
    $result1 = mysqli_stmt_execute($stmt1);

    $patientID = mysqli_insert_id($conn);

    // Insert into patient
    $sql2 = "INSERT INTO patient (PatientID, EmergencyContactName, EmergencyContactPhone, Department, Procedures, AppointmentDT, AppointmentTime)
             VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt2 = mysqli_prepare($conn, $sql2);
    mysqli_stmt_bind_param($stmt2, "issssss", $patientID, $ECN, $ECP, $department, $procedure, $appointment, $appointmentT);
    $result2 = mysqli_stmt_execute($stmt2);

    // Check if all queries were successful
    if ($result1 && $result2) {
        echo '<script>alert("Booking successful!");</script>';
        echo '<script>history.back()</script>';
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    // Close the prepared statements
    mysqli_stmt_close($stmt1);
    mysqli_stmt_close($stmt2);
    // Close the connection
    mysqli_close($conn);
}
?>