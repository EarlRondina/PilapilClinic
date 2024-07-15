<?php
include "../connection.php";

// Initialize variables
$Department = "";
$Procedure = "";
$AppointmentDate = "";
$AppointmentTime = "";
$Status = "";

$errorMessage = "";
$successMessage = "";

// Function to handle GET request
function handleGetRequest($conn) {
    global $Department, $Procedure, $AppointmentDate, $AppointmentTime, $Status, $errorMessage;

    if (!isset($_GET['PatientID'])) {
        header("location: activity.php");
        exit;
    }

    $PatientID = $_GET["PatientID"];

    $sql = "SELECT PatientID, Department, Procedures, Status, AppointmentDT, AppointmentTime
            FROM patient
            WHERE PatientID = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $PatientID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if (!$row) {
        header("location: activity.php");
        exit;
    }

    $Department = $row["Department"];
    $Procedure = $row["Procedures"];
    $AppointmentDate = $row["AppointmentDT"];
    $AppointmentTime = $row["AppointmentTime"];
    $Status = $row["Status"];
}

// Function to handle POST request
function handlePostRequest($conn) {
    global $Department, $Procedure, $AppointmentDate, $AppointmentTime, $Status, $errorMessage, $successMessage;

    $PatientID = $_POST["PatientID"];
    $Department = htmlspecialchars($_POST["Department"]);
    $Procedure = htmlspecialchars($_POST["Procedure"]);
    $AppointmentDate = htmlspecialchars($_POST["Appointment"]);
    $AppointmentTime = htmlspecialchars($_POST["appoint-time"]);
    $Status = htmlspecialchars($_POST["Status"]);

    // Validate form data
    if (empty($Department) || empty($Procedure) || empty($AppointmentDate) || empty($AppointmentTime) || empty($Status)) {
        $errorMessage = "All fields are required";
    } else {
        // Update SQL statement
        $sql = "UPDATE patient
                SET Department = ?,
                    Procedures = ?,
                    AppointmentDT = ?,
                    AppointmentTime = ?,
                    Status = ?
                WHERE PatientID = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssi", $Department, $Procedure, $AppointmentDate, $AppointmentTime, $Status, $PatientID);
        $stmt->execute();

        // Check if the update was successful
        if ($stmt->affected_rows > 0) {
            $successMessage = "Client updated correctly";
            header("location: activity.php");
            exit;
        } else {
            $errorMessage = "Error updating record: " . $conn->error;
        }
    }
}

// Determine request method and call appropriate function
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    handleGetRequest($conn);
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    handlePostRequest($conn);
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
            <input type="hidden" name="PatientID" value="<?php echo $_GET['PatientID']; ?>">

            <!-- Department select dropdown -->
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

            <!-- Procedure select dropdown -->
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

            <!-- Appointment Date input -->
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Appointment Date</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="Appointment" value="<?php echo $AppointmentDate; ?>">
                </div>
            </div>

            <!-- Appointment Time select dropdown -->
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

            <!-- Status select dropdown -->
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

            <!-- Success message -->
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

            <!-- Submit and Cancel buttons -->
            <div class="row mb-3">
                <div class="col-sm-3 offset-sm-3 d-grid">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
                <div class="col-sm-3 d-grid">
                    <a class="btn btn-outline-primary" href="/PHP/ACTIVITY-PAGE/activity.php" role="button">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</body>

</html>