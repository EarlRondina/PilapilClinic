<?php
  session_start();
	include "../PHP/connection.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pilapil Clinic</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="index-style.css">

</head>
<body>

<!-- Main Content -->
<div class="container-my-5">
  



  <h2 class = "clients-text">
    Patient Information
  </h2>

  <form action="create.php" method="get">
  <button type="submit" class="appointment-button">New Appointment</button>
  </form>

  <br>
  <table class="table">
    <thead>
      <tr>
        <th>Name</th>
        <th>Birth Date</th>
        <th>age</th>
        <th>Gender</th>
        <th>Email</th>
        <th>Phone Number</th>
        <th>Department</th>
        <th>Procedure</th>
        <th>Appointment Date</th>
        <th>Booking Type</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      <?php
      // READ ALL ROWS FROM DATABASE
      $sql = "SELECT p.PatientID, FirstName, LastName, MiddleInitial, BirthDate, Age, Gender, Email, PhoneNumber, BookingType, Department, Procedures, Status, AppointmentDT FROM patientgeneralinformation AS pgi JOIN patient AS p ON pgi.PatientID = p.PatientID";
      $result = $conn->query($sql);

      if (!$result) {
        die("Invalid query: " . $conn->error);
      }

      // Read data of each row
      while ($row = $result->fetch_assoc()) {
        echo "<tr>"; 
        echo "<td>" . $row['LastName'] . ", " . $row['FirstName'] . " " . $row['MiddleInitial'] . "</td>";
        echo "<td>" . $row['BirthDate'] . "</td>";
        echo "<td>" . $row['Age'] . "</td>";
        echo "<td>" . $row['Gender'] . "</td>";
        echo "<td>" . $row['Email'] . "</td>";
        echo "<td>" . $row['PhoneNumber'] . "</td>";
        echo "<td>" . $row['Department'] . "</td>";
        echo "<td>" . $row['Procedures'] . "</td>";
        echo "<td>" . $row['AppointmentDT'] . "</td>";
        echo "<td>" . $row['BookingType'] . "</td>";
        echo "<td>" . $row['Status'] . "</td>";
        echo "<td>";
        echo "<a class='btn btn-warning btn-sm' href='edit.php?PatientID=" . $row['PatientID'] . "'>Edit</a> ";
        echo "<a class='btn btn-danger btn-sm' href='delete.php?PatientID=" . $row['PatientID'] . "'>Delete</a>"; "'>Delete</a>";
        echo "</td>";
        echo "</tr>";
      }
      ?>
    </tbody>
  </table>
</div>


</body>
</html>


<!-- Include your custom CSS -->
<style>

  .clients-text{
    font-weight: bold;
  }

  .appointment-button{
    border: ;
  }

  .ClinicName{
    font-weight: bold;
    font-size: 30px;
  }
    /* Adjustments for main content area */
    .container-my-5 {
      margin-left: 0%; /* Margin to accommodate the sidebar width */
      padding: 20px; /* Padding inside the main content */
      background-color: white; /* Background color */
      position: relative; /* Relative positioning for content flow */
      z-index: 0; /* Default z-index */
    }
    
    /* Ensure main content area expands to fill remaining width */
    .main-content {
      width: 80%; /* Remaining width after sidebar */
      float: right; /* Float right to align with margin-left */
    }

    /* Responsive adjustments for smaller screens */
    @media (max-width: 768px) {
      .Left-Nav {
        width: 100%; /* Full width on smaller screens */
        height: auto; /* Auto height to fit content */
        position: static; /* Reset position for flow in document */
        padding: 10px; /* Reduced padding for smaller screens */
        z-index: 0; /* Reset z-index */
      }

      .container-my-5 {
        margin-left: 0; /* No margin on smaller screens */
        width: 100%; /* Full width */
        padding: 10px; /* Reduced padding for smaller screens */
      }

      .main-content {
        width: 100%; /* Full width on smaller screens */
        float: none; /* Reset float for flow in document */
      }
    }
  </style>