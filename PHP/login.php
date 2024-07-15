<?php
session_start(); // Start session for storing user data

if (isset($_POST['submit'])) {
    include "connection.php"; // Include your database connection script

    // Sanitize and retrieve input data
    $email_username = htmlspecialchars(trim($_POST['email_username']));
    $password = htmlspecialchars(trim($_POST['password']));

    // Query to fetch user information based on email or username
    $sql = "SELECT AccountID, Email, AccPassword FROM account WHERE Email = ? OR Username = ?";
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        echo "Error preparing statement: " . mysqli_error($conn);
        exit();
    }
    mysqli_stmt_bind_param($stmt, "ss", $email_username, $email_username);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt); // Store the result set

    // Check if any rows were returned
    if (mysqli_stmt_num_rows($stmt) > 0) {
        mysqli_stmt_bind_result($stmt, $AccountID, $email, $hashed_password);
        mysqli_stmt_fetch($stmt);

        // Debugging: Print hashed password
        echo "Hashed Password from DB: $hashed_password<br>";

        // Verify the password
        if (password_verify($password, $hashed_password)) {
            // Password is correct, store user information in session
            $_SESSION['Account_id'] = $AccountID;
            $_SESSION['email'] = $email;

            // Redirect to dashboard or home page
            header("Location: /PHP/admin.php");
            exit();
        } else {
            // Password is incorrect
            echo '<script>alert("Incorrect password.");</script>';
        }
    } else {
        // No user found with the given email
        echo '<script>alert("User not found.");</script>';
    }
    
    // Close statement and connection
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>