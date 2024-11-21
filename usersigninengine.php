<?php
session_start(); // Start the session to manage user login
include 'db_connecting.php'; // Ensure this file connects correctly to your database

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['user_email']);
    $password = trim($_POST['user_password']);

    // Basic validation
    if (empty($email) || empty($password)) {
        $error = "Please enter both email and password.";
    } else {
        // Prevent SQL injection
        $email = mysqli_real_escape_string($conn, $email);

        // Query to find user by email
        $query = "SELECT * FROM users WHERE user_email = '$email'";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);

            // Compare passwords (using plain-text comparison for now)
            if ($password === $user['user_password']) { 
                // Set session variables
                $_SESSION['user_id'] = $user['user_id']; // Fixing the user_id
                $_SESSION['user_name'] = $user['user_name']; // Store user name for personalized greetings
                
                // Redirect to KOIKIES.html (your main page)
                header('Location: KOIKIES.html');
                exit();
            } else {
                $error = "Invalid email or password.";
            }
        } else {
            $error = "Invalid email or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - KOIKIES</title>
    <link rel="stylesheet" href="style.css"> <!-- Optional: your CSS file -->
</head>
<style>
    body {
        font-family: Arial, sans-serif;
        background-image: url("Screenshot 2024-11-02 233551.png");
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

    /* Sign In Form Style */
    .signin-container {
        background-color: white;
        padding: 40px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        max-width: 400px;
        width: 100%;
    }

    .signin-container h2 {
        text-align: center;
        color: #888E4D;
        margin-bottom: 20px;
    }

    .signin-container form {
        display: flex;
        flex-direction: column;
    }

    .signin-container label {
        font-size: 1.2em;
        margin-bottom: 8px;
    }

    .signin-container input {
        padding: 10px;
        margin-bottom: 20px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 1em;
    }

    .signin-container button {
        padding: 10px;
        background-color: #9A9F69;
        color: white;
        font-size: 1.2em;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .signin-container button:hover {
        background-color: #888E4D;
    }

    .signup-link {
        text-align: center;
        margin-top: 20px;
    }

    .signup-link a {
        color: #9A9F69;
        text-decoration: none;
    }

    .signup-link a:hover {
        text-decoration: underline;
    }

    .error {
        color: red;
        text-align: center;
        margin-bottom: 20px;
    }
</style>
<body>
    <div class="signin-container">
        <h2>Login</h2>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form action="usersigninengine.php" method="post">
            <label for="email">Email:</label>
            <input type="email" id="email" name="user_email" required><br>

            <label for="password">Password:</label>
            <input type="password" id="password" name="user_password" required><br>

            <button type="submit">Login</button>
        </form>
        <p class="signup-link">Don't have an account? <a href="usersignup.html">Sign Up</a></p>
    </div>
</body>
</html>
