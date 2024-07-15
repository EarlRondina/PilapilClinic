<?php
    include "../connection.php";

    if(isset($_GET['query'])) {
        $searchQuery = $_GET['query'];

        // Modify your SQL query to search for patients based on the search query
        $sql = "SELECT p.PatientID, FirstName, LastName, MiddleInitial, Age, BirthDate, Gender, Email, PhoneNumber, EmergencyContactName, EmergencyContactPhone 
                FROM patientgeneralinformation AS pgi 
                JOIN patient AS p ON pgi.PatientID = p.PatientID
                WHERE LastName LIKE ? OR FirstName LIKE ?";

        $stmt = mysqli_prepare($conn, $sql);
        $searchParam = "%$searchQuery%";
        mysqli_stmt_bind_param($stmt, "ss", $searchParam, $searchParam);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if(mysqli_num_rows($result) > 0) {
            // Output data of each row
            while($row = mysqli_fetch_assoc($result)) {
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
        } else {
            echo "<tr><td colspan='10'>No results found</td></tr>";
        }

        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    } else {
        echo "<tr><td colspan='10'>Invalid request</td></tr>";
    }
?>