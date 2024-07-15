<?php
include "../PHP/connection.php";

// Initialize variables
$FirstName = "";
$LastName = "";
$MiddleInitial = "";
$BirthDate = "";
$age = "";
$Gender = "";
$Email = "";
$PhoneNumber = "";
$Department = "";
$Procedure = "";
$AppointmentDate = "";
$BookingType = "";
$Status = "";

$errorMessage = "";
$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (!isset($_GET['PatientID'])) {
        header("location: index.php");
        exit;
    }

    $AppointmentID = $_GET["PatientID"];

    $sql = "SELECT p.PatientID, FirstName, LastName, MiddleInitial, BirthDate, Age, Gender, Email, PhoneNumber, BookingType, Department, Procedures, Status, AppointmentDT 
            FROM patientgeneralinformation AS pgi 
            JOIN patient AS p ON pgi.PatientID = p.PatientID 
            WHERE p.PatientID = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $AppointmentID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if (!$row) {
        header("location: /index.php");
        exit;
    }

    $FirstName = $row["FirstName"];
    $LastName = $row["LastName"];
    $MiddleInitial = $row["MiddleInitial"];
    $BirthDate = $row["BirthDate"];
    $age = $row["Age"];
    $Gender = $row["Gender"];
    $Email = $row["Email"];
    $PhoneNumber = $row["PhoneNumber"];
    $Department = $row["Department"];
    $Procedure = $row["Procedures"];
    $AppointmentDate = $row["AppointmentDT"];
    $BookingType = $row["BookingType"];
    $Status = $row["Status"];

} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $AppointmentID = htmlspecialchars($_POST["AppointmentID"]); // Ensure AppointmentID is being set correctly
    $FirstName = htmlspecialchars($_POST["FirstName"]);
    $LastName = htmlspecialchars($_POST["LastName"]);
    $MiddleInitial = htmlspecialchars($_POST["MiddleInitial"]);
    $BirthDate = htmlspecialchars($_POST["Bday"]);
    $age = htmlspecialchars($_POST["age"]);
    $Gender = htmlspecialchars($_POST["Gender"]);
    $Email = htmlspecialchars($_POST["Email"]);
    $PhoneNumber = htmlspecialchars($_POST["PhoneNumber"]);
    $Department = htmlspecialchars($_POST["Department"]);
    $Procedure = htmlspecialchars($_POST["Procedure"]);
    $AppointmentDate = htmlspecialchars($_POST["Appointment"]);
    $BookingType = htmlspecialchars($_POST["BookingType"]);
    $Status = htmlspecialchars($_POST["Status"]);

    // Validate form data
    if (empty($FirstName) || empty($LastName) || empty($MiddleInitial) || empty($BirthDate) || empty($age) || empty($Gender) || empty($Email) || empty($PhoneNumber) || empty($Department) || empty($Procedure) || empty($AppointmentDate) || empty($BookingType) || empty($Status)) {
        $errorMessage = "All fields are required";
    } elseif ($BookingType != 'manual' && $BookingType != 'site') {
        $errorMessage = "Booking Type must be either 'manual' or 'site'";
    } else {
        // Update SQL statement
        $sql = "UPDATE patientgeneralinformation pgi
                JOIN patient p ON pgi.PatientID = p.PatientID
                SET 
                    pgi.FirstName = ?,
                    pgi.LastName = ?,
                    pgi.MiddleInitial = ?,
                    pgi.BirthDate = ?,
                    pgi.Age = ?,
                    pgi.Gender = ?,
                    pgi.Email = ?,
                    pgi.PhoneNumber = ?,
                    p.Department = ?,
                    p.Procedures = ?,
                    p.AppointmentDT = ?,
                    p.BookingType = ?,
                    p.Status = ?
                WHERE p.PatientID = ?";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssissssssssi", $FirstName, $LastName, $MiddleInitial, $BirthDate, $age, $Gender, $Email, $PhoneNumber, $Department, $Procedure, $AppointmentDate, $BookingType, $Status, $AppointmentID);

        $stmt->execute();

        // Execute SQL statement
        if ($stmt->affected_rows > 0) {
            $successMessage = "Client updated correctly";
            // Redirect to index.php after successful update
            header("location: index.php");
            exit;
        } else {
            $errorMessage = "Error updating record: " . $conn->error;
        }
    }
}

// Close the database connection
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilapil Clinic</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <div class="container">
        <h2>Edit Client</h2>

        <?php if (!empty($errorMessage)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong><?php echo $errorMessage; ?></strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <form method="post">
            <input type="hidden" name="AppointmentID" value="<?php echo $AppointmentID; ?>">
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">First Name</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="FirstName" value="<?php echo $FirstName; ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Last Name</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="LastName" value="<?php echo $LastName; ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Middle Initial</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="MiddleInitial" value="<?php echo $MiddleInitial; ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Birth Date</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="Bday" value="<?php echo $BirthDate; ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Age</label>
                <div class="col-sm-6">
                    <input type="number" class="form-control" name="age" value="<?php echo $age; ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Gender</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="Gender" value="<?php echo $Gender; ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Email</label>
                <div class="col-sm-6">
                    <input type="email" class="form-control" name="Email" value="<?php echo $Email; ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Phone Number</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="PhoneNumber" value="<?php echo $PhoneNumber; ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Department</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="Department" value="<?php echo $Department; ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Procedure</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="Procedure" value="<?php echo $Procedure; ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">AppointmentDate</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="Appointment" value="<?php echo $AppointmentDate; ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Booking Type</label>
                <div class="col-sm-6">
                    <select class="form-control" name="BookingType">
                        <option value="manual" <?php if ($BookingType == 'manual') echo 'selected'; ?>>Manual</option>
                        <option value="site" <?php if ($BookingType == 'site') echo 'selected'; ?>>Site</option>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Status</label>
                <div class="col-sm-6">
                    <select class="form-control" name="Status">
                        <option value="pending" <?php if ($Status == 'pending') echo 'selected'; ?>>Pending</option>
                        <option value="completed" <?php if ($Status == 'completed') echo 'selected'; ?>>Completed</option>
                        <option value="in-session" <?php if ($Status == 'in-session') echo 'selected'; ?>>In-Session</option>
                        <option value="cancelled" <?php if ($Status == 'cancelled') echo 'selected'; ?>>Cancelled</option>
                    </select>
                </div>
            </div>

            <?php if (!empty($successMessage)): ?>
                <div class="row mb-3">
                    <div class="col-sm-6 offset-sm-3">
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong><?php echo $successMessage; ?></strong>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="row mb-3">
                <div class="col-sm-3 offset-sm-3 d-grid">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
                <div class="col-sm-3 d-grid">
                    <a class="btn btn-outline-primary" href="/PilapilClinic/PatientInfo/index.php" role="button">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</body>

</html>
