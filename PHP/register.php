<?php
if (isset($_POST['submit'])) {
    include "connection.php";
    
    $username = htmlspecialchars(trim($_POST['username']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $phone = htmlspecialchars(trim($_POST['phone']));
    $password = htmlspecialchars(trim($_POST['password']));
    
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    if ($hashed_password === false) {
        echo "Error hashing password.";
        exit();
    }

    $sql1 = "INSERT INTO account (Username, Email, PhoneNumber, AccPassword) VALUES (?, ?, ?, ?)";
    $stmt1 = mysqli_prepare($conn, $sql1);
    if (!$stmt1) {
        echo "Error preparing statement: " . mysqli_error($conn);
        exit();
    }

    mysqli_stmt_bind_param($stmt1, "ssss", $username, $email, $phone, $hashed_password);
    $result1 = mysqli_stmt_execute($stmt1);

    if ($result1) {
        echo '<script>alert("Registration successful!"); window.location.href = "../login.html";</script>';
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt1);
    
    mysqli_close($conn);
}
?>