<?php
session_start();
include 'db_connecting.php'; // Ensure this file has the correct DB connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input data to avoid SQL injection
    $name = mysqli_real_escape_string($conn, $_POST['user_name']);
    $email = mysqli_real_escape_string($conn, $_POST['user_email']);
    $password = mysqli_real_escape_string($conn, $_POST['user_password']);
    $confirmPassword = mysqli_real_escape_string($conn, $_POST['confirmPassword']);

    // Check if passwords match
    if ($password !== $confirmPassword) {
        header('Location: usersignup.html?error=Passwords do not match.');
        exit();
    }

    // Check if email already exists
    $checkEmailQuery = "SELECT * FROM users WHERE user_email='$email'";
    $result = mysqli_query($conn, $checkEmailQuery);
    if (mysqli_num_rows($result) > 0) {
        header('Location: usersignup.html?error=Email already exists.');
        exit();
    }

    // Insert user into the database without password hashing
    $insertQuery = "INSERT INTO users (user_name, user_email, user_password) VALUES ('$name', '$email', '$password')";
    if (mysqli_query($conn, $insertQuery)) {
        // Redirect to the sign-in page
        header('Location: usersignin.html');
        exit();
    } else {
        // Handle database error
        header('Location: usersignup.html?error=Error during registration.');
        exit();
    }
}
?>
