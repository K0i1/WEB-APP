<?php
session_start();
include 'db_connecting.php'; // Database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password = mysqli_real_escape_string($conn, trim($_POST['password']));
    $confirmPassword = mysqli_real_escape_string($conn, trim($_POST['confirmPassword']));

    // Check if passwords match
    if ($password !== $confirmPassword) {
        echo "Passwords do not match.";
        exit();
    }

    // Check if email already exists
    $checkEmailQuery = "SELECT * FROM admin WHERE signup_email_A='$email'";
    $result = mysqli_query($conn, $checkEmailQuery);
    if (mysqli_num_rows($result) > 0) {
        echo "Email already exists.";
        exit();
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert into database
    $insertQuery = "INSERT INTO admin(signup_name_A, signup_email_A, signup_password_A) VALUES ('$name', '$email', '$hashedPassword')";
    if (mysqli_query($conn, $insertQuery)) {
        echo "Admin registered successfully!";
        header("Location: login_A.php"); // Redirect to login page
    } else {
        echo "Error during registration: " . mysqli_error($conn);
    }
}

$conn->close();
?>
