<?php
session_start();
include "../connection.php";

$Amount = "";

$errorMessage = "";
$successMessage = "";

// Check if form is submitted via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate and sanitize input
    $Amount = htmlspecialchars($_POST["Amount"]);
    $PatientID = $_GET["PatientID"];

    // Validate form data
    if (empty($Amount) || !is_numeric($Amount)) {
        $errorMessage = "Please enter a valid amount.";
    } else {
        // Update SQL statement
        $sql = "UPDATE patient SET Amount = ? WHERE PatientID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("di", $Amount, $PatientID);
        $result = $stmt->execute();

        if ($result) {
            $successMessage = "Amount updated successfully";
            header("location: status.php");
        } else {
            $errorMessage = "Error updating amount";
        }
        $stmt->close();
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
        <h2>Payment</h2>
<h5>Price of Treatments or Procedure:</h5>
<h6>Medical Management = 500 PHP</h6>
<h6>Preventive Care = 500 PHP</h6>
<h6>Diagnosis = 500 PHP</h6>
<h6>Treatment = 500 PHP</h6>
<h6>Consultation = 500 PHP</h6>


        <?php if (!empty($errorMessage)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong><?php echo $errorMessage; ?></strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <form method="post">
            <input type="hidden" name="AppointmentID" value="<?php echo $AppointmentID; ?>">
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Amount</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="Amount" value="<?php echo $Amount; ?>">
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