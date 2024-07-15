<?php
include "../connection.php";

// Initialize variables
$FirstName = $LastName = $MiddleInitial = $BirthDate = $age = $Gender = $Email = "";
$PhoneNumber = $Department = $Procedure = $AppointmentDate = $AppointmentTime = "";
$BookingType = $Status = $EmergencyP = $EmergencyN = "";

$errorMessage = $successMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (!isset($_GET['PatientID'])) {
        header("location: index.php");
        exit;
    }

    $AppointmentID = $_GET["PatientID"];

    $sql = "SELECT p.PatientID, FirstName, LastName, MiddleInitial, BirthDate, Age, Gender, Email, PhoneNumber, 
                   BookingType, Department, Procedures, Status, AppointmentDT, AppointmentTime, EmergencyContactName, EmergencyContactPhone
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
    $EmergencyP = $row["EmergencyContactPhone"];
    $EmergencyN = $row["EmergencyContactName"];
    $AppointmentTime = $row["AppointmentTime"];
    $BookingType = $row["BookingType"];
    $Status = $row["Status"];
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $AppointmentID = htmlspecialchars($_POST["AppointmentID"]);
    $FirstName = htmlspecialchars($_POST["FirstName"]);
    $LastName = htmlspecialchars($_POST["LastName"]);
    $MiddleInitial = htmlspecialchars($_POST["MiddleInitial"]);
    $BirthDate = htmlspecialchars($_POST["Bday"]);
    $age = htmlspecialchars($_POST["age"]);
    $Gender = htmlspecialchars($_POST["Gender"]);
    $Email = htmlspecialchars($_POST["Email"]);
    $PhoneNumber = htmlspecialchars($_POST["Phone"]);
    $Department = htmlspecialchars($_POST["Department"]);
    $Procedure = htmlspecialchars($_POST["Procedure"]);
    $AppointmentDate = htmlspecialchars($_POST["Appointment"]);
    $AppointmentTime = htmlspecialchars($_POST["appoint-time"]);
    $EmergencyP = htmlspecialchars($_POST["EmergencyP"]);
    $EmergencyN = htmlspecialchars($_POST["EmergencyN"]);
    $BookingType = htmlspecialchars($_POST["BookingType"]);
    $Status = htmlspecialchars($_POST["Status"]);

    // Validate form data
    if (empty($EmergencyP) || empty($AppointmentTime) || empty($EmergencyN) || empty($FirstName) || 
        empty($LastName) || empty($MiddleInitial) || empty($BirthDate) || empty($age) || empty($Gender) || 
        empty($Email) || empty($PhoneNumber) || empty($Department) || empty($Procedure) || empty($AppointmentDate) || 
        empty($BookingType) || empty($Status)) {
        $errorMessage = "All fields are required";
    } elseif ($BookingType != 'manual' && $BookingType != 'site') {
        $errorMessage = "Booking Type must be either 'manual' or 'site'";
    } else {
        $currentDate = date('Y-m-d');

        if ($BirthDate > $currentDate) {
            $errorMessage = "Birthdate cannot be after the current date";
        } else {
            try {
                // Check if Email already exists
                $sql_check_email = "SELECT COUNT(*) AS count FROM patientgeneralinformation WHERE Email = ? AND PatientID != ?";
                $stmt_check_email = $conn->prepare($sql_check_email);
                $stmt_check_email->bind_param("si", $Email, $AppointmentID);
                $stmt_check_email->execute();
                $stmt_check_email->bind_result($email_count);
                $stmt_check_email->fetch();
                $stmt_check_email->close();

                if ($email_count > 0) {
                    $errorMessage = "Email already exists";
                } else {
                    // Check if PhoneNumber already exists
                    $sql_check_phone = "SELECT COUNT(*) AS count FROM patientgeneralinformation WHERE PhoneNumber = ? AND PatientID != ?";
                    $stmt_check_phone = $conn->prepare($sql_check_phone);
                    $stmt_check_phone->bind_param("si", $PhoneNumber, $AppointmentID);
                    $stmt_check_phone->execute();
                    $stmt_check_phone->bind_result($phone_count);
                    $stmt_check_phone->fetch();
                    $stmt_check_phone->close();

                    if ($phone_count > 0) {
                        $errorMessage = "Phone number already exists";
                    } else {
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
                                    p.AppointmentTime = ?,
                                    p.BookingType = ?,
                                    p.Status = ?,
                                    p.EmergencyContactName = ?,
                                    p.EmergencyContactPhone = ?
                                WHERE p.PatientID = ?";

                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("ssssisssssssssssi", $FirstName, $LastName, $MiddleInitial, $BirthDate, $age, $Gender, $Email, $PhoneNumber, $Department, $Procedure, $AppointmentDate, $BookingType, $Status, $AppointmentTime, $EmergencyP, $EmergencyN, $AppointmentID);
                        $stmt->execute();

                        if ($stmt->affected_rows > 0) {
                            $successMessage = "Client updated correctly";
                            header("location: clients.php");
                            exit;
                        } else {
                            $errorMessage = "Error updating record: " . $conn->error;
                        }
                    }
                }
            } catch (Exception $e) {
                $errorMessage = "Error: " . $e->getMessage();
            }
        }
    }
}

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
        <h2>New Client</h2>

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
                    <input type="date" class="form-control" name="Bday" value="<?php echo $BirthDate; ?>">
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
                    <select class="form-control" name="Gender">
                        <option value="selgen" selected hidden>Select Gender</option>
                        <option value="Male" <?php if ($Gender == 'Male') echo 'selected'; ?>>Male</option>
                        <option value="Female" <?php if ($Gender == 'Female') echo 'selected'; ?>>Female</option>
                        <option value="Other" <?php if ($Gender == 'Other') echo 'selected'; ?>>Other</option>
                    </select>
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
                    <input type="text" class="form-control" name="Phone" pattern="[0-9]{11}" value="<?php echo $PhoneNumber; ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Department</label>
                <div class="col-sm-6">
                    <select class="form-control" name="Department">
                        <option value="seldep" selected hidden>Select a Department</option>
                        <option value="Family Medicine" <?php if ($Department == 'Family Medicine') echo 'selected'; ?>>Family Medicine</option>
                        <option value="Primary Care" <?php if ($Department == 'Primary Care') echo 'selected'; ?>>Primary Care</option>
                        <option value="General Practice" <?php if ($Department == 'General Practice') echo 'selected'; ?>>General Practice</option>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Procedure</label>
                <div class="col-sm-6">
                    <select class="form-control" name="Procedure">
                        <option value="selpro" selected hidden>Select a Procedure</option>
                        <option value="Medical Management" <?php if ($Procedure == 'Medical Management') echo 'selected'; ?>>Medical Management</option>
                        <option value="Preventive Care" <?php if ($Procedure == 'Preventive Care') echo 'selected'; ?>>Preventive Care</option>
                        <option value="Diagnosis" <?php if ($Procedure == 'Diagnosis') echo 'selected'; ?>>Diagnosis</option>
                        <option value="Treatment" <?php if ($Procedure == 'Treatment') echo 'selected'; ?>>Treatment</option>
                        <option value="Consultation" <?php if ($Procedure == 'Consultation') echo 'selected'; ?>>Consultation</option>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Appointment Date</label>
                <div class="col-sm-6">
                    <input type="date" class="form-control" name="Appointment" value="<?php echo $AppointmentDate; ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Appointment Time</label>
                <div class="col-sm-6">
                    <select class="form-control" name="appoint-time" required>
                        <option value="time" selected hidden>Select Time</option>
                        <option value="09:00 AM" <?php if ($AppointmentTime == '09:00 AM') echo 'selected'; ?>>09:00 AM</option>
                        <option value="09:30 AM" <?php if ($AppointmentTime == '09:30 AM') echo 'selected'; ?>>09:30 AM</option>
                        <option value="10:00 AM" <?php if ($AppointmentTime == '10:00 AM') echo 'selected'; ?>>10:00 AM</option>
                        <option value="10:30 AM" <?php if ($AppointmentTime == '10:30 AM') echo 'selected'; ?>>10:30 AM</option>
                        <option value="11:00 AM" <?php if ($AppointmentTime == '11:00 AM') echo 'selected'; ?>>11:00 AM</option>
                        <option value="11:30 AM" <?php if ($AppointmentTime == '11:30 AM') echo 'selected'; ?>>11:30 AM</option>
                        <option value="12:00 PM" <?php if ($AppointmentTime == '12:00 PM') echo 'selected'; ?>>12:00 PM</option>
                        <option value="12:30 PM" <?php if ($AppointmentTime == '12:30 PM') echo 'selected'; ?>>12:30 PM</option>
                        <option value="01:00 PM" <?php if ($AppointmentTime == '01:00 PM') echo 'selected'; ?>>01:00 PM</option>
                        <option value="01:30 PM" <?php if ($AppointmentTime == '01:30 PM') echo 'selected'; ?>>01:30 PM</option>
                        <option value="02:00 PM" <?php if ($AppointmentTime == '02:00 PM') echo 'selected'; ?>>02:00 PM</option>
                        <option value="02:30 PM" <?php if ($AppointmentTime == '02:30 PM') echo 'selected'; ?>>02:30 PM</option>
                        <option value="03:00 PM" <?php if ($AppointmentTime == '03:00 PM') echo 'selected'; ?>>03:00 PM</option>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Emergency Contact Name</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="EmergencyP" value="<?php echo $EmergencyP; ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Emergency Contact Phone</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="EmergencyN" pattern="[0-9]{11}" value="<?php echo $EmergencyN; ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Booking Type</label>
                <div class="col-sm-6">
                    <select class="form-control" name="BookingType">
                        <option value="manual" <?php echo ($BookingType == 'manual') ? 'selected' : ''; ?>>Manual</option>
                        <option value="site" <?php echo ($BookingType == 'site') ? 'selected' : ''; ?>>Site</option>
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

            <div class="row mb-3">
                <div class="col-sm-3 offset-sm-3 d-grid">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
                <div class="col-sm-3 d-grid">
                    <a class="btn btn-outline-primary" href="clients.php" role="button">Cancel</a>
                </div>
            </div>
        </form>

        <?php if (!empty($successMessage)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong><?php echo $successMessage; ?></strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>