<?php
    session_start();
    include "../connection.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
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
                    <h2 class="clients-text">Patient Information</h2>
                    <input style="width:250px; height: 35px; border-radius: 15px; padding: 5px;" type="text" id="search" placeholder="Search by name...">
                    <form action="create.php" method="get">
                        <button type="submit" class="appointment-button">New Appointment</button>
                    </form>
                </div>
                <br>
                <div style="flex-grow: 1;height: 100%;overflow: auto;">
                    <table class="table">
                        <thead>
                            <tr>
                                <th><h3>ID</h3></th>
                                <th><h3>Name</h3></th>
                                <th><h3>Age</h3></th>
                                <th><h3>Birth Date</h3></th>
                                <th><h3>Gender</h3></th>
                                <th><h3>Email</h3></th>
                                <th><h3>Phone</h3></th>
                                <th><h3>Emergency Person</h3></th>
                                <th><h3>Emergency Number</h3></th>
                                <th><h3>Actions</h3></th>
                            </tr>
                        </thead>
                        <tbody id="results">
                            <?php
                            // Initial query to fetch all patients
                            $sql = "SELECT p.PatientID, FirstName, LastName, MiddleInitial, Age, BirthDate, Gender, Email, PhoneNumber, EmergencyContactName, EmergencyContactPhone 
                                    FROM patientgeneralinformation AS pgi 
                                    JOIN patient AS p ON pgi.PatientID = p.PatientID WHERE Status != 'Completed'";
                            $result = $conn->query($sql);

                            if (!$result) {
                                die("Invalid query: " . $conn->error);
                            }

                            // Read data of each row
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>"; 
                                echo "<td>" . $row['PatientID'] . "</td>";
                                echo "<td>" . $row['LastName'] . ", " . $row['FirstName'] . " " . $row['MiddleInitial'] . "</td>";
                                echo "<td>" . $row['Age'] . "</td>";
                                echo "<td>" . $row['BirthDate'] . "</td>";
                                echo "<td>" . $row['Gender'] . "</td>";
                                echo "<td>" . $row['Email'] . "</td>";
                                echo "<td>" . $row['PhoneNumber'] . "</td>";
                                echo "<td>" . $row['EmergencyContactName'] . "</td>";
                                echo "<td>" . $row['EmergencyContactPhone'] . "</td>";
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $("#search").on("input", function () {
                var searchQuery = $(this).val();
                $.ajax({
                    url: "search.php",
                    type: "GET",
                    data: { query: searchQuery },
                    success: function (data) {
                        $("#results").html(data);
                    },
                });
            });
        });
    </script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>