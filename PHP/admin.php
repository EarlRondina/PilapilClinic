<?php
session_start();
include "connection.php";

// Fetch user information
$sql = "SELECT Username FROM account WHERE AccountID = {$_SESSION['Account_id']}";
$result = $conn->query($sql);

$username = "";
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $username = $row['Username'];
}

// CHART 1
$chartDataSql = "SELECT Department, COUNT(*) as count FROM patient WHERE Final = 'YES' GROUP BY Department";
$chartDataResult = $conn->query($chartDataSql);

$chartData = [];
if ($chartDataResult->num_rows > 0) {
    while ($row = $chartDataResult->fetch_assoc()) {
        $chartData[] = $row;
    }
}

//CHART 2
$totalAmountSql = "SELECT SUM(Amount) AS totalAmount FROM patient WHERE Final = 'YES'";
$totalAmountResult = $conn->query($totalAmountSql);

$totalAmount = 0;
if ($totalAmountResult->num_rows > 0) {
    $row = $totalAmountResult->fetch_assoc();
    $totalAmount = $row['totalAmount'];
}

$totalSalesSql = "SELECT DATE_FORMAT(AppointmentDT, '%Y-%m-%d') AS day, SUM(Amount) AS total_sales
                  FROM patient
                  WHERE AppointmentDT >= CURDATE() - INTERVAL 7 DAY
                  GROUP BY DATE_FORMAT(AppointmentDT, '%Y-%m-%d')
                  ORDER BY day";

$totalSalesResult = $conn->query($totalSalesSql);

$totalSalesData = [];
while ($row = $totalSalesResult->fetch_assoc()) {
    $totalSalesData[$row['day']] = $row['total_sales'];
}
$totalSalesJson = json_encode($totalSalesData);

// CHART 3
$chartData1Sql = "SELECT COUNT(*) as count FROM patient";
$chartData1Result = $conn->query($chartData1Sql);

$chartData1 = [];
if ($chartData1Result->num_rows > 0) {
    $row = $chartData1Result->fetch_assoc();
    $chartData1 = $row['count'];
}

//CHART 4
$ActivityDataSql = "SELECT Status, COUNT(*) as count FROM patient GROUP BY Status";
$ActivityDataResult = $conn->query($ActivityDataSql);

$ActivityData = [];
if ($ActivityDataResult->num_rows > 0) {
    while ($row = $ActivityDataResult->fetch_assoc()) {
        $ActivityData[] = $row;
    }
}


$sqlLatestTransactions = "SELECT p.PatientID, pgi.FirstName, pgi.LastName, pgi.MiddleInitial, p.AppointmentDT, p.AppointmentTime
                          FROM patientgeneralinformation AS pgi 
                          JOIN patient AS p ON pgi.PatientID = p.PatientID 
                          WHERE p.Final = 'YES'
                          ORDER BY p.AppointmentDT DESC, p.AppointmentTime DESC";

$resultLatestTransactions = $conn->query($sqlLatestTransactions);
$conn->close();

// Encode the chart data as JSON 
$ActivityDataJson = json_encode($ActivityData);
$chartDataJson = json_encode($chartData);
$chartData1Json = json_encode($chartData1);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <link rel="stylesheet" href="../CSS/admin.css" />
	<link rel="stylesheet" href="../CSS/destin.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>
<body>
	<section class="Profile-drop">
		<div class="Profile-info">
			<a href="data.php">
				<ion-icon name="create-outline"></ion-icon>
				<p>Edit your Profile</p>
			</a>
			<a href="logout.php">
				<ion-icon name="log-out-outline"></ion-icon>
				<p>Log Out</p>
			</a>
		</div>
	</section>
    <div class="admin_home">
        <div class="navbar">
            <div class="logo_holder">
                <img src="../images/LOGO.png" alt="logo" />
            </div>
            <div class="pages">
                <a href="admin.php">
                    <ion-icon name="home-outline"></ion-icon>
                </a>
                <a href="ACTIVITY-PAGE/activity.php">
                    <ion-icon name="clipboard-outline"></ion-icon>
                </a>
                <a href="ALL-CLIENTS/clients.php">
                    <ion-icon name="people-outline"></ion-icon>
                </a>
                <a href="SUCCESS/status.php">
                    <ion-icon name="checkmark-done-outline"></ion-icon>
                </a>
            </div>
        </div>
        <div class="welcome">
            <div class="HI_there">
                <h1>Hi <?= htmlspecialchars($username); ?>,</h1>
                <h3>Welcome Back!</h3>
                <img src="../images/welcome.png" class="pic" alt="welcome" />
            </div>
            <div class="prog">
				<h5>Activity Status</h5>
                <canvas id="myChart3" width="100%" height="100%"></canvas>
            </div>
        </div>

        <div class="status">
            <div class="clinic_name">
                <h2>Pilipapil Clinic</h2>
                <div >
                    <h3 style="font-style: italic;">"An Apple a Day keeps the Doctor Away"</h3>
                </div>
            </div>
            <div class="scale">
                <div class="scale1">
                    <canvas id="myChart" width="100%" height="100%"></canvas>
                </div>
                <div class="scale2">
                    <canvas id="myChart2" width="100%" height="100%"></canvas>
                </div>
            </div>
            <div class="latest_transactions">
                <div class="act-head" style="height: fit-content; position: sticky;">
                    <h2 class="clients-text">Recent Transactions</h2>
                </div>
				<div class="lat" style="flex-grow: 1;overflow: auto;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Appointment Date & Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $resultLatestTransactions->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['LastName'] . ", " . $row['FirstName'] . " " . $row['MiddleInitial']); ?></td>
                                <td><?php echo htmlspecialchars($row['AppointmentDT'] . " at " . $row['AppointmentTime']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
				</div>
			</div>
        </div>

        <div class="connection">
            <div class="accountholder">
                <p><?= htmlspecialchars($username); ?></p>
                <a href="#" class="drop-icon" style="color:inherit;">
                    <ion-icon name="chevron-down"></ion-icon>
                </a>
                <img src="../images/img_acc.jpg" alt="img" />
            </div>
            <div class="account_box1">
				<h5>Total Sales (PHP):</h5>
				<h1><?php echo htmlspecialchars($totalAmount); ?></h1>
			</div>
            <div class="account_box2">
				<h5>Total Sales by Day (PHP)</h5>
				<br>
				<canvas id="myBarChart" width="100%" height="100%"></canvas>
			</div>
        </div>
    </div>
	<script src="../Javascript/profile.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/chart.js@3.5.1/dist/chart.min.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script>
        $(document).ready(function() {
            const chartData = <?= $chartDataJson; ?>;
            const labels = chartData.map(item => item.Department);
            const data = chartData.map(item => item.count);

            const ctx = document.getElementById('myChart').getContext('2d');
            const myChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Patient Counts by Department',
                        data: data,
                        backgroundColor: [
                            "rgb(255, 99, 132)",
                            "rgb(54, 162, 235)",
                            "rgb(255, 205, 86)",
                            "rgb(75, 192, 192)",
                            "rgb(153, 102, 255)",
                            "rgb(255, 159, 64)"
                        ],
                        hoverOffset: 4
                    }]
                }
            });
        });

        $(document).ready(function() {
            const totalPatients = <?= $chartData1Json; ?>;

            const ctx2 = document.getElementById('myChart2').getContext('2d');
            const myChart2 = new Chart(ctx2, {
                type: 'doughnut',
                data: {
                    labels: ['Total Patients'],
                    datasets: [{
                        label: 'Total Number of Patients',
                        data: [totalPatients],
                        backgroundColor: [
                            "rgb(54, 162, 235)"
                        ],
                        hoverOffset: 4
                    }]
                }
            });
        });

	document.addEventListener('DOMContentLoaded', function() {
            const salesData = <?= $totalSalesJson; ?>;
            const days = Object.keys(salesData);
            const salesValues = Object.values(salesData);

            const ctx = document.getElementById('myBarChart').getContext('2d');
            const myBarChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: days,
                    datasets: [{
                        label: 'Total Sales',
                        data: salesValues,
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });

	 $(document).ready(function() {
            const ActivityData = <?= $ActivityDataJson ?>;
            const labels = ActivityData.map(item => item.Status);
            const data = ActivityData.map(item => item.count);

            const ctx = document.getElementById('myChart3').getContext('2d');
            const myChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Activity Status',
                        data: data,
                        backgroundColor: [
                            "rgb(255, 99, 132)",
                            "rgb(54, 162, 235)",
                            "rgb(255, 205, 86)",
                            "rgb(75, 192, 192)",
                            "rgb(153, 102, 255)",
                            "rgb(255, 159, 64)"
                        ],
                        hoverOffset: 4
                    }]
                }
            });
        });
    </script>
</body>
</html>