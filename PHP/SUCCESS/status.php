<?php
session_start();
include "../connection.php";

// Check if Paid button is pressed
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['PatientID']) && isset($_GET['action'])) {
    $PatientID = intval($_GET['PatientID']);
    $action = $_GET['action'];

    // Fetch current status and payment information
    $sql = "SELECT Status, Payment FROM patient WHERE PatientID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $PatientID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    // Handle DONE action
    if ($action === 'done') {
        if ($row['Status'] !== 'Completed' || $row['Payment'] !== 'Paid') {
            // Redirect back with alert if conditions are not met
            header("location: status.php?alert=invalidstatuspaymentfordone");
            exit;
        } else {
            // Redirect with confirmation alert
            echo "<script>
                    if (confirm('Are you sure? This cannot be changed. Double check your information.')) {
                        window.location.href = 'end.php?PatientID=$PatientID';
                    } else {
                        window.location.href = 'status.php';
                    }
                 </script>";
            exit;
        }
    }
}

// Fetch data to display on the page
$sql = "SELECT p.PatientID, pgi.FirstName, pgi.LastName, pgi.MiddleInitial, pgi.Age, pgi.Gender, p.Department, p.Procedures, p.AppointmentDT, p.Payment, p.AppointmentTime, p.BookingType, p.Status 
        FROM patientgeneralinformation AS pgi 
        JOIN patient AS p ON pgi.PatientID = p.PatientID 
        WHERE p.Status = 'Completed' AND p.Final != 'YES'";

$result = $conn->query($sql);

if (!$result) {
    die("Invalid query: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Status Page</title>
    <link rel="stylesheet" href="../../CSS/admin.css" />
    <link rel="stylesheet" href="../../CSS/destin.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>
<body>
    <div class="admin_home">
        <div class="navbar">
            <div class="logo_holder">
                <img src="../../images/LOGO.png" alt="logo" />
            </div>
            <div class="pages">
                <a href="../admin.php">
                    <ion-icon name="home-outline"></ion-icon>
                </a>
                <a href="../ACTIVITY-PAGE/activity.php">
                    <ion-icon name="clipboard-outline"></ion-icon>
                </a>
                <a href="../ALL-CLIENTS/clients.php">
                    <ion-icon name="people-outline"></ion-icon>
                </a>
                <a href="../SUCCESS/status.php">
                    <ion-icon name="checkmark-done-outline"></ion-icon>
                </a>
            </div>
        </div>
        <div class="activity_section">
            <div class="container-my-5">
                <div class="act-head">
                    <h2 class="clients-text">Payments</h2>
                </div>
                <br>
                <div style="flex-grow: 1;height: 100%;overflow: auto;">
                    <table class="table">
                        <thead>
                            <tr>
                                <th><h3>Name</h3></th>
                                <th><h3>Gender</h3></th>
                                <th><h3>Department</h3></th>
                                <th><h3>Procedure</h3></th>
                                <th><h3>Appointment Date & Time</h3></th>
                                <th><h3>Booking Type</h3></th>
                                <th><h3>Status</h3></th>
                                <th><h3>Payment</h3></th>
                                <th><h3>Actions</h3></th>
                                <th><h3>Finished</h3></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['LastName'] . ", " . $row['FirstName'] . " " . $row['MiddleInitial']; ?></td>
                                <td><?php echo $row['Gender']; ?></td>
                                <td><?php echo $row['Department']; ?></td>
                                <td><?php echo $row['Procedures']; ?></td>
                                <td><?php echo $row['AppointmentDT'] . " at " . $row['AppointmentTime']; ?></td>
                                <td><?php echo $row['BookingType']; ?></td>
                                <td><?php echo $row['Status']; ?></td>
                                <td><?php echo $row['Payment']; ?></td>
                                <td>
									<a class="btn-danger" href="unpaid.php?PatientID=<?php echo $row['PatientID']; ?>">PAY</a>
                                    <a class="btn-warning" href="paid.php?PatientID=<?php echo $row['PatientID']; ?>">PAID</a>
                                </td>
                                <td>
                                    <a class="btn-end" href="status.php?action=done&PatientID=<?php echo $row['PatientID']; ?>"><ion-icon name="bag-check"></ion-icon>DONE</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

    <?php
    // Handle alerts
    if (isset($_GET['alert'])) {
        $alert = $_GET['alert'];
        switch ($alert) {
            case 'invalidstatuspaymentfordone':
                echo "<script>alert('Status must be completed and Payment must be paid to mark as DONE.');</script>";
                break;
            default:
                break;
        }
    }
    ?>
</body>
</html>