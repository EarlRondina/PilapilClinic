<?php
session_start();
include "connection.php";

// Initialize variables
$EditUsername = "";
$EditEmail = "";
$EditPhone = "";

$errorMessage = "";
$successMessage = "";

function handleGetRequest($conn) {
    global $EditUsername, $EditEmail, $EditPhone;

    if (!isset($_GET['AccountID'])) {
        header("location: admin.php");
        exit;
    }

    $AccountID = $_GET["AccountID"];

    $accountsql = "SELECT Username, Email, PhoneNumber FROM account WHERE AccountID = ?";
    $accountstmt = $conn->prepare($accountsql);
    $accountstmt->bind_param("i", $AccountID);
    $accountstmt->execute();
    $accountresult = $accountstmt->get_result();
    $accountrow = $accountresult->fetch_assoc();

    if (!$accountrow) {
        header("location: admin.php");
        exit;
    }

    $EditUsername = $accountrow["Username"];
    $EditEmail = $accountrow["Email"];
    $EditPhone = $accountrow["PhoneNumber"];
}

function handlePostRequest($conn) {
    global $EditUsername, $EditEmail, $EditPhone, $errorMessage, $successMessage;

    $AccountID = $_POST["AccountID"];
    $EditUsername = htmlspecialchars($_POST["EditUsername"]);
    $EditEmail = htmlspecialchars($_POST["EditEmail"]);
    $EditPhone = htmlspecialchars($_POST["EditPhone"]);

    // Validate form data
    if (empty($EditUsername) || empty($EditEmail) || empty($EditPhone)) {
        $errorMessage = "All fields are required";
    } else {
        $accsql = "UPDATE account SET Username = ?, Email = ?, PhoneNumber = ? WHERE AccountID = ?";
        $accstmt = $conn->prepare($accsql);
        $accstmt->bind_param("sssi", $EditUsername, $EditEmail, $EditPhone, $AccountID);
        $accstmt->execute();

        // Check if the update was successful
        if ($accstmt->affected_rows > 0) {
            $successMessage = "Profile updated successfully";
            header("location: admin.php");
            exit;
        } else {
            $errorMessage = "Error updating record: " . $conn->error;
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    handleGetRequest($conn);
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    handlePostRequest($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class="container">
        <h2>Edit Profile</h2>

        <?php if (!empty($errorMessage)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong><?php echo $errorMessage; ?></strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form method="post">
            <input type="hidden" name="AccountID" value="<?php echo htmlspecialchars($_GET['AccountID']); ?>">

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Username</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="EditUsername" value="<?php echo htmlspecialchars($EditUsername); ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Email</label>
                <div class="col-sm-6">
                    <input type="email" class="form-control" name="EditEmail" value="<?php echo htmlspecialchars($EditEmail); ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Phone Number</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="EditPhone" value="<?php echo htmlspecialchars($EditPhone); ?>">
                </div>
            </div>

            <?php if (!empty($successMessage)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong><?php echo $successMessage; ?></strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="row mb-3">
                <div class="col-sm-3 offset-sm-3 d-grid">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
                <div class="col-sm-3 d-grid">
                    <a class="btn btn-outline-primary" href="admin.php" role="button">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</body>
</html>