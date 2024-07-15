<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <link rel="stylesheet" href="../../CSS/admin.css" />
    <link rel="stylesheet" href="../../CSS/destin.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
                    <h2 class="clients-text">Booked Appointments</h2>
                    <form method="GET" action="">
                        <button style="width: 150px; height: 30px; padding:5px; border-radius: 10px;" type="submit" name="filter" value="day">Today's Appointments</button>
                        <button style="width: 150px; height: 30px; padding:5px; border-radius: 10px;" type="submit" name="filter" value="week">Week's Appointments</button>
                    </form>
                </div>
                <br>
                <div style="flex-grow: 1; height: 100%; overflow: auto;">
                    <table class="table">
                        <thead>
                            <tr>
                                <th><h3>ID</h3></th>
                                <th><h3>Name</h3></th>
                                <th><h3>Age</h3></th>
                                <th><h3>Department</h3></th>
                                <th><h3>Procedure</h3></th>
                                <th><h3>Appointment Date</h3></th>
                                <th><h3>Time</h3></th>
                                <th><h3>Booking Type</h3></th>
                                <th><h3>Status</h3></th>
                                <th><h3>Actions</h3></th>
                            </tr>
                        </thead>
                        <tbody id="results">
                            <?php
                            session_start();
                            include "../connection.php";

                            // Determine the filter type
                            $filter = isset($_GET['filter']) ? $_GET['filter'] : 'day';

                            // Get the current date
                            $currentDate = date('Y-m-d');

                            // Calculate the start and end dates of the current week (assuming week starts on Monday)
                            $startOfWeek = date('Y-m-d', strtotime('monday this week', strtotime($currentDate)));
                            $endOfWeek = date('Y-m-d', strtotime('sunday this week', strtotime($currentDate)));

                            // Determine SQL query based on filter type
                            if ($filter == 'week') {
                                $sql = "SELECT p.PatientID, FirstName, LastName, MiddleInitial, Age, Department, Procedures, AppointmentDT, AppointmentTime, BookingType, Status 
                                        FROM patientgeneralinformation AS pgi 
                                        JOIN patient AS p ON pgi.PatientID = p.PatientID 
                                        WHERE DATE(AppointmentDT) BETWEEN '$startOfWeek' AND '$endOfWeek' AND Status != 'completed'";
                            } else {
                                $sql = "SELECT p.PatientID, FirstName, LastName, MiddleInitial, Age, Department, Procedures, AppointmentDT, AppointmentTime, BookingType, Status 
                                        FROM patientgeneralinformation AS pgi 
                                        JOIN patient AS p ON pgi.PatientID = p.PatientID 
                                        WHERE DATE(AppointmentDT) = '$currentDate' AND Status != 'completed'";
                            }

                            $result = $conn->query($sql);

                            // Read data of each row
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>"; 
                                echo "<td>" . $row['PatientID'] . "</td>";
                                echo "<td>" . $row['LastName'] . ", " . $row['FirstName'] . " " . $row['MiddleInitial'] . "</td>";
                                echo "<td>" . $row['Age'] . "</td>";
                                echo "<td>" . $row['Department'] . "</td>";
                                echo "<td>" . $row['Procedures'] . "</td>";
                                echo "<td>" . $row['AppointmentDT'] . "</td>";
                                echo "<td>" . $row['AppointmentTime'] . "</td>";
                                echo "<td>" . $row['BookingType'] . "</td>";
                                echo "<td>" . $row['Status'] . "</td>";
                                echo "<td>";
                                echo "<a class='btn-warning' href='edit.php?PatientID=" . $row['PatientID'] . "'>Edit</a> ";
                                echo "<a class='btn-danger' href='delete.php?PatientID=" . $row['PatientID'] . "'>Delete</a>";
                                echo "</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>